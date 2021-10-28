<template>
    <div>
        <Head title="Test" />
        <div>
            <div v-if="files.length > 0">
                <div class="container mb-2 flex mx-auto w-full items-center justify-center">
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
            <hr>
            {{files[currentPlaying].is_beat.title}} - {{files[currentPlaying].is_beat.from_user.username}}
            <br>
            /storage/uploads/{{files[currentPlaying].name}}
            
            <audio style="display:none" ref="player">
                    <source :src="'/storage/uploads/'+files[currentPlaying].name" type="audio/mp3" />
            </audio>

            <div class="flex justify-around items-center mt-8">
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
</template>

<script>
import BreezeAuthenticatedLayout from "@/Layouts/Authenticated.vue";
import { Head } from "@inertiajs/inertia-vue3";
import Player from '@/Components/Player.vue';

export default {
    components: {
        BreezeAuthenticatedLayout,
        Head,
        Player,
    },

    props: { 
        files: Array,
        paths: Array,
     },

    data() {
        return {
            loadedBeats: false,
            playing: false,
            currentPlaying: 0,
        };
    },
    methods: {
        play(){
            this.playing = !this.playing;
            this.toggleAudio();
        },

        toggleAudio(){
            var audio = this.$refs.player;
            if (audio.paused) {audio.play()}
            else {audio.pause()}
        }
    },
    mounted() { 
        this.emitter.on("test-emit", text => {
            this.playing = !this.playing;
            // console.log(text);
        });
        this.emitter.on("play-pause", numb => {
            if(this.currentPlaying == numb || !this.playing){
                this.playing = !this.playing;
                this.toggleAudio();
            }
            this.currentPlaying = numb;
            // console.log(text);
        });
        this.$watch('currentPlaying', () => {
                this.$refs.player.load()
                console.log('heyy');
            })
    }
};
</script>
