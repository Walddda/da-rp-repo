<template>
    <div>
        <Head title="Test" />
        <div>
            <div v-if="files">
                <div class="container mb-96 mt-10 flex mx-auto w-full items-center justify-center">
                    <ul class="flex flex-col p-4">
                        <!-- <li 
                            v-for="x in files" 
                            class="border-gray-400 flex flex-row my-5">
                            <div class="select-none flex flex-1 items-center p-4 transition duration-500 ease-in-out transform hover:-translate-y-2 rounded-2xl border-2 p-6 hover:shadow-2xl border-red-400">
                                <div class="flex-1 pl-1">
                                        <player :track="x"/>
                                </div>
                            </div>
                        </li> -->

                        <li v-for="(x, k) in files" >
                            <player v-if="k == currentPlaying" :track="x" :numb="k" :rn="playing" current/>
                            <player v-else :track="x" :numb="k" />
                        </li>
                    </ul>
                </div>

                
                
            </div>
            <div class="border-t-2 border-black pt-7 pb-7 fixed w-full bottom-0 flex flex-col items-center bg-white">
                <audio style="display:none" ref="player"  preload="metadata" >
                        <source :src="files[currentPlaying].file_path" type="audio/mp3" v-if="loadedBeats" />
                </audio>
                <div class="w-5/12 flex flex-row items-center"> 
                    <img  v-if="files" class="box-border h-28 w-28 border-2 border-black" :src="files[currentPlaying].is_beat.get_cover.cover_path" alt="Album Pic">
                    <!-- <img class="transform scale-75" :src="'/storage/covers/'+files[currentPlaying].is_beat.get_cover.name" alt="Album Pic"> -->
                    <div class="w-10/12 px-5 flex flex-col items-center">
                        <div class="w-full" v-if="files">{{files[currentPlaying].is_beat.title}} - {{files[currentPlaying].is_beat.from_user.username}}</div>
                        <slider2 v-if="files" class="text-center" trackColor="#020203" v-model="audio.curLength.sum" :max="audio.length.sum" @dragging="updateCurTime" @click="testClick"/>
                        <div v-if="files" class="flex justify-between items-center w-full">
                            <span>{{audio.curLength.min}}:<span v-if="audio.curLength.sec <= 9">0</span>{{audio.curLength.sec}}</span>
                            <span>{{audio.length.min}}:<span v-if="audio.length.sec <= 9">0</span>{{audio.length.sec}}</span>
                        </div>
                    </div>
                </div>

                <div class="flex justify-around items-center mt-8 w-full">
                    <div class="text-grey-darker">
                        <svg class="w-8 h-8" fill="currentColor" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20"><path d="M6.59 12.83L4.4 15c-.58.58-1.59 1-2.4 1H0v-2h2c.29 0 .8-.2 1-.41l2.17-2.18 1.42 1.42zM16 4V1l4 4-4 4V6h-2c-.29 0-.8.2-1 .41l-2.17 2.18L9.4 7.17 11.6 5c.58-.58 1.59-1 2.41-1h2zm0 10v-3l4 4-4 4v-3h-2c-.82 0-1.83-.42-2.41-1l-8.6-8.59C2.8 6.21 2.3 6 2 6H0V4h2c.82 0 1.83.42 2.41 1l8.6 8.59c.2.2.7.41.99.41h2z"/></svg>
                    </div>
                    <div class="text-grey-darker">
                        <svg class="w-8 h-8" fill="currentColor" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20"><path d="M4 5h3v10H4V5zm12 0v10l-9-5 9-5z"/></svg>
                    </div>
                    <div class="text-white p-8 rounded-full bg-red-light shadow-lg" @click="play" >
                        <svg v-if="playing" class="w-8 h-8" fill="black" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20"><path d="M5 4h3v12H5V4zm7 0h3v12h-3V4z"/></svg>
                        <svg v-else class="w-8 h-8" fill="black" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20"><path d="M 5 4 l 10 6 l -10 6 z z"/></svg>
                    </div>
                    <div class="text-grey-darker">
                        <svg class="w-8 h-8" fill="currentColor" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20"><path d="M13 5h3v10h-3V5zM4 5l9 5-9 5V5z"/></svg>
                    </div>
                    <div class="text-grey-darker">
                        <svg class="w-8 h-8" fill="currentColor" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20"><path d="M5 4a2 2 0 0 0-2 2v6H0l4 4 4-4H5V6h7l2-2H5zm10 4h-3l4-4 4 4h-3v6a2 2 0 0 1-2 2H6l2-2h7V8z"/></svg>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
import BreezeAuthenticatedLayout from "@/Layouts/Authenticated.vue";
import { Head } from "@inertiajs/inertia-vue3";
import Player from '@/Components/Player.vue';
  import Slider from '@vueform/slider'
  import Slider2 from "vue3-slider"
import axios from 'axios';
//   import VueSlider from 'vue-slider-component'
// import VueSlider from 'vue-slider-component/dist-css/vue-slider-component.umd.min.js'
// import 'vue-slider-component/dist-css/vue-slider-component.css'

export default {
    components: {
        BreezeAuthenticatedLayout,
        Head,
        Player,
        Slider,
        Slider2
    },

    props: { 
        // files: Array,
        paths: Array,
    },

    data() {
        return {
            files: null,
            loadedBeats: false,
            rendered: false,
            playing: false,
            currentPlaying: 0,
            tag: '',
            tags: [],
            audio: {
                length: {min:0,sec:0, sum:1},
                curLength: {min:0,sec:0, sum:0},
                curLengthOld: {min:0,sec:0, sum:0}
            },
            value: 20,
        };
    },
    created() {
    },
    methods: {
        getTracks(){
            console.log('start');
            axios.get('/api/beats')
            .then(response => {
                this.files = response.data
                console.log('finish: ');
                console.log(response.data);
                this.loadedBeats = true;
                console.log(this.$refs.player)
            })
        },
        play(){
            this.playing = !this.playing;
            this.toggleAudio();
            this.getLength()
        },

        toggleAudio(){
            var audio = this.$refs.player;
            if (audio.paused) {audio.play()}
            else {audio.pause()}
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
            console.log($event)
            console.log($event.layerX);
            console.log($event.srcElement.offsetParent.clientWidth);
            console.log(this.audio.length.sum);
            var disc = $event.srcElement.offsetParent.clientWidth/this.audio.length.sum;
            // console.log('disc '+disc)
            var calc = $event.layerX/disc
            // console.log('spould '+calc)
            this.$refs.player.currentTime = calc;
            console.log(this.$refs.player);
            console.log('--------------------------'+calc)
        },
        changeCurTime(value){
            // this.audio.curLength.sum += value;
            this.$refs.player.currentTime += value;
            console.log('yeha')
        }
    },
    mounted() {    
        this.$nextTick(function () {
            this.rendered = true;
        })
        this.getTracks();

        this.getCurrentTime();
        setInterval(this.getCurrentTime, 100);


        this.getLength();
        this.emitter.on("test-emit", text => {
            this.playing = !this.playing;
            // console.log(text);
        });
        this.emitter.on("play-pause", numb => {
            this.getLength()
            if(this.currentPlaying == numb || !this.playing){
                this.playing = !this.playing;
                this.toggleAudio();
            }
            this.currentPlaying = numb;
            // console.log(text);
        });
        this.$watch('currentPlaying', () => {
                this.getLength()
                if(this.$refs.player){
                this.$refs.player.load()}
                console.log('heyy');
                this.playing = true;
                this.toggleAudio();
            });
        this.$watch('loadedBeats', () => {
                if(this.rendered){
                this.$refs.player.load()
                console.log('heyy222');
                this.getLength()}
            })
        this.$watch('rendered', () => {
                if(this.loadedBeats){
                this.$refs.player.load()
                console.log('heyy3332');
                this.getLength()}
            })

        
        window.addEventListener('keydown', (e) => {
            // console.log(e.key +' - '+ e.keyCode);
        if (e.key === 'Spacebar' || e.keyCode === 32) {
            this.play();
        }else if (e.key === 'ArrowLeft' || e.keyCode === 37) {
            this.changeCurTime(-10);
        }else if (e.key === 'ArrowRight' || e.keyCode === 39) {
            this.changeCurTime(10);
        }
        });
    }
};
</script>

<style src="@vueform/slider/themes/default.css"></style>