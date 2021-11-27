<template>
<div class="fullPlayer">
	<div class="playerImgBox">
		<img class="block h-52 w-52 max-w-none playerImg" @error="defaultCover = true" v-if="!defaultCover" :src="track.is_beat.get_cover.cover_path" ref="cover"/>
		<img class="block h-52 w-52 max-w-none playerImg" v-if="defaultCover" src="/storage/covers/Default-cover.png" ref="cover"/>
	</div>
	<div class="playerControl h-52" style="width: 45rem !important;">
		<div class="flex items-center justify-center">
			<div class="w-full">
				<div class="flex">
					<div class="w-full p-8">
						<div class="flex justify-between">
							<div>
								<h1 class="trackName">{{track.is_beat.title}}</h1>
								<p class="artistName">{{track.is_beat.from_user.username}}</p>

							</div>
							<div class="text-red-lighter">
								<svg class="w-6 h-6" @click="likeUnlike" :fill="likedColor" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20"><path d="M10 3.22l-.61-.6a5.5 5.5 0 0 0-7.78 7.77L10 18.78l8.39-8.4a5.5 5.5 0 0 0-7.78-7.77l-.61.61z"/></svg>
								{{likeCount}}
							</div>
						</div>
						
						<div class="flex justify-between items-center mt-8">
							<div class="" @click="testEmit" >
								<svg v-if="rn" class="w-8 h-8" fill="black" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20"><path d="M5 4h3v12H5V4zm7 0h3v12h-3V4z"/></svg>
								<svg v-else class="w-8 h-8" fill="black" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20"><path d="M 5 4 l 10 6 l -10 6 z z"/></svg>
							</div>
							<div @click="infoEmit" >
								INFO
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
	<div class="playerInfo">
		<div v-if="numb == info" class="transition-width w-52">
			<div class="flex flex-row">
				<span v-if="track.is_beat.bpm">{{track.is_beat.bpm}} BPM</span>
				<span v-if="track.is_beat.bpm && track.is_beat.key">{{ workaround }}</span>
				<span v-if="track.is_beat.key">Key: {{track.is_beat.key}}</span>
			</div>
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
		info: Number,
    },
	data() {
		return{
			hover: false,
			defaultCover: false,
			likedColor: 'black',
			likeCount: this.track.is_beat.likes2.length,
			showInfo: false,
			workaround: ", ",
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
			if(this.$page.props.auth.user){
				this.track.is_beat.likes2.forEach(e => {
					if(e.id == this.$page.props.auth.user.id){
						this.likedColor = 'red'
					}
				});
			}
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
					var val;
					switch (response.data.cov) {
						case 1:
							val = ''
							break;
						case 2:
							val = '_silver'
							break;
						case 3:
							val = '_gold'
							break;
						case 4:
							val = '_diamond'
							break;
					
						default:
							val = ''
							break;
					}
					this.$refs.cover.src = "storage/covers/Default-cover"+val+".png"
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