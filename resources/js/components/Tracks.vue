<style lang="scss">
    // @import './app.scss';
</style>
<template>
    <div>
        
        <audio v-if="files" style="display:none" ref="player"  preload="metadata" >
            <source type="audio/mp3"/>
        </audio>
        <div ref="feedDiv">
            <div class="main-feed-div flex items-center justify-center">
                <div v-if="files">
                    <div class="container flex mx-auto w-full items-center justify-center">
                        <ul class="flex flex-col p-4 grid justify-items-center">

                            <li v-for="(x, k) in files">
                                <player v-if="k == currentPlaying-1" :track="x" :numb="k+1" :rn="playing" :info="info" :volume="audio.volume" current :curTime="audio.curLength.sum" v-on:volume-change="volChange($event)"/>
                                <player v-else :track="x" :numb="k+1" :info="info" />
                            </li>
                        </ul>
                    </div>
                    
                </div>
                <div v-else-if="!files & !loading & !searchEmpty">Be the first one to upload your work at Beatchain.</div>
                <div v-if="searchEmpty">Your search "{{searchTerm}}" had no result.</div>

                <div class="loader ease-linear rounded-full border-4 border-t-4 border-gray-200 h-12 w-12" v-if="loading"></div>
            </div>


            <div v-if="currentPlaying" class="border-t-2 border-black pt-3 pb-3 fixed w-full bottom-0 flex flex-col items-center bg-white">
                <div class="w-5/12 flex flex-row items-center"> 
                    <img class="box-border h-28 w-28 border-2 border-black" loading="lazy" :src="files[currentPlaying-1].is_beat.get_cover.cover_path" alt="Album Pic">
                    <!-- <img class="transform scale-75" :src="'/storage/covers/'+files[currentPlaying].is_beat.get_cover.name" alt="Album Pic"> -->
                    <div class="w-10/12 px-5 flex flex-col items-center">
                        <div class="w-full" v-if="files">{{files[currentPlaying-1].is_beat.title}} - {{files[currentPlaying-1].is_beat.from_user.username}}</div>
                        <slider2 v-if="files" class="text-center" trackColor="#020203" v-model="audio.curLength.sum" :max="audio.length.sum" @dragging="updateCurTime" @click="testClick"/>
                        <div v-if="files" class="flex justify-between items-center w-full">
                            <span>{{audio.curLength.min}}:<span v-if="audio.curLength.sec <= 9">0</span>{{audio.curLength.sec}}</span>
                            <span>{{audio.length.min}}:<span v-if="audio.length.sec <= 9">0</span>{{audio.length.sec}}</span>
                        </div>
                    </div>
                    <div class="text-white p-5 rounded-full bg-red-light shadow-lg" @click="play" >
                        <svg v-if="playing" class="w-8 h-8" fill="black" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20"><path d="M5 4h3v12H5V4zm7 0h3v12h-3V4z"/></svg>
                        <svg v-else class="w-8 h-8" fill="black" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20"><path d="M 5 4 l 10 6 l -10 6 z z"/></svg>
                    </div>
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
            backgroundOp: 0,
            searchTerm : this.givenSearchTerm,
        }
    },
    methods: {
        getTracks(term = this.searchTerm){
            let profile = {}
            if(this.attr.loc == 'prof'){
                profile = {
                    act: true,
                    id: this.attr.id,
                }
            }
            this.searchEmpty = false;
            console.info('start');
            if(term){
                window.scrollTo({
                    top: 840,
                    behaviour: "smooth",
                })
            }
            axios.get('/api/beats', { params: { keywords: term, profile} })
            .then(response => {
                if(!response.data.length){
                    console.info('empty')
                    this.searchEmpty = true;
                    this.loading = false;
                    this.searchTerm = term
                }else{
                    this.files = response.data
                    console.info('finish: ');
                    console.info(response.data);
                    this.loadedBeats = true;
                    console.info(this.$refs.player)
                    this.loading = false;
                    console.log(term)
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
        volChange(x){
            console.log("new vol:"+x);
        }

    },
    mounted() {    
        // this.$nextTick(function () {
        //     this.rendered = true;
        // })
        this.getTracks(this.searchTerm);

        this.getCurrentTime();
        setInterval(this.getCurrentTime, 10);
        // setInterval(()=>{this.emitter.emit("reload")}, 20000);


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
            console.log("test")
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
        this.emitter.on("volume-change", newVol => {
            console.log("vol: "+ newVol)
            this.audio.volume = newVol/100;
        });

        this.emitter.on('upload-success',() =>{
            this.getTracks();
            console.log('yeeeah')
        });

        this.emitter.on("search", term => {
            this.files = null
            this.loading = true;
            this.searchTerm = term
            this.getTracks(term);
            // console.log(text);
        });
        this.emitter.on("focus", bool =>{
            this.searchFocus = bool;
        })

        this.$watch('currentPlaying', () => {
                this.getLength()
                console.log('r301')
                if(this.currentPlaying){
                    if(this.currentPlayingOld){
                        console.log(this.audio)
                        this.$page.props.tracks[this.currentPlayingOld] = this.audio.curLength.sum
                        console.log(this.audio.curLength.sum);
                    }
                    // console.warn(this.currentPlaying);
                    this.$refs.player.src = this.files[this.currentPlaying-1].file_path;
                    this.$refs.player.load()

                    if(this.$page.props.tracks[this.currentPlaying]){
                        this.$refs.player.currentTime = this.$page.props.tracks[this.currentPlaying];
                    }
                    if(this.wantTime){
                        this.$refs.player.currentTime = this.wantTime*this.audio.length.sum;
                        this.wantTime = null;
                    }

                    console.log('r305');
                    this.playing = true;
                    this.toggleAudio();
                    this.currentPlayingOld = this.currentPlaying;
                    // console.log('r308')
                }

            });

        this.emitter.on('slide', data => {
            console.log(data)
            console.log(this.currentPlaying)
            this.playing = true;
            if(this.currentPlaying == data.id){
                console.log(this.audio.length.sum+'*'+data.timeP+'/'+100+' -> ' + (this.audio.length.sum*data.timeP))
                this.$refs.player.currentTime = (this.audio.length.sum*data.timeP)
            }else{
                this.wantTime = data.timeP;
                // this.$page.props.tracks[currentPlaying] = data.timeP
                this.currentPlaying = data.id

            }
        })

        
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