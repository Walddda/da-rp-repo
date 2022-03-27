<template>
  <div class="popup-bg-blurr" @click="close()">
    <div class="popup-main payment" @click.stop>
      <div class="popup-title">Buy</div>
        <p class="main-pay-title">{{ song.is_beat.title }}</p>
        <div class="main-pay-row">
          <p class="main-pay-artist">by {{ song.is_beat.from_user.username }}</p>
          <p class="main-pay-eth">{{ song.is_beat.price }} ETH</p>
        </div>
        <div class="main-pay-row">
          <p class="main-pay-feat">{{ song.is_beat.feature ? 'feat. ' + song.is_beat.feature : '' }}</p>
          <p class="main-pay-dol">= {{ this.calculating ? 'Loading...' : this.dollarPrice }} $</p>
        </div>
        <div class="main-pay-info">
          <img class="main-pay-img" :src="song.is_beat.get_cover.cover_path" />
          <div class="main-pay-content">
            {{song.is_beat.bpm}} BPM<br>
            {{song.is_beat.key}} <br>
            <span v-for="(ax,ak) in song.is_beat">
              <span v-if="ak.substring(0,3) == 'tag' && ax &&ak != 'tag1'">, </span>
              <span  v-if="ak.substring(0,3) == 'tag' && ax">
                {{ax}}</span>
            </span><br>
            {{song.is_beat.description}} <br>
          </div>
        </div>
      <div class="popup-footer">
        <button class="popup-cta back" @click="close()" >Back</button>
        
        <div class="flex flex-auto"></div>
        <!-- <p v-if="download" class="main-pay-down-text text-sm text-gray-600 hover:text-gray-900">You already bought this beat</p> -->
        <p v-if="own" class="main-pay-down-text text-sm text-gray-600 hover:text-gray-900">This is your beat</p>
        <a v-if="download || own" :href="song.file_path" download>
          <button class="popup-cta download">Download</button>

          
        </a>
        <div class="loader ease-linear rounded-full border-4 border-t-4 border-gray-200 h-12 w-12" v-if="loading"></div>
        <div v-if="!loading">
          <button v-if="!download && !own" class="popup-cta submit" @click="pay()" :disabled="download">Buy</button>
        </div>

        
        
      </div>
      
    </div>
  </div>
</template>
<script>
import Web3 from "web3"
import Beats from "../../dapp/build/contracts/Beats.json"
//import { purchaseBeat, getBeat } from '../services/blockchain.js'

export default {
    name: 'Payment',

    props:{
      song: Object,
    },

    data() {
        return {
            dollarPrice: 0,
            calculating: true,
            hash: '',
            download: false,
            transactions: [],
            own: this.$page.props.auth.user && this.song.is_beat.from_user.id === this.$page.props.auth.user.id,
            loading: false,
        }
    },

    methods: {
        close() {
            if (!this.loading) {
              this.emitter.emit('closePopupPayment');
            } else {
              this.emitter.emit('error', 'Please wait for transaction to complete')
            }
        },

        async purchaseBeat(contract, id, price, buyer) {
          const purchase = await contract.methods.purchaseBeat(id).send( { from: buyer, value: Web3.utils.toWei(price.toString(), 'Ether') })
            .once('transactionHash', (transasctionHash) => {
                    this.emitter.emit('success', 'Transaction started')
                    console.log(transasctionHash)
                })
            .once('receipt', (receipt) => {
              console.log(receipt)
              this.loading = false
              this.download = true
            })
            .on('error', (error) => {
              this.loading = false
              console.error(error)
            })
          return purchase
        },

        convert() {
          // this.calculating= true;
            axios.get('https://api.coinbase.com/v2/exchange-rates')
            .then(res => {
              this.calculating = false;
              this.dollarPrice = (Math.round(((this.song.is_beat.price / res.data.data.rates.ETH) + Number.EPSILON) * 100) / 100).toFixed(2)
            })
        },

        pay() {
          //console.log(this.song.is_beat.id, this.song.is_beat.from_user.eth_address)
          this.loading = true
          this.purchaseBeat(this.$store.state.contract, this.song.is_beat.id, this.song.is_beat.price, ethereum.selectedAddress)
            .then(response => {
              

              console.log('hi')

              let formData = new FormData()
              formData.append('seller_id', this.song.is_beat.from_user.id)
              formData.append('buyer_id', this.$page.props.auth.user.id)
              formData.append('hash', response.transactionHash)
              formData.append('beat_id', this.song.is_beat.id)

              const config = {
                      headers: {
                          'content-type': 'multipart/form-data',
                          'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                      },
                  }

              return axios.post('/api/transaction', formData, config)
                  .then(response => {
                    let formCount = new FormData();
                    formCount.append('beat_id', this.song.is_beat.id)
                    formCount.append('seller_id', this.song.is_beat.from_user.id)
                    formCount.append('download_count', this.counter)
                    this.emitter.emit('success', 'Track was successfully purchased')
                    return axios.post('/api/counter', formCount)
                    .then(res => {
                      console.log(res)
                      this.emitter.emit("cover-update", {'cov': res.cov, 'id': this.song.is_beat.id})
                    })
                  })
            })
            .catch((error) => {
                this.emitter.emit('error', 'Transaction cancelled')
                console.error(error)
              })
        },

        getTransactions() {
          axios.get('/api/transactions/'+this.song.is_beat.id+'/'+this.$page.props.auth.user.id)
            .then(response => {
              if(response.data.length){
                console.log(response.data)
                this.transactions[0] = response.data[0]
                this.download = true
              }
            })
            .catch((error) => console.error(error))
        },
    }, 
    
    mounted() {
      this.convert()
      this.getTransactions()
      this.emitter.on("reload", () => {
              this.convert();
              // console.log(text);
          });
      }, 
} 
</script>

<style>
.bg-blurr { 
    width: 100vw;
    height: 100vh;
    background-color:rgba(128, 128, 128, 0.233);
    position:fixed;
    top:0;
    display: flex;
    align-items: center;
    justify-content: center;
}
.popup{
    background-color: white;
    padding: 50px;
    border:black 1px solid;
    border-radius: 10%;
}
.cropImg{
    border: 2px solid green;
}

</style>
