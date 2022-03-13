<template>
<div class="fullPlayer">
	    <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.15.4/css/all.css" integrity="sha384-DyZ88mC6Up2uqS4h/KRgHuoeGwBcD4Ng9SiP4dIRy0EXTlnuz47vAwmeGwVChigm" crossorigin="anonymous"/>

	<div class="playerImgBox">
		<img class="block h-52 w-52 max-w-none playerImg" loading="lazy" @error="defaultCover = true" v-if="!defaultCover" :src="track.is_beat.get_cover.cover_path" ref="cover"/>
		<img class="block h-52 w-52 max-w-none playerImg" loading="lazy" v-if="defaultCover" src="http://localhost:8000/storage/covers/Default-cover.png" ref="cover"/>
	</div>
	<div class="playerControl h-52 flex flex-col transition-width">
		<div class="flex items-center justify-center h-full">
			<div class="w-full h-full">
				<div class="flex w-full">
					<div class="w-full playerControl-flex transition-width"  style="width: 45rem !important;">
						<div class="flex justify-between player-main-inf">
							<div>
								<h1 class="trackName">{{track.is_beat.title}}</h1>
								<a :href="'/profile/' + track.is_beat.from_user.username"><p class="artistName">{{track.is_beat.from_user.username}}</p></a>
								<p v-if="track.is_beat.feature" class="featName">Feat. {{track.is_beat.feature}}</p>
							</div>
							<!-- <div class="text-red-lighter">
								<svg class="w-6 h-6" @click="likeUnlike" :fill="likedColor" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20"><path d="M10 3.22l-.61-.6a5.5 5.5 0 0 0-7.78 7.77L10 18.78l8.39-8.4a5.5 5.5 0 0 0-7.78-7.77l-.61.61z"/></svg>
								{{likeCount}}
							</div> -->
							<button class="popup-cta pay" @click="paymentEmit">
								{{ this.track.is_beat.price }} $
							</button>
						</div>	
						<div class="flex flex-auto h-12 flex justify-end items-end" style="padding-right: 10px">
								<span v-if="own" @click="this.emitter.emit('openPopupEdit', this.track)" class="underline">
								edit 
								</span>
							</div>
						<div class="flex items-center">
							<div class="" @click="testEmit" >
								<svg v-if="rn" class="w-8 h-8" fill="black" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20"><path d="M5 4h3v12H5V4zm7 0h3v12h-3V4z"/></svg>
								<svg v-else class="w-8 h-8" fill="black" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20"><path d="M 5 4 l 10 6 l -10 6 z z"/></svg>
							</div>
							<div class="volumeControls flex flex-row items-center" v-if="current">
								<div style="width: 24px" class="m-2" @click="mute">
								<svg id="Ebene_1" class="main_icon_volume" data-name="Ebene 1" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 97.121 66.698"><defs></defs>
									<path class="cls-1" d="M50.174,21.514V78.486a4.855,4.855,0,0,1-8.044,3.66L21.374,64.07H8.958a7.4,7.4,0,0,1-7.4-7.4V43.33a7.4,7.4,0,0,1,7.4-7.4H21.374L42.13,17.854A4.855,4.855,0,0,1,50.174,21.514Z" transform="translate(-1.558 -16.651)"/>    
									<path v-if="volume == 0" class="cls-1" d="M86.126,57.322a3.4,3.4,0,1,1-4.808,4.812L73.994,54.81,66.67,62.134a3.4,3.4,0,0,1-4.808-4.812L69.186,50l-7.324-7.324a3.4,3.4,0,0,1,4.808-4.808l7.324,7.324,7.324-7.324a3.4,3.4,0,1,1,4.808,4.808L78.8,50Z" transform="translate(-1.558 -16.651)"/>
									<path v-if="volume > 0" class="cls-1" d="M72.615,50a12.687,12.687,0,0,1-7.136,11.46,3.2,3.2,0,0,1-4.4-1.732,3.246,3.246,0,0,1,1.6-4.028,6.369,6.369,0,0,0,0-11.4,3.246,3.246,0,0,1-1.6-4.028,3.2,3.2,0,0,1,4.4-1.728A12.676,12.676,0,0,1,72.615,50Z" transform="translate(-1.558 -16.651)" />
									<path v-if="volume > 0.3" class="cls-1" d="M86.3,50a29.29,29.29,0,0,1-2.908,13.02,22.28,22.28,0,0,1-8.064,9.092,3.2,3.2,0,0,1-4.536-1.228,3.3,3.3,0,0,1,1.24-4.26C76.887,63.521,79.9,57.161,79.9,50s-3.008-13.52-7.868-16.628a3.293,3.293,0,0,1-1.24-4.256,3.2,3.2,0,0,1,4.536-1.232,22.291,22.291,0,0,1,8.064,9.1A29.277,29.277,0,0,1,86.3,50Z" transform="translate(-1.558 -16.651)" />
									<path v-if="volume > 0.7" class="cls-1" d="M98.679,50c0,14.008-6.552,26.592-17.1,32.844a3.2,3.2,0,0,1-4.708-1.856,3.241,3.241,0,0,1,1.52-3.692C86.959,72.173,92.279,61.725,92.279,50s-5.32-22.176-13.888-27.3a3.243,3.243,0,0,1-1.52-3.692,3.2,3.2,0,0,1,4.708-1.86C92.127,23.405,98.679,35.993,98.679,50Z" transform="translate(-1.558 -16.651)" />
								</svg>
								<!-- <p>Volume {{volume}}</p> -->
								</div>
								<!-- <input v-if="showVol" type="range" step="0.01" min="0" max="1" v-model="volume" @change="volumeEmit"> -->
								<div class="main-volume-bar-player" @click="volSlider" @drag="volSlider" ref="volSliderRef" @dragend="volSlider">
									<div class="main-volume-bar-in" :style="{'width': (myvol*100)+'%'}"></div>
								</div>
							</div>
							<div class="flex flex-auto"></div>
							<div class="text-red-lighter main-like">
								{{likeCount}}
								<svg class="w-6 h-6" @click="likeUnlike" :fill="likedColor" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20"><path d="M10 3.22l-.61-.6a5.5 5.5 0 0 0-7.78 7.77L10 18.78l8.39-8.4a5.5 5.5 0 0 0-7.78-7.77l-.61.61z"/></svg>
							</div>
							<svg id="Ebene_1" @click="infoEmit" class="main-info-icon w-6 h-6 m-2" data-name="Ebene 1" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 76.71 76.71"><path d="M50.12,11.71A38.355,38.355,0,1,0,88.47,50.07,38.361,38.361,0,0,0,50.12,11.71ZM57.58,79.8H42.9V45.52l14.68-.41Zm-7.5-41.93c-5.14,0-9.14-3.59-9.14-8.73s4-8.81,9.14-8.81S59.3,24,59.3,29.14,55.22,37.87,50.08,37.87Z" transform="translate(-11.76 -11.71)"/><polygon  style="fill:#e6e6e6" class="cls-1" points="45.82 33.4 45.82 68.09 31.14 68.09 31.14 33.81 45.82 33.4"/><path  style="fill:#e6e6e6" class="cls-1" d="M59.3,29.14c0,5.14-4.08,8.73-9.22,8.73s-9.14-3.59-9.14-8.73,4-8.81,9.14-8.81S59.3,24,59.3,29.14Z" transform="translate(-11.76 -11.71)"/></svg>
							<!-- <div @click="infoEmit" class="main-info-button">
								i
							</div> -->

							<!-- {{own}} -->
							<!-- <div @click="paymentEmit">
								{{ this.dollarPrice }} $
							</div> -->

							
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
		<div class="main-slider-container" @click="mainSlider" @drag="mainSlider" ref="mainSliderRef" @dragend="mainSlider" @dragover="mainSlider">
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
			own: this.currentUser(this.track.is_beat.from_user.id),
			showVol: false,
			myvol: this.volume,
			volold: null,
		}
	},
	created() {
	},
	mounted(){
		// console.log('mount')
		// console.log(this.$page.props)
		this.liked()
		this.convert()
		// console.log('conv')
		this.emitter.on("reload", () => {
            this.convert();
            console.log('reload');
        });
	},
	methods: {
		mute(){
			if(!this.volold && this.volold != 0){
				console.log('mute')
				this.volold = this.myvol;
				this.myvol = 0;
			}else{
				if(this.volold == 0){
					this.myvol = 0.5;
				}else{
					this.myvol = this.volold;
				}
				console.log('mute aus')
				this.volold = null;
			}
			this.volumeEmit();
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
			this.emitter.emit('slide', {id: this.numb, timeP: perc})
		},
		volSlider(e){
			let min = 0, cur = e.clientX - this.$refs.volSliderRef.offsetLeft, max = this.$refs.volSliderRef.clientWidth, perc = cur/max
			console.log(cur)
			console.log(max)
			console.log(perc)
			this.myvol = perc;
			this.volumeEmit()
		},
        currentUser(x){
            if(this.$page.props.auth.user){
                return this.$page.props.auth.user.id == this.track.is_beat.from_user.id
            } return false
        },
		testEmit() {

			// console.log('yaay');
			this.emitter.emit("play-pause", this.numb);
		},
		infoEmit() {
			this.emitter.emit("show-info", this.numb);
		},
		paymentEmit() {
			if(this.$page.props.auth.user) {
				this.emitter.emit("openPopupPayment", this.track)
			} else {
				this.emitter.emit('error', 'Login to Purchase a track')
			}
		},
		volumeEmit(){
			// this.emitter.emit("volume-change", this.volume);
			console.log(this.myvol)
			this.$emit("vol", this.myvol)
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
			// console.log('test')
			let currentObj = this;
            axios.get('https://api.coinbase.com/v2/exchange-rates')
            .then(res => {
				// console.log(res)
				// console.log(this.track.is_beat.price)
				// console.log(currentObj.track.is_beat.price)

              currentObj.dollarPrice = (Math.round(((currentObj.track.is_beat.price / res.data.data.rates.ETH) + Number.EPSILON) * 100) / 100).toFixed(2)
            })
        },

		likeUnlike(e){
			if (this.$page.props.auth.user) {
				console.log(e)
				if(this.likedColor == 'black'){
						this.likedColor = 'red'
						this.likeCount++;
					}else{
						this.likedColor = 'black'
						this.likeCount--;
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
						this.$refs.cover.src = "/storage/covers/Default-cover"+val+".png"
						// console.info(this.$refs.cover)
					}
					// this.refreshLikeCount()
					if(!response.data.del){
						this.likedColor = 'red'
					}else{
						this.likedColor = 'black'
						}
				})
			} else {
				this.emitter.emit('error', 'Login to like a track')
			}
		},
		refreshLikeCount(){
			axios.get('/api/likeCount/'+this.track.beat_id)
			.then(response =>{
				console.info(response)
				console.info(response.data[0].is_beat.likes2.length)
				this.likeCount= response.data[0].is_beat.likes2.length
			})
		},
	},
}
</script>

<style scoped>
.active {
  background: green;
}
</style>