<template>
<div class="fullPlayer">
	<div class="playerImgBox">
		<img class="block h-52 w-52 max-w-none playerImg" loading="lazy" @error="defaultCover = true" v-if="!defaultCover" :src="track.is_beat.get_cover.cover_path" ref="cover"/>
		<img class="block h-52 w-52 max-w-none playerImg" loading="lazy" v-if="defaultCover" src="http://localhost:8000/storage/covers/Default-cover.png" ref="cover"/>
	</div>
	<div class="playerControl h-52 flex flex-col transition-width">
		<div class="flex items-center justify-center h-full">
			<div class="w-full h-full">
				<div class="flex w-full">
					<div class="w-full playerControl-flex transition-width"  style="width: 45rem !important;">
						<div class="flex justify-between">
							<div>
								<h1 class="trackName">{{track.is_beat.title}}</h1>
								<a :href="'/myprofile/' + track.is_beat.from_user.username"><p class="artistName">{{track.is_beat.from_user.username}}</p></a>
								<p v-if="track.is_beat.feature" class="featName">Feat. {{track.is_beat.feature}}</p>
							</div>
							<!-- <div class="text-red-lighter">
								<svg class="w-6 h-6" @click="likeUnlike" :fill="likedColor" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20"><path d="M10 3.22l-.61-.6a5.5 5.5 0 0 0-7.78 7.77L10 18.78l8.39-8.4a5.5 5.5 0 0 0-7.78-7.77l-.61.61z"/></svg>
								{{likeCount}}
							</div> -->
							<div @click="paymentEmit">
								{{ this.dollarPrice }} $
							</div>
						</div>	
						<div class="flex flex-auto"></div>
						<div class="flex items-center">
							<div class="" @click="testEmit" >
								<svg v-if="rn" class="w-8 h-8" fill="black" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20"><path d="M5 4h3v12H5V4zm7 0h3v12h-3V4z"/></svg>
								<svg v-else class="w-8 h-8" fill="black" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20"><path d="M 5 4 l 10 6 l -10 6 z z"/></svg>
							</div>
							<div class="volumeControls">
								<div v-if="volumeOff"  @click="toggleVol">
									<p>no volume rn</p>
								</div>
								<div v-else @click="toggleVol">
									<p>voLUME</p>
								</div>
								<div v-if="volumeOn">
									<input type="range" min="0" max="100" v-model="volume" @click="volumeEmit" @drag="volumeEmit" @dragend="volumeEmit">
								</div>
							</div>
							<div class="flex flex-auto"></div>
							<div @click="infoEmit" class="main-info-button">
								i
							</div>
							<!-- <div @click="paymentEmit">
								{{ this.dollarPrice }} $
							</div> -->
							<div class="text-red-lighter main-like">
								{{likeCount}}
								<svg class="w-6 h-6" @click="likeUnlike" :fill="likedColor" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20"><path d="M10 3.22l-.61-.6a5.5 5.5 0 0 0-7.78 7.77L10 18.78l8.39-8.4a5.5 5.5 0 0 0-7.78-7.77l-.61.61z"/></svg>
								
							</div>
						</div>
					</div>
					<div class="playerInfo ">
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
			</div>
		</div>
		<!-- <div class="flex flex-auto"></div> -->
		<div class="main-slider-container" @click="mainSlider" @drag="mainSlider" ref="mainSliderRef" @dragend="mainSlider">
			<div class="main-slider-small">

			</div>
			<div class="main-slider-big">
				<div class="main-slider-play" 
				:style="[curTime ? 
					{width: ((curTime*100)/track.is_beat.length)+'%'} : 
					this.$page.props.tracks[numb] ? {width: ((this.$page.props.tracks[numb]*100)/track.is_beat.length)+'%'} :
					{width: ((curTime*100)/track.is_beat.length)+'%'}]"></div>
				<div class="main-slider-hider"></div>
			</div>
		</div>
	</div>
	<!-- {{track.is_beat.length}} -->
	<!-- <div class="playerInfo">
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
	</div> -->
</div>
</template>



<script>    
import axios from 'axios';
import slider from 'vue3-slider';
export default {
    name: 'Player',
    
    props: {
        track: Object,
		current: Boolean,
		numb: Number,
		rn: {Boolean, default: false},
		curTime: {Number, default: 0},
		info: Number,
		volume: Number,
    },
	components: {
		slider,
	},
	data() {
		return{
			hover: false,
			defaultCover: false,
			likedColor: 'black',
			likeCount: this.track.is_beat.likes2.length,
			showInfo: false,
			workaround: ", ",
			dollarPrice: 0,
			volumeX: 100,
			volumeOn: false,
			volumeOff: false,
		}
	},
	created() {
	},
	mounted(){
		// console.log(this.$page.props)
		this.liked()
		this.convert()
		this.emitter.on("reload", () => {
            this.convert();
            console.log('reload');
        });
	},
	methods: {
		mainSlider(e){
			let min = 0, cur = e.clientX - this.$refs.mainSliderRef.offsetLeft, max = this.$refs.mainSliderRef.clientWidth, perc = cur/max
			// console.log('----------------------------------')
			// console.log(e)
			// console.log(this.$refs.mainSliderRef)
			// console.log(min + ' - '+ cur +' - '+ max)
			// console.log(cur/max)
			if(perc > 1){perc = 1}
			if(perc < 0){perc = 0}
			this.emitter.emit('slide', {id: this.numb, timeP: perc})
		},
		testEmit() {

			// console.log('yaay');
			this.emitter.emit("play-pause", this.numb);
		},
		infoEmit() {
			this.emitter.emit("show-info", this.numb);
		},
		paymentEmit() {
			this.emitter.emit("openPopupPayment", this.track);
		},
		volumeEmit(){
			if(this.volume == 0){
				this.volumeOff = true;
			} else {
				this.volumeOff = false;
			}
			this.emitter.emit("volume-change", this.volume);
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

		convert() {
			let currentObj = this;
            axios.get('https://api.coinbase.com/v2/exchange-rates')
            .then(res => {
				// console.log(this.track.is_beat.price)
				// console.log(currentObj.track.is_beat.price)

              currentObj.dollarPrice = (Math.round(((currentObj.track.is_beat.price / res.data.data.rates.ETH) + Number.EPSILON) * 100) / 100).toFixed(2)
            })
        },

		likeUnlike(e){
			console.log(e)
			if(this.likedColor == 'black'){
					this.likedColor = 'red'
				}else{
					this.likedColor = 'black'
					}
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
		},
		toggleVol(){
			this.volumeOn = !this.volumeOn;
		},
	},
	mounted() {
		console.log(this.volume);
	},
}
</script>

<style scoped>
.active {
  background: green;
}
</style>