<template>
    <div>
        <div class="w-full">
  <div class="h-2 bg-red-light"></div>
	<div class="flex items-center justify-center bg-red-lightest">
		<div class="bg-white shadow-lg rounded-lg" style="width: 45rem !important;">
			<div class="flex">
				<div>
					{{numb}}
					<img class="rounded box-border block h-44 w-44 border-2 border-black max-w-none" @error="defaultCover = true" v-if="!defaultCover" :src="track.is_beat.get_cover.cover_path" ref="cover"/>
					<img class="rounded box-border block h-44 w-44 border-2 border-black max-w-none" v-if="defaultCover" src="/storage/covers/placeholder_1.jpg" ref="cover"/>
				</div>
				<div class="w-full p-8">
					<div class="flex justify-between">
						<div>
							<h3 class="text-2xl text-grey-darkest font-medium">{{track.is_beat.title}}</h3>
							<p class="text-sm text-grey mt-1">{{track.is_beat.from_user.username}}</p>

						</div>
						<div class="text-red-lighter">
							<svg class="w-6 h-6" @click="likeUnlike" :fill="likedColor" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20"><path d="M10 3.22l-.61-.6a5.5 5.5 0 0 0-7.78 7.77L10 18.78l8.39-8.4a5.5 5.5 0 0 0-7.78-7.77l-.61.61z"/></svg>
							{{likeCount}}
						</div>
					</div>
					<div class="flex justify-between items-center mt-8">
						<div class="text-white p-8 rounded-full bg-red-light shadow-lg" @click="testEmit" >
							<svg v-if="rn" class="w-8 h-8" fill="black" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20"><path d="M5 4h3v12H5V4zm7 0h3v12h-3V4z"/></svg>
							<svg v-else class="w-8 h-8" fill="black" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20"><path d="M 5 4 l 10 6 l -10 6 z z"/></svg>
						</div>

						<div class="p-8 rounded-full bg-red-light shadow-lg" @click="infoEmit" >
							INFO
						</div>
					</div>
					<div v-if="info == numb">
						<span v-if="track.is_beat.bpm">BPM: {{track.is_beat.bpm}}</span><br>
						<span v-if="track.is_beat.key">Key: {{track.is_beat.key}}</span><br>
						<span v-if="track.is_beat.description">{{track.is_beat.description}}</span><br>
						Tags: 
						<span v-for="(ax,ak) in track.is_beat">
							<span v-if="ak.substring(0,3) == 'tag' && ax &&ak != 'tag1'">, </span>
							<span  v-if="ak.substring(0,3) == 'tag' && ax">
								{{ax}}</span>
						</span><br>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>
    </div>
</template>



<script>    
import axios from 'axios';
export default {
    name: 'Player',
    
    props: {
        track: Object,
		current: Boolean,
		numb: Number,
		rn: {Boolean, default: false},
		info: {Boolean, default: false},
    },
	data() {
		return{
			hover: false,
			defaultCover: false,
			likedColor: 'black',
			likeCount: this.track.is_beat.likes2.length,
			showInfo: false,
		}
	},
	created() {
	},
	mounted(){
		this.liked()
	},
	methods: {
		testEmit() {
			// console.log('yaay');
			this.emitter.emit("play-pause", this.numb);
		},
		infoEmit() {
			this.emitter.emit("show-info", this.numb);
		},
		liked(){
			this.track.is_beat.likes2.forEach(e => {
				if(e.id == this.$page.props.auth.user.id){
					this.likedColor = 'red'
				}
			});
		},
		likeUnlike(){
            axios.get('/api/beat/like/'+this.track.beat_id+'/'+this.$page.props.auth.user.id)
            .then(response => {
                console.info('liked: ');
                console.info(response.data);
				this.likeCount= response.data.count
				if(response.data.cov != 'old'){
					// this.emitter.emit("cover-update", response.data.cov);
					// console.warn(this.$refs)
					// console.error("storage/covers/placeholder_"+response.data.cov+".jpg");
					this.$refs.cover.src = "storage/covers/placeholder_"+response.data.cov+".jpg"
					// console.info(this.$refs.cover)
				}
				// this.refreshLikeCount()
				if(!response.data.del){
					this.likedColor = 'red'
				}else{
					this.likedColor = 'black'
					}
            })
		},
		refreshLikeCount(){
			axios.get('/api/likeCount/'+this.track.beat_id)
			.then(response =>{
				console.info(response)
				console.info(response.data[0].is_beat.likes2.length)
				this.likeCount= response.data[0].is_beat.likes2.length
			})
		}
	}
}
</script>

<style scoped>
.active {
  background: green;
}
</style>