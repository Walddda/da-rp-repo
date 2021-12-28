<template>
    <div class="relative bg-blurr fixed inset-0 flex items-center justify-center bg-semi-75" @click="close()">
        <div class="popup text-center" @click.stop>
        {{ song.is_beat.title }}<br>-<br>
        {{ song.is_beat.from_user.username }}

        {{song.is_beat.from_user.eth_address}}
        
        <span v-if="song.is_beat.feature"> feat. {{ song.is_beat.feature }} </span> <br>-<br>

        price: {{ this.calculating ? 'Loading...' : this.dollarPrice }} $<br>-<br>
        ether price: {{ song.is_beat.price }} ETH

        
        <div class="popup-footer">
            <button class="popup-cta back" @click="close()">Cancel</button>
            <button class="popup-cta submit" @click="pay()">Purchase</button>
        </div>
        
        </div>
    </div>
</template>
<script>
import web3 from 'web3/dist/web3.min.js'
import { ethers } from "ethers"

export default {
    name: 'Payment',
    
    components: {
        ethers,
        web3,
    },

    props:{
      song: Object,
    },

    data() {
        return {
            dollarPrice: 0,
            calculating: true,
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
            ethereum
              .request({
                  method: 'eth_sendTransaction',
                  params: [
                      {
                      from: this.$page.props.auth.user.eth_address,
                      to: this.song.is_beat.from_user.eth_address,
                      value: parseInt(web3.utils.toWei(String(this.song.is_beat.price), 'ether')).toString(16),
                      },
                  ],
              })
              .then((txHash) => console.log(txHash))
              .catch((error) => console.error);
        },
    }, 
    
    mounted() {
      this.convert()
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
