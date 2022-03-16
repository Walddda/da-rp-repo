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
                                <player v-if="k == currentPlaying-1" :track="x" :numb="k+1" :rn="playing" :info="info" :volume="volume" current :curTime="[cache ? cache : audio.curLength.sum]" @vol="volChange($event)" @color="wavecolor" ref="curTrack"/>
                                <canvas id="canvas" ref="canvas" v-if="k == currentPlaying-1" class="main-wave block h-52 w-52 max-w-none"></canvas>
                                <player v-else :track="x" :numb="k+1" :info="info" :volume="volume" @vol="volChange($event)" />
                            </li>
                        </ul>
                    </div>
                    
                </div>
                <div v-else-if="!files & !loading & !searchEmpty">Be the first one to upload your work at Beatchain.</div>
                <div v-if="searchEmpty">Your search "{{searchTerm}}" had no result.</div>

                <div class="loader ease-linear rounded-full border-4 border-t-4 border-gray-200 h-12 w-12" v-if="loading"></div>
            </div>


            <div v-if="currentPlaying" :style="[showGlobal ? {} : {'bottom': '-100px'}]" class="transition-all fixed bottom-0 flex flex-col w-full">
                <div class="flex flex-col items-center"> 
                    <div class="main-slider-container slider-global w-full" @click="mainSlider" @drag="mainSlider" ref="mainSliderRef" @dragend="mainSlider">
                        <!-- <div class="main-slider-small"></div> -->
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
                        <!-- <img class="transform scale-75" :src="'/storage/covers/'+files[currentPlaying].is_beat.get_cover.name" alt="Album Pic"> -->
                        <div class="w-full px-5 pt-2 flex flex-col items-center">
                            <div class="w-full text-2xl" v-if="files">{{files[currentPlaying-1].is_beat.title}} - {{files[currentPlaying-1].is_beat.from_user.username}}</div>
                            <!-- <slider2 v-if="files" class="text-center" trackColor="#020203" v-model="audio.curLength.sum" :max="audio.length.sum" @dragging="updateCurTime" @click="testClick"/> -->
                            <div v-if="files" class="flex items-center w-full text-base">
                                <span>{{audio.curLength.min}}:<span v-if="audio.curLength.sec <= 9">0</span>{{audio.curLength.sec}}</span>&nbsp;/&nbsp;
                                <span>{{audio.length.min}}:<span v-if="audio.length.sec <= 9">0</span>{{audio.length.sec}}</span>
                            </div>
                        </div>
                        |{{cache}}|
                        Volume
                        <div class="p-5" @click="play" >
                            <svg v-if="playing" class="w-8 h-8" fill="black" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20"><path d="M5 4h3v12H5V4zm7 0h3v12h-3V4z"/></svg>
                            <svg v-else class="w-10 h-10" fill="black" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20"><path d="M 5 4 l 10 6 l -10 6 z z"/></svg>
                        </div>
                    </div>
                </div>
            </div>
            <!-- volume
            player pixel weiter runter
            pfeil rutscht zwischen end und mitte
             -->
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
import PlayerWave from '@/Components/PlayerWave.vue';
import Slider from '@vueform/slider'
import Slider2 from "vue3-slider"
import axios from 'axios';
import FastAverageColor from 'fast-average-color';


export default {
    components: {
        Player,
        Slider,
        Slider2,
        PlayerWave,
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
            currentPlaying: 0,
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
            rgb: {r: 0, g:0, b:0},
            volume: 0.5,
            showGlobal: true,
            cache: null,
        }
    },
    methods: {
        wavecolor(rgb){
            this.rgb = rgb
        },
        wave(first){
            var audio = this.$refs.player;
            // var files = this.files;
            // audio.src = URL.createObjectURL(files[0]);
            console.log(audio.src)
            if(first){audio.load();}
            audio.play();
            var context = new AudioContext();
            var src = context.createMediaElementSource(audio);
            console.log(src)
            var analyser = context.createAnalyser();
        
            // this.$refs.curTrack.averageColor();
            console.log(this.$refs.canvas)
            var canvas = this.$refs.canvas;
            canvas.width = window.innerWidth;
            canvas.height = window.innerHeight;
            var ctx = canvas.getContext("2d");

            src.connect(analyser);
            analyser.connect(context.destination);

            analyser.fftSize = 256;
            console.log(analyser)

            var bufferLength = analyser.frequencyBinCount;
            console.log(bufferLength);

            var dataArray = new Uint8Array(bufferLength);
            console.log(dataArray)

            var WIDTH = canvas.width;
            var HEIGHT = canvas.height;

            var barWidth = (WIDTH / bufferLength) * 2.5;
            console.log(barWidth)
            var barHeight;
            var x = 0;

            function renderFrame() {
            requestAnimationFrame(renderFrame);

            x = 0;

            analyser.getByteFrequencyData(dataArray);
            //console.log(dataArray)

            // ctx.fillStyle = "#000";
            // ctx.fillRect(0, 0, WIDTH, HEIGHT);
            ctx.clearRect(0,0,WIDTH,HEIGHT);

            for (var i = 0; i < bufferLength; i++) {
                barHeight = dataArray[i];
                //console.log(i)
                //console.log(barHeight)
                
                // var r = barHeight + (25 * (i/bufferLength));
                // var g = 250 * (i/bufferLength);
                // var b = 50;

                ctx.fillStyle = "rgb(" + this.rgb.r + "," + this.rgb.g + "," + this.rgb.b + ")";
                ctx.fillRect(x, HEIGHT - barHeight, barWidth, barHeight);

                x += barWidth + 1;
                }
            }

            // audio.play();
            this.playProm()
            renderFrame();
        },
        fetchVideoAndPlay(url) {
            var curr = this
            console.info('fetch: '+url)
            fetch(url)
            .then(response => response.blob())
            .then(blob => {
                console.log('--->')
                console.log(blob)
                console.log(URL.createObjectURL(blob))
                // video.srcObject = blob;
                // curr.$refs.player.srcObject = blob;
                curr.$refs.player.src = URL.createObjectURL(blob);
                if(curr.wantTime){
                    curr.cache = this.wantTime*this.audio.length.sum;
                    // this.$refs.player.currentTime = this.wantTime*this.audio.length.sum;
                    // curr.$refs.player.currentTime = (curr.wantTime*curr.audio.length.sum)
                    curr.wantTime = null;
                } else if(curr.$page.props.tracks[curr.currentPlaying]){
                    curr.cache = this.$page.props.tracks[this.currentPlaying];
                    // this.$refs.player.currentTime = this.$page.props.tracks[this.currentPlaying];
                    // curr.$refs.player.currentTime = (curr.$page.props.tracks[curr.currentPlaying])
                }
                return curr.$refs.player.load();
            })
            .then(_ => {
                if(curr.cache){
                    curr.updateCurTime(curr.cache)
                    console.info(curr.cache)
                    curr.cache = null;
                }
                // Video playback started ;)
                console.log('leeeego')
                return curr.$refs.player.play();
            })
            .then(_ => {
                // // Video playback started ;)
                // if(curr.wantTime){
                //     // this.$refs.player.currentTime = this.wantTime*this.audio.length.sum;
                //     curr.updateCurTime(curr.wantTime*curr.audio.length.sum)
                //     curr.wantTime = null;
                // }
                // if(curr.$page.props.tracks[curr.currentPlaying]){
                //     // this.$refs.player.currentTime = this.$page.props.tracks[this.currentPlaying];
                //     curr.updateCurTime(curr.$page.props.tracks[curr.currentPlaying])
                // }
                console.log('dgufdakzgausdzkfdazsugfu')
            })
            .catch(e => {
                // Video playback failed ;(
                console.log('no')
                console.error(e)
            })
        },
		mainSlider(e){
			let min = 0, cur = e.clientX - this.$refs.mainSliderRef.offsetLeft, max = this.$refs.mainSliderRef.clientWidth, perc = cur/max
			// console.log('----------------------------------')
			// console.log(e)
			// console.log(this.$refs.mainSliderRef)
			// console.log(min + ' - '+ cur +' - '+ max)
			// console.log(cur/max)
			if(perc > 1){perc = 1}
			if(perc < 0){perc = 0}
			this.emitter.emit('slide', {id: this.currentPlaying, timeP: perc})
		},
        getTracks(term = this.searchTerm){
            //for profile site, to get only tracks from one profile 
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
                    if(this.sum){
                        this.files = response.data.slice(0,this.sum)
                    }else{
                        this.files = response.data
                    }
                    // this.currentPlaying = 1
                    // this.emitter.emit("openPopupPayment", this.files[1]);
                    // this.currentPlaying = 1;
                    // this.currentPlaying = 1; //für global player testing
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
            // this.playing = !this.playing;
            // if(this.$refs.player)
            this.toggleAudio();
            this.getLength()
        },

        toggleAudio(slide){         
            var player = this.$refs.player;
            // console.warn(player.getAttribute("src"))
            try {
                if (player.paused) {
                    this.wave(); 
                }
                else {
                    if(slide == 'slide'){
                        this.wave(); 
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
        updateCurTime(v){
            console.log('update: '+v);
            var aud = this.$refs.player;
            // this.$refs.player.currentTime = $event;
            // this.getCurrentTime()
            aud.currentTime = v;
            // aud.oncanplay = function() {
            //     aud.currentTime = v;
            //     // console.log('updateeeeed: '+v)
            // }
            this.playProm()
        },
        changeCurTime(value){
            // this.audio.curLength.sum += value;
            // this.$refs.player.currentTime += value;
            this.updateCurTime(this.$refs.player.currentTime + value)
            // console.log('r239')
        },
        volChange(x){
            console.log("new vol:"+x + ' -> '+Math.pow(x,1.02));
            this.volume = x;
            this.$refs.player.volume = Math.pow(x,1.02);
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
                // this.wave();
                // console.log('r273')
            }
            this.currentPlaying = numb;
            this.getLength()
            // console.log(text);
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
                    // this.$refs.player.src = this.files[this.currentPlaying-1].file_path;
                    // this.$refs.player.load()
                    this.fetchVideoAndPlay(this.files[this.currentPlaying-1].file_path);

                    // if(this.$page.props.tracks[this.currentPlaying]){
                    //     // this.$refs.player.currentTime = this.$page.props.tracks[this.currentPlaying];
                    //     this.updateCurTime(this.$page.props.tracks[this.currentPlaying])
                    // }
                    // if(this.wantTime){
                    //     // this.$refs.player.currentTime = this.wantTime*this.audio.length.sum;
                    //     this.updateCurTime(this.wantTime*this.audio.length.sum)
                    //     this.wantTime = null;
                    // }

                    console.log('r305');
                    this.playing = true;
                    this.toggleAudio();
                    this.currentPlayingOld = this.currentPlaying;
                    // console.log('r308')
                    // this.$refs.curTrack.averageColor();
                }

            });

        this.emitter.on('slide', data => {
            console.log(data)
            console.log(this.currentPlaying)
            // this.playing = true;
            this.toggleAudio('slide')
            if(this.currentPlaying == data.id){
                console.log(this.audio.length.sum+'*'+data.timeP+'/'+100+' -> ' + (this.audio.length.sum*data.timeP))
                // this.$refs.player.currentTime = (this.audio.length.sum*data.timeP)
                this.updateCurTime(this.audio.length.sum*data.timeP)
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
                        this.playProm();
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