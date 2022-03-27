<template>
    <!-- <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet"> -->
    <Transition name="fade">
      <div class="relative bg-blurr fixed inset-0 flex items-center justify-center bg-semi-75" @click="close()">
          <div class="popup text-center" @click.stop>
            <h2 class="text-xl font-bold text-gray-900">Wallet</h2>
              <p>Use the button to connect your metamask wallet</p>
              <wallet/>
          </div>
      </div>
    </Transition>
</template>

<script>
import Wallet from '@/Components/Wallet.vue';

export default {
    name: 'PopupWallet',
    components: {
      Wallet,
    },
    props: {
    },
    methods: {
        close() {
            this.emitter.emit('closePopupWallet');
        }
    },
}
</script>

<style scoped>
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