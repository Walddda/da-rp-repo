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
        <button class="popup-cta back" @click="close()">Back</button>
        <div class="flex flex-auto"></div>
        <p v-if="download" class="main-pay-down-text text-sm text-gray-600 hover:text-gray-900">You already bought this beat</p>
        <p v-if="own" class="main-pay-down-text text-sm text-gray-600 hover:text-gray-900">This is your beat</p>
        <a v-if="download || own" :href="song.file_path" download>
          <button class="popup-cta download">Download</button>
        </a>
        <button v-if="!download && !own" class="popup-cta submit" @click="pay()" :disabled="download">Buy</button>
      </div>
    </div>
  </div>
</template>
<script>
import web3 from 'web3/dist/web3.min.js'

export default {
    name: 'Payment',
    
    components: {
        web3,
    },

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
        }
    },

    methods: {
        close() {
            this.emitter.emit('closePopupPayment');
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
          if (ethereum.selectedAddress) {
            ethereum.request({
              method: 'eth_sendTransaction',
              params: [
                  {
                  from: ethereum.selectedAddress,
                  to: this.song.is_beat.from_user.eth_address,
                  value: parseInt(web3.utils.toWei(String(this.song.is_beat.price), 'ether')).toString(16),
                  },
              ],
              })
              .then(
                (txHash) => {
                  console.log(txHash)
                  this.hash = txHash
                  this.download = true

                  let formData = new FormData()
                  console.log('transaction')
                  console.log(this.song.is_beat.from_user.id)
                  console.log(this.$page.props.auth.user.id)
                  formData.append('seller_id', this.song.is_beat.from_user.id)
                  formData.append('buyer_id', this.$page.props.auth.user.id)
                  formData.append('hash', this.hash)
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
                  })
              })
              .catch((error) => {
                console.error
                if (error.message.includes("User denied transaction signature")) {
                  this.emitter.emit('error', 'Transaction cancelled')
                }
              })
          } else {
            this.emitter.emit('error', 'No Wallet connected')
          }
        },

        getTransactions() {
          axios.get('/api/transactions')
            .then(response => {
              this.transactions = response.data
              this.transactions.forEach((transaction) => {
                if (transaction.buyer_id === this.$page.props.auth.user.id && transaction.beat_id == this.song.is_beat.id) {
                  this.download = true
                }
              })
            })
            .catch((error) => console.error)
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

.loader {
  border-top-color: #3498db;
  -webkit-animation: spinner 1.5s linear infinite;
  animation: spinner 1.5s linear infinite;
}

@-webkit-keyframes spinner {
  0% { -webkit-transform: rotate(0deg); }
  100% { -webkit-transform: rotate(360deg); }
}

@keyframes spinner {
  0% { transform: rotate(0deg); }
  100% { transform: rotate(360deg); }
}

.fade-enter-active, .fade-leave-active {
  transition: opacity .3s;
}
.fade-enter, .fade-leave-to /* .fade-leave-active below version 2.1.8 */ {
  opacity: 0;
}

</style>
