<template>
    <div>
        <audio v-if="files" style="display:none" ref="player"  preload="metadata" >
            <source type="audio/mp3"/>
        </audio>
        <div ref="feedDiv">
            <div class="main-feed-div flex justify-center" :class="[attr.loc == 'feed' || loadEmpty || loading || attr.loc == 'single' ? 'items-center' : '']">
                <div v-if="files">
                <div v-if="searchTerm" class="flex flex-row">
                <div class="mr-4 cursor-pointer" @click="searchBack">
                    <svg class="h-6 w-6" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 448 512"><!--! Font Awesome Pro 6.1.1 by @fontawesome - https://fontawesome.com License - https://fontawesome.com/license (Commercial License) Copyright 2022 Fonticons, Inc. --><path d="M447.1 256C447.1 273.7 433.7 288 416 288H109.3l105.4 105.4c12.5 12.5 12.5 32.75 0 45.25C208.4 444.9 200.2 448 192 448s-16.38-3.125-22.62-9.375l-160-160c-12.5-12.5-12.5-32.75 0-45.25l160-160c12.5-12.5 32.75-12.5 45.25 0s12.5 32.75 0 45.25L109.3 224H416C433.7 224 447.1 238.3 447.1 256z"/></svg>
                </div><div>Search results for "{{searchTerm}}":</div></div>
                    <div class="container flex mx-auto w-full justify-center" :class="[attr.loc == 'feed' || loadEmpty || loading || attr.loc == 'single' ? 'items-center' : '']">
                        <ul class="flex flex-col p-4 grid justify-items-center">
                            <li v-for="(x, k) in files" >
                                <player v-if="k == currentPlaying-1" :track="x" :numb="k+1" :rn="playing" :info="info" :volume="volume" current :curTime="[cache ? cache : audio.curLength.sum]" @vol="volChange($event)"/>
                                <player v-else :track="x" :numb="k+1" :info="info" :volume="volume" @vol="volChange($event)" />
                            </li>
                        </ul>
                    </div>
                    
                </div>
                <div v-if="loadEmpty && attr && attr.loc == 'feed'">Be the first one to upload your work at Beatchain.</div>
                <div v-if="loadEmpty && attr && attr.loc == 'prof'">There is nothing to see.</div>
                <div v-if="loadEmpty && attr && attr.loc == 'single'">This track doesn't exist.</div>
                <div v-if="loadEmpty && attr && attr.loc == 'prof-bought'">Buy your first beat.</div>
                <div v-if="searchEmpty"><div class="mr-4 cursor-pointer" @click="searchBack">
                    <svg class="h-6 w-6" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 448 512"><!--! Font Awesome Pro 6.1.1 by @fontawesome - https://fontawesome.com License - https://fontawesome.com/license (Commercial License) Copyright 2022 Fonticons, Inc. --><path d="M447.1 256C447.1 273.7 433.7 288 416 288H109.3l105.4 105.4c12.5 12.5 12.5 32.75 0 45.25C208.4 444.9 200.2 448 192 448s-16.38-3.125-22.62-9.375l-160-160c-12.5-12.5-12.5-32.75 0-45.25l160-160c12.5-12.5 32.75-12.5 45.25 0s12.5 32.75 0 45.25L109.3 224H416C433.7 224 447.1 238.3 447.1 256z"/></svg>
                </div>Your search "{{searchTerm}}" had no result.</div>

                <div class="loader ease-linear rounded-full border-4 border-t-4 border-gray-200 h-12 w-12" v-if="loading"></div>
            </div>


            <div v-if="currentPlaying" :style="[showGlobal ? {} : {'bottom': '-100px'}]" class="transition-all fixed bottom-0 flex flex-col w-full">
                <div class="flex flex-col items-center"> 
                    <div class="main-slider-container slider-global w-full" @click="mainSlider" @drag="mainSlider" ref="mainSliderRef" @dragend="mainSlider">
                        <div class="main-slider-big">
                            <div class="main-slider-play" 
                            :style="[audio.curLength.sum ? 
                                {width: ((audio.curLength.sum*100)/audio.length.sum)+'%'} : 
                                this.$page.props.tracks[currentPlaying] ? {width: ((this.$page.props.tracks[currentPlaying]*100)/audio.length.sum)+'%'} :
                                {width: ((audio.curLength.sum*100)/audio.length.sum)+'%'}]"></div>
                            <div class="main-slider-hider"></div>
                        </div>
                    </div>
                    <div class="w-full flex flex-row main-global-bottom">
                        <img class="box-border h-24 w-24" loading="lazy" :src="files[currentPlaying-1].is_beat.get_cover.cover_path" alt="Album Pic">
                        <div class="w-full px-5 pt-2 flex flex-col items-center">
                            <div class="w-full text-2xl" v-if="files">{{files[currentPlaying-1].is_beat.title}} - {{files[currentPlaying-1].is_beat.from_user.username}}</div>
                            <div v-if="files" class="flex items-center w-full text-base">
                                <span>{{audio.curLength.min}}:<span v-if="audio.curLength.sec <= 9">0</span>{{audio.curLength.sec}}</span>&nbsp;/&nbsp;
                                <span>{{audio.length.min}}:<span v-if="audio.length.sec <= 9">0</span>{{audio.length.sec}}</span>
                            </div>
                        </div>
                        <div class="volumeControls flex flex-row items-center">
                            <div style="width: 24px" class="m-2" @click="mute">
                            <svg id="Ebene_1" class="main_icon_volume" data-name="Ebene 1" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 97.121 66.698"><defs></defs>
                                <path class="cls-1" d="M50.174,21.514V78.486a4.855,4.855,0,0,1-8.044,3.66L21.374,64.07H8.958a7.4,7.4,0,0,1-7.4-7.4V43.33a7.4,7.4,0,0,1,7.4-7.4H21.374L42.13,17.854A4.855,4.855,0,0,1,50.174,21.514Z" transform="translate(-1.558 -16.651)"/>    
                                <path v-if="volume == 0" class="cls-1" d="M86.126,57.322a3.4,3.4,0,1,1-4.808,4.812L73.994,54.81,66.67,62.134a3.4,3.4,0,0,1-4.808-4.812L69.186,50l-7.324-7.324a3.4,3.4,0,0,1,4.808-4.808l7.324,7.324,7.324-7.324a3.4,3.4,0,1,1,4.808,4.808L78.8,50Z" transform="translate(-1.558 -16.651)"/>
                                <path v-if="volume > 0" class="cls-1" d="M72.615,50a12.687,12.687,0,0,1-7.136,11.46,3.2,3.2,0,0,1-4.4-1.732,3.246,3.246,0,0,1,1.6-4.028,6.369,6.369,0,0,0,0-11.4,3.246,3.246,0,0,1-1.6-4.028,3.2,3.2,0,0,1,4.4-1.728A12.676,12.676,0,0,1,72.615,50Z" transform="translate(-1.558 -16.651)" />
                                <path v-if="volume > 0.3" class="cls-1" d="M86.3,50a29.29,29.29,0,0,1-2.908,13.02,22.28,22.28,0,0,1-8.064,9.092,3.2,3.2,0,0,1-4.536-1.228,3.3,3.3,0,0,1,1.24-4.26C76.887,63.521,79.9,57.161,79.9,50s-3.008-13.52-7.868-16.628a3.293,3.293,0,0,1-1.24-4.256,3.2,3.2,0,0,1,4.536-1.232,22.291,22.291,0,0,1,8.064,9.1A29.277,29.277,0,0,1,86.3,50Z" transform="translate(-1.558 -16.651)" />
                                <path v-if="volume > 0.7" class="cls-1" d="M98.679,50c0,14.008-6.552,26.592-17.1,32.844a3.2,3.2,0,0,1-4.708-1.856,3.241,3.241,0,0,1,1.52-3.692C86.959,72.173,92.279,61.725,92.279,50s-5.32-22.176-13.888-27.3a3.243,3.243,0,0,1-1.52-3.692,3.2,3.2,0,0,1,4.708-1.86C92.127,23.405,98.679,35.993,98.679,50Z" transform="translate(-1.558 -16.651)" />
                            </svg>
                            </div>
                            <div class="main-volume-bar-player" @click="volSlider" @drag="volSlider" ref="volSliderRef" @dragend="volSlider">
                                <div class="main-volume-bar-in" :style="{'width': (volume*100)+'%'}"></div>
                            </div>
                        </div>
                        <div class="p-5">
                            <button class="popup-cta pay" @click="paymentEmit">
								{{files[currentPlaying-1].is_beat.dollarPrice}}$
							</button>
                            
                        </div>
                        <div class="p-5" @click="play()" >
                            <svg v-if="playing" class="w-10 h-10" fill="black" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20"><path d="M5 4h3v12H5V4zm7 0h3v12h-3V4z"/></svg>
                            <svg v-else class="w-10 h-10" fill="black" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20"><path d="M 5 4 l 10 6 l -10 6 z z"/></svg>
                        </div>
                    </div>
                </div>
            </div>
            <div v-if="currentPlaying" class="fixed right-0 flex items-center transition-all" 
            :class="[!showGlobal ? 'main-global-ind-cl' : 'main-global-ind-op']">
                <div class="main-global-p pointer" @click="showGlobal = !showGlobal">
                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 384 512" ><!--! Font Awesome Pro 6.0.0 by @fontawesome - https://fontawesome.com License - https://fontawesome.com/license (Commercial License) Copyright 2022 Fonticons, Inc. -->
                        <path v-if="showGlobal" d="M192 384c-8.188 0-16.38-3.125-22.62-9.375l-160-160c-12.5-12.5-12.5-32.75 0-45.25s32.75-12.5 45.25 0L192 306.8l137.4-137.4c12.5-12.5 32.75-12.5 45.25 0s12.5 32.75 0 45.25l-160 160C208.4 380.9 200.2 384 192 384z"/>
                        <path v-if="!showGlobal" d="M352 352c-8.188 0-16.38-3.125-22.62-9.375L192 205.3l-137.4 137.4c-12.5 12.5-32.75 12.5-45.25 0s-12.5-32.75 0-45.25l160-160c12.5-12.5 32.75-12.5 45.25 0l160 160c12.5 12.5 12.5 32.75 0 45.25C368.4 348.9 360.2 352 352 352z"/>
                    </svg>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
import Player from '@/Components/Player.vue';
import Slider from '@vueform/slider'
import Slider2 from "vue3-slider"
import axios from 'axios';
import { loadBlockchainData, getBeatCount, getBeat } from '../services/blockchain.js'


export default {
    components: {
        Player,
        Slider,
        Slider2,
    },

    props: { 
        attr: Object,
        paths: Array,
        logedIn: Number,
        givenSearchTerm: {type: String, default: ''},
        sum: {type: Number, default: 0},
    },

    data() {
        return {
            files: null,
            loadedBeats: false,
            rendered: false,
            playing: false,
            currentPlaying: null,
            currentPlayingOld: null,
            wantTime: null,
            audio: {
                length: {min:0,sec:0, sum:1},
                curLength: {min:0,sec:0, sum:0},
                curLengthOld: {min:0,sec:0, sum:0}
            },
            value: 20,
            paymentTrack: null,
            info: null,
            loading: true,
            searchEmpty: false,
            searchFocus: false,
            loadEmpty: false,
            backgroundOp: 0,
            searchTerm : this.givenSearchTerm,
            volume: 0.5,
            showGlobal: true,
            cache: null,
			myvol: this.volume,
			volold: null,
            dollarPrice: 0,
        }
    },
    methods: {
        fetchVideoAndPlay(url) {
            var curr = this
            fetch(url)
            .then(response => response.blob())
            .then(blob => {
                curr.$refs.player.src = URL.createObjectURL(blob);
                if(curr.wantTime){
                    curr.cache = this.wantTime*this.audio.length.sum;
                    curr.wantTime = null;
                } else if(curr.$page.props.tracks[curr.currentPlaying]){
                    curr.cache = this.$page.props.tracks[this.currentPlaying];
                }
                return curr.$refs.player.load();
            })
            .then(_ => {
                if(curr.cache){
                    curr.updateCurTime(curr.cache)
                    curr.cache = null;
                }                
                return curr.$refs.player.play();
            })
            .catch(e => {                
                console.error(e)
            })
        },
		mainSlider(e){
			let min = 0, cur = e.clientX - this.$refs.mainSliderRef.offsetLeft, max = this.$refs.mainSliderRef.clientWidth, perc = cur/max
			if(perc > 1){perc = 1}
			if(perc < 0){perc = 0}
			this.emitter.emit('slide', {id: this.currentPlaying, timeP: perc})
		},
		volSlider(e){
			let min = 0, cur = e.clientX - this.$refs.volSliderRef.offsetLeft, max = this.$refs.volSliderRef.clientWidth, perc = cur/max			
            this.volChange(perc)
		},
        
		mute(){
			if(!this.volold && this.volold != 0){
				
				this.volold = this.volume;
                this.volChange(0)
			}else{
				if(this.volold == 0){
                this.volChange(0.5)
				}else{
                this.volChange(this.volold)
				}
				
				this.volold = null;
			}
		},
        getTracks(term = this.searchTerm){
            let profile = {}
            let id = '';
            if(this.attr.loc == 'prof'){
                profile = {
                    act: true,
                    id: this.attr.id,
                    transact: false
                }
            }
            if(this.attr.loc == 'prof-bought'){
                profile = {
                    act: true,
                    id: this.attr.id,
                    transact: true
                }
            }
            if(this.attr.id){
                id = this.attr.id;
            }
            this.searchEmpty = false;
            this.loadEmpty = false;
            // console.info('start');
            if(term){
                window.scrollTo({
                    top: 840,
                    behaviour: "smooth",
                })
            }
            axios.get('/api/beats', { params: { keywords: term, profile, id} })
            .then(response => {
                if(!response.data.length){
                    // console.info('empty')
                    if(term){
                        this.searchEmpty = true;
                        this.loading = false;
                        this.searchTerm = term
                    }else{
                        this.loadEmpty = true;
                        this.loading = false;
                    }

                    
                }else{
                    if(this.sum){
                        this.files = response.data.slice(0,this.sum)
                    }else{
                        this.files = response.data
                    }
                    this.loadedBeats = true;
                    this.loading = false;
                    
                    this.convert()
                }
            })
        },
        play(numb){
            if(numb){
                if(this.currentPlaying === null){
                    // 
                    this.currentPlaying = numb;
                }
                else if(this.currentPlaying == numb || !this.playing){
                    this.currentPlaying = numb;
                    this.playing = !this.playing;
                    this.toggleAudio();
                    // 
                }
                this.currentPlaying = numb;
                this.getLength()
            }else{
                this.playing = !this.playing;
                this.toggleAudio();
                this.getLength()
            }
                    
        },

        toggleAudio(slide){         
            var player = this.$refs.player;
            try {
                if (player.paused) {
                    this.playProm(); 
                }
                else {
                    if(slide == 'slide'){
                        this.playProm(); 
                    }else{
                        this.pauseProm();
                    }
                }
            } catch (err) {
                console.error(err);
                console.error('r182 - sooooowy')
            }
        },
        playProm(){
            var prom = this.$refs.player.play();
            if(prom !== undefined){
                prom.then(x => {
                    this.playing = true;
                })
                .catch(err => {
                    console.error(err)
                })
            }
        },
        pauseProm(){
            var prom = this.$refs.player.pause();
            if(prom !== undefined){
                prom.then(x => {
                    this.playing = false;
                })
                .catch(err => {
                    console.error(err)
                })
            }
        },

        getLength(){
            if (this.files  && this.$refs.player) {
            var sis = this;
            this.$refs.player.onloadedmetadata = function() {
                var min = Math.trunc(this.duration/60)
                var sec = (Math.trunc(this.duration) - min*60 ).toFixed(0)
                // 
                sis.audio.length.sum = this.duration
                sis.audio.length.min = min
                sis.audio.length.sec = sec
                
            }}
        },

        getCurrentTime(){
            if (this.files && this.loadedBeats && this.$refs.player) {
                if(this.$refs.player.currentTime >= this.audio.length.sum){
                    this.audio.curLength.sum = 0;
                    this.playing = false;
                }else{
                    var min = Math.trunc(this.$refs.player.currentTime/60)
                    var sec = (Math.trunc(this.$refs.player.currentTime) - min*60 ).toFixed(0)
                    this.audio.curLength.sum = this.$refs.player.currentTime
                    this.audio.curLength.min = min
                    this.audio.curLength.sec = selectedAddress
                }  
            }
        },
        updateCurTime(v){
            var aud = this.$refs.player;
            aud.currentTime = v;
            this.playProm()
        },
        changeCurTime(value){
            this.updateCurTime(this.$refs.player.currentTime + value)
        },
        volChange(x){
            this.volume = x;
            this.$refs.player.volume = Math.pow(x,1.02);
        },

        convert() {
            axios.get('https://api.coinbase.com/v2/exchange-rates')
            .then(res => {
                this.files.forEach(element => {
                element.is_beat.dollarPrice = (Math.round(((element.is_beat.price / res.data.data.rates.ETH) + Number.EPSILON) * 100) / 100).toFixed(2)
                });
            })
        },

        paymentEmit() {
			if(this.$page.props.auth.user) {
				this.emitter.emit("openPopupPayment", this.files[this.currentPlaying-1])
			} else {
				this.emitter.emit('error', 'Login to Purchase a track')
			}
		},

        blockchainTracks() {
            if (typeof window.ethereum !== 'undefined') {
            loadBlockchainData()
                .then(response => {
                    this.$store.state.contract = response
                    
                    getBeatCount(this.$store.state.contract)
                        .then(response => {
                            for (var i = 1; i <= response; i++) {
                                getBeat(this.$store.state.contract, i)
                                    .then(response => {
                                        this.$store.state.beats.push(response)
                                        
                                    })
                            }
                            
                        })
                })
            }else{
                this.emitter.emit('error', 'MetaMask not detected')
            }
        },

        searchBack(){
            this.searchTerm = ''
            this.loading = true
            this.files = null
            this.getTracks()
            this.emitter.emit('nav-change', '')
        }

    },
    mounted() {
        
        
          
        if(this.givenSearchTerm != ''){
            
            window.scrollTo({
                top: 840,
                behaviour: "smooth",
            })
        }
        this.getTracks(this.searchTerm);

        

        this.getCurrentTime();
        setInterval(this.getCurrentTime, 10);
        this.getLength();
        this.emitter.on("test-emit", text => {
            this.playing = !this.playing;
        });
        this.emitter.on("show-info", num => {
            if(this.info == num){
                this.info = null;
            }else{
                this.info = num;
            }
        });
        this.emitter.on("play-pause", numb => {
            
            this.play(numb);
            // 
        });

        this.emitter.on('upload-success',() =>{
            this.getTracks();
            this.blockchainTracks()
            
        });

        this.emitter.on("search", term => {
            this.files = null
            this.loading = true;
            this.searchTerm = term
            this.getTracks(term);
            // 
        });
        this.emitter.on("focus", bool =>{
            this.searchFocus = bool;
        })

        this.$watch('currentPlaying', () => {
                this.getLength()
                
                if(this.currentPlaying){
                    if(this.currentPlayingOld){
                        this.$page.props.tracks[this.currentPlayingOld] = this.audio.curLength.sum                        
                    }
                    this.fetchVideoAndPlay(this.files[this.currentPlaying-1].file_path);                    
                    this.playing = true;
                    this.toggleAudio();
                    this.currentPlayingOld = this.currentPlaying;
                    // 
                    
                }

            });

        this.emitter.on('slide', data => {
            
            
            // this.playing = true;
            this.toggleAudio('slide')
            if(this.currentPlaying == data.id){
                this.updateCurTime(this.audio.length.sum*data.timeP)
            }else{
                this.wantTime = data.timeP;
                // this.$page.props.tracks[currentPlaying] = data.timeP
                this.currentPlaying = data.id

            }
        })

        
        window.addEventListener('keydown', (e) => {
            //  
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
        

        
    },

    created() {
        if (typeof window.ethereum !== 'undefined') {
            if(this.$page.props.auth.user && ethereum.selectedAddress) {
                this.blockchainTracks()
            }
        }
    }
};
</script>

<style src="@vueform/slider/themes/default.css"></style>

<style scoped>
</style>