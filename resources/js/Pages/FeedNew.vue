<style lang="scss">
    // @import './app.scss';
</style>
<template>
    <div>
        <Head title="Home" />

        <div class="background-image "><!-- Foto von Dmitry Demidov von Pexels -->
            <div v-if="!$page.props.auth.user" class="w-full h-full flex items-center" :style="{backgroundColor: 'rgba(0,0,0,'+backgroundOp+')'}">
                <div :style="{opacity: (1-backgroundOp)}">
                <p class="heading-feed">Start selling<br>
                your beats now</p>
                <a href="/register"><button class="cta-main but-main mt-4">get started</button></a>
                </div>
            </div>
            <div v-else class="w-full h-full flex items-center" :style="{backgroundColor: 'rgba(0,0,0,'+backgroundOp+')'}">
                <div :style="{opacity: (1-backgroundOp)}">
                <p class="heading-feed">Find exactly<br>
                what you need</p>
                <search :keywords="searchTerm" loc="feed" feed></search>
                <!-- <button class="cta-main but-main mt-4">get started</button> -->
                </div>
            </div>
        </div>
        <!-- <home-page v-if="!$page.props.auth.user"></home-page> -->
        
        <nav-bar-new :backgroundOp="backgroundOp" :searchTerm="searchTerm" feed></nav-bar-new>

            <!-- <nav-bar :searchTerm="searchTerm"></nav-bar> -->

        <tracks :attr="{loc: 'feed'}"></tracks>

        <upload v-if="showPopupUpload"></upload>
        <popup-wallet v-if="showPopupWallet"></popup-wallet>
        <popup-payment v-if="showPopupPayment" :song="paymentTrack"></popup-payment>
        <!-- <PopupWallet :showing="walletPopup" @close="WalletPopup = false">
            <p>Would You like to connect your wallet?</p>
            <wallet/>
        </PopupWallet> -->
    </div>
</template>

<script>
import { Head } from "@inertiajs/inertia-vue3";
import axios from 'axios';
import NavBar from '@/Components/NavBar.vue'
import NavBarNew from '@/Components/NavBarNew.vue'
import Upload from '@/Components/PopupUpload.vue'
import PopupWallet from '@/Components/PopupWallet.vue'
import PopupPayment from '@/Components/PopupPayment.vue'
import Search from '@/Components/Search.vue';
import Tracks from '@/Components/Tracks.vue';

//   import VueSlider from 'vue-slider-component'
// import VueSlider from 'vue-slider-component/dist-css/vue-slider-component.umd.min.js'
// import 'vue-slider-component/dist-css/vue-slider-component.css'

export default {
    components: {
        Head,
        NavBar,
        NavBarNew,
        Upload,
        PopupWallet,
        PopupPayment,
        Search,
        Tracks,
    },

    props: { 
        paths: Array,
        logedIn: Number,
        givenSearchTerm: {type: String, default: ''},
    },

    data() {
        return {
            playing: false,
            currentPlaying: null,
            showPopupUpload: false,
            showPopupWallet: false,
            showPopupPayment: false,
            paymentTrack: null,
            backgroundOp: 0,
            walletPopup: true,
            searchTerm : this.givenSearchTerm,
        }
    },
    created () {
        window.addEventListener('scroll', this.onScroll);
    },
    destroyed () {
        window.removeEventListener('scroll', this.onScroll);
    },
    methods: {
        onScroll (event) {
            if(window.scrollY <= 840){
                // if(window.scrollY > this.lastScroll){
                //     this.backgroundOp += 0.01
                // }else{
                //     this.backgroundOp -= 0.01
                // }
                this.backgroundOp = scrollY/840;
            }else{
                this.backgroundOp = 1;
            }
            // this.lastScroll = window.scrollY
            // console.log('---------------------------')
            // console.log(window.scrollY)
            // console.log(this.backgroundOp)
        },
    },
    mounted() {
        this.emitter.on("closePopupUpload", () => {
            this.showPopupUpload = false;
            console.info('cl')
        });
        this.emitter.on("openPopupUpload", () => {
            this.showPopupUpload = true;
            console.info('op')
        });
        this.emitter.on("closePopupWallet", () => {
            this.showPopupWallet = false;
            console.info('cl')
        });
        this.emitter.on("openPopupWallet", () => {
            this.showPopupWallet = true;
            console.info('op')
        });
        this.emitter.on("closePopupPayment", () => {
            this.showPopupPayment = false;
            console.info('cl')
        });
        this.emitter.on("openPopupPayment", track => {
            this.paymentTrack = track;
            this.showPopupPayment = true;
            console.info('op')
        });

        this.emitter.on('upload-success',() =>{
            this.getTracks();
            console.log('yeeeah')
        })

        this.emitter.on("focus", bool =>{
            this.searchFocus = bool;
        })
    }
};
</script>

<style src="@vueform/slider/themes/default.css"></style>

<style scoped>
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

</style>