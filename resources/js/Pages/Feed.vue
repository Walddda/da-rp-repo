<style lang="scss">
    // @import './app.scss';
</style>
<template>
    <div>
        <Head title="Home" />

        <div class="background-image "><!-- Foto von Dmitry Demidov von Pexels -->
            <div class="w-full h-full flex items-center" :style="{backgroundColor: 'rgba(0,0,0,'+backgroundOp+')'}">
                <div :style="{opacity: (1-backgroundOp)}">
                <p class="heading-feed">Start selling<br>
                your beats now</p>
                <button class="cta-main but-main mt-4">get started</button>
                </div>
            </div>
        </div>
        <!-- <home-page v-if="!$page.props.auth.user"></home-page> -->
        
        <nav-bar-new :backgroundOp="backgroundOp" :searchTerm="searchTerm"></nav-bar-new>

            <!-- <nav-bar :canLogin="canLogin" :canRegister="canRegister" :searchTerm="searchTerm"></nav-bar> -->

            <audio v-if="files" style="display:none" ref="player"  preload="metadata" >
                <source type="audio/mp3"/>
            </audio>
        <div>
            <!-- {{$page.props}} -->
            <!-- {{logedIn}} ---{{this.$page.props.auth.user.id}} -->
            <div v-if="files">
                <div class="container mb-96 mt-10 flex mx-auto w-full items-center justify-center">
                    <ul class="flex flex-col p-4 grid justify-items-center">
                        <!-- <li 
                            v-for="x in files" 
                            class="border-gray-400 flex flex-row my-5">
                            <div class="select-none flex flex-1 items-center p-4 transition duration-500 ease-in-out transform hover:-translate-y-2 rounded-2xl border-2 p-6 hover:shadow-2xl border-red-400">
                                <div class="flex-1 pl-1">
                                        <player :track="x"/>
                                </div>
                            </div>
                        </li> -->

                        <li v-for="(x, k) in files">
                            <player v-if="k == currentPlaying-1" :track="x" :numb="k+1" :rn="playing" :info="info" current/>
                            <player v-else :track="x" :numb="k+1" :info="info" />
                        </li>
                    </ul>
                </div>

                
                
            </div>
            <div v-else-if="!files & !loading & !searchEmpty">Sowwy there are no files</div>
            <div v-if="searchEmpty">Sowwy there are no files with that search</div>

            <div class="loader ease-linear rounded-full border-4 border-t-4 border-gray-200 h-12 w-12" v-if="loading"></div>



            <div v-if="currentPlaying" class="border-t-2 border-black pt-7 pb-7 fixed w-full bottom-0 flex flex-col items-center bg-white">
                <div class="w-5/12 flex flex-row items-center"> 
                    <img class="box-border h-28 w-28 border-2 border-black" :src="files[currentPlaying-1].is_beat.get_cover.cover_path" alt="Album Pic">
                    <!-- <img class="transform scale-75" :src="'/storage/covers/'+files[currentPlaying].is_beat.get_cover.name" alt="Album Pic"> -->
                    <div class="w-10/12 px-5 flex flex-col items-center">
                        <div class="w-full" v-if="files">{{files[currentPlaying-1].is_beat.title}} - {{files[currentPlaying-1].is_beat.from_user.username}}</div>
                        <slider2 v-if="files" class="text-center" trackColor="#020203" v-model="audio.curLength.sum" :max="audio.length.sum" @dragging="updateCurTime" @click="testClick"/>
                        <div v-if="files" class="flex justify-between items-center w-full">
                            <span>{{audio.curLength.min}}:<span v-if="audio.curLength.sec <= 9">0</span>{{audio.curLength.sec}}</span>
                            <span>{{audio.length.min}}:<span v-if="audio.length.sec <= 9">0</span>{{audio.length.sec}}</span>
                        </div>
                    </div>
                    <div class="text-white p-8 rounded-full bg-red-light shadow-lg" @click="play" >
                        <svg v-if="playing" class="w-8 h-8" fill="black" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20"><path d="M5 4h3v12H5V4zm7 0h3v12h-3V4z"/></svg>
                        <svg v-else class="w-8 h-8" fill="black" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20"><path d="M 5 4 l 10 6 l -10 6 z z"/></svg>
                    </div>
                </div>
            </div>
            <!-- <div v-else class="border-t-2 border-black pt-7 pb-7 fixed w-full bottom-0 flex flex-col items-center bg-white">
                <div class="w-5/12 flex flex-row items-center"> 
                    <img class="box-border h-28 w-28 border-2 border-black" src="/storage/covers/placeholder.jpg" alt="Album Pic">-->
                    <!-- <img class="transform scale-75" :src="'/storage/covers/'+files[currentPlaying].is_beat.get_cover.name" alt="Album Pic"> -->
                    <!-- <div class="w-10/12 px-5 flex flex-col items-center">
                        <div class="w-full">-</div>
                        <slider2 class="text-center" v-model="audio.curLength.sum" trackColor="#020203"/>
                        <div class="flex justify-between items-center w-full">
                            <span>00:00</span>
                            <span>00:00</span>
                        </div>
                    </div>
                    <div class="text-white p-8 rounded-full bg-red-light shadow-lg">
                        <svg class="w-8 h-8" fill="grey" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20"><path d="M 5 4 l 10 6 l -10 6 z z"/></svg>
                    </div>
                </div> 
            </div>-->
        </div>
            <upload v-if="showPopupUpload"></upload>
            <popup-wallet v-if="showPopupWallet"></popup-wallet>
            <!-- <PopupWallet :showing="walletPopup" @close="WalletPopup = false">
                <p>Would You like to connect your wallet?</p>
                <wallet/>
            </PopupWallet> -->
    </div>
</template>

<script>
import BreezeAuthenticatedLayout from "@/Layouts/Authenticated.vue";
import { Head } from "@inertiajs/inertia-vue3";
import Player from '@/Components/Player.vue';
import Slider from '@vueform/slider'
import Slider2 from "vue3-slider"
import axios from 'axios';
import NavBar from '@/Components/NavBar.vue'
import NavBarNew from '@/Components/NavBarNew.vue'
import Upload from '@/Components/PopupUpload.vue'
import HomePage from '@/Components/HomePage.vue'
import PopupWallet from '@/Components/PopupWallet.vue'
//   import VueSlider from 'vue-slider-component'
// import VueSlider from 'vue-slider-component/dist-css/vue-slider-component.umd.min.js'
// import 'vue-slider-component/dist-css/vue-slider-component.css'

export default {
    components: {
        BreezeAuthenticatedLayout,
        Head,
        Player,
        Slider,
        Slider2,
        NavBar,
        NavBarNew,
        Upload,
        HomePage,
        // Wallet,
        PopupWallet,
    },

    props: { 
        // files: Array,
        paths: Array,
        canLogin: Boolean,
        canRegister: Boolean,
        logedIn: Number,
        searchTerm: {type: String, default: ''},
    },

    data() {
        return {
            files: null,
            loadedBeats: false,
            rendered: false,
            playing: false,
            currentPlaying: null,
            audio: {
                length: {min:0,sec:0, sum:1},
                curLength: {min:0,sec:0, sum:0},
                curLengthOld: {min:0,sec:0, sum:0}
            },
            value: 20,
            showPopupUpload: false,
            showPopupWallet: false,
            info: null,
            loading: true,
            searchEmpty: false,
            searchFocus: false,
            backgroundOp: 0,
            walletPopup: true,
        }
    },
    created () {
        window.addEventListener('scroll', this.onScroll);
    },
    destroyed () {
        window.removeEventListener('scroll', this.onScroll);
    },
    methods: {
        getTracks(term = this.searchTerm){
            this.searchEmpty = false;
            console.info('start');
            axios.get('/api/beats', { params: { keywords: term } })
            .then(response => {
                if(!response.data.length){
                    console.info('empty')
                    this.searchEmpty = true;
                    this.loading = false;
                }else{
                    this.files = response.data
                    console.info('finish: ');
                    console.info(response.data);
                    this.loadedBeats = true;
                    console.info(this.$refs.player)
                    this.loading = false;

                }
            })
        },
        play(){
            this.playing = !this.playing;
            this.toggleAudio();
            this.getLength()
        },

        toggleAudio(){         
            var player = this.$refs.player;
            player.volume = 1.0;
            console.warn(player.getAttribute("src"))
            try {
                if (player.paused) {player.play()}
            else {player.pause()}
            } catch (err) {
                console.error(err);
                console.error('r182 - sooooowy')
            }
        },

        getLength(){
            if (this.files  && this.$refs.player) {
            var sis = this;
            this.$refs.player.onloadedmetadata = function() {
                var min = Math.trunc(this.duration/60)
                var sec = (Math.trunc(this.duration) - min*60 ).toFixed(0)
                // console.log(min+':'+sec + '--------'+this.duration)
                sis.audio.length.sum = this.duration
                sis.audio.length.min = min
                sis.audio.length.sec = sec
                
            }}
            // this.audio.length = this.$refs.player.duration/60
        },

        getCurrentTime(){
            // console.log('getCurrentTime()')
            if (this.files && this.loadedBeats && this.$refs.player) {
            // console.log(this.$refs.player.currentTime)
                if(this.$refs.player.currentTime >= this.audio.length.sum){
                    this.audio.curLength.sum = 0;
                    this.playing = false;
                }else{
                    var min = Math.trunc(this.$refs.player.currentTime/60)
                    var sec = (Math.trunc(this.$refs.player.currentTime) - min*60 ).toFixed(0)
                    this.audio.curLength.sum = this.$refs.player.currentTime
                    this.audio.curLength.min = min
                    this.audio.curLength.sec = sec
                    // console.log(this.audio.curLength.sum);
                }  
            }
        },
        updateCurTime($event){
            // console.log($event);
            this.$refs.player.currentTime = $event;
            // this.getCurrentTime()
        },
        testClick($event){
            // console.log($event)
            // console.log($event.layerX);
            // console.log($event.srcElement.offsetParent.clientWidth);
            // console.log(this.audio.length.sum);
            var disc = $event.srcElement.offsetParent.clientWidth/this.audio.length.sum;
            // console.log('disc '+disc)
            var calc = $event.layerX/disc
            // console.log('spould '+calc)
            this.$refs.player.currentTime = calc;
            // console.log(this.$refs.player);
            // console.log('--------------------------'+calc)
        },
        changeCurTime(value){
            // this.audio.curLength.sum += value;
            this.$refs.player.currentTime += value;
            // console.log('r239')
        },
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
            this.lastScroll = window.scrollY
            console.log('---------------------------')
            console.log(window.scrollY)
        },

    },
    mounted() {    
        // this.$nextTick(function () {
        //     this.rendered = true;
        // })
        this.getTracks(this.searchTerm);

        this.getCurrentTime();
        setInterval(this.getCurrentTime, 100);


        this.getLength();
        this.emitter.on("test-emit", text => {
            this.playing = !this.playing;
            // console.log(text);
        });
        this.emitter.on("show-info", num => {
            if(this.info == num){
                this.info = null;
            }else{
                this.info = num;
            }
        });
        this.emitter.on("play-pause", numb => {
            if(this.currentPlaying === null){
                // console.log('r266')
                this.currentPlaying = numb;
            }
            else if(this.currentPlaying == numb || !this.playing){
                this.currentPlaying = numb;
                this.playing = !this.playing;
                this.toggleAudio();
                // console.log('r273')
            }
            this.currentPlaying = numb;
            this.getLength()
            // console.log(text);
        });
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

        this.emitter.on('upload-success',() =>{
            this.getTracks();
            console.log('yeeeah')
        })

        this.emitter.on("search", term => {
            this.files = null
            this.loading = true;
            this.getTracks(term);
            // console.log(text);
        });
        this.emitter.on("focus", bool =>{
            this.searchFocus = bool;
        })

        this.$watch('currentPlaying', () => {
                this.getLength()
                // console.log('r301')
                if(this.currentPlaying){
                    console.warn(this.currentPlaying);
                    this.$refs.player.src = this.files[this.currentPlaying-1].file_path;
                    this.$refs.player.load()
                    // console.log('r305');
                    this.playing = true;
                    this.toggleAudio();
                    // console.log('r308')
                }

            });
        // this.$watch('loadedBeats', () => {
        //         if(this.rendered && this.$refs.player){
        //         this.$refs.player.load()
        //         console.log('r315');
        //         this.getLength()}
        //     })
        // this.$watch('rendered', () => {
        //         if(this.loadedBeats){
        //         this.$refs.player.load()
        //         console.log('r321');
        //         this.getLength()}
        //     })

        
        window.addEventListener('keydown', (e) => {
            // console.log(e.key +' - '+ e.keyCode); 
            if(!this.searchFocus && !this.showPopupUpload && this.currentPlaying){
                if (e.key === 'Spacebar' || e.keyCode === 32) {
                        this.play();
                }else if (e.key === 'ArrowLeft' || e.keyCode === 37) {
                    this.changeCurTime(-10);
                }else if (e.key === 'ArrowRight' || e.keyCode === 39) {
                    this.changeCurTime(10);
                }
            }
        });
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