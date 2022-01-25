<template>
<link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">
    <div class="popup-bg-blurr" @click="close">
        <div class="popup-main" @click.stop @click="currencySelect = false">
            <!-- {{$page.props.auth.user}} -->
            <div class="popup-title">Upload</div>
    <!-- <form v-if="logedin" :action="route" method="post" enctype="multipart/form-data">upload -->
            <form class="popup-content main-form" v-if="$page.props.auth.user" @submit="upload()" enctype="multipart/form-data">
                <input type="hidden" name="_token" v-bind:value="$page.props.tokens.csrf" />
                
                <!-- <transition name="fade">
                    <div v-if="!isHidden && error" class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative" role="alert">
                        <span class="material-icons material-icons-outlined m-auto sm:inline" style="font-size:20px">gpp_bad</span>
                        <strong class="font-bold">Upload error<span v-if="error.length > 1">s</span>{{error.length}}: </strong>
                        <span class="block sm:inline" v-if="error.length < 2">{{error}}</span>
                        <span class="block sm:inline" v-else v-for="e in error">{{e[0]}}lol<br></span>
                        <button v-on:click="hide">
                            <span class="absolute top-0 bottom-0 right-0 px-4 py-3">
                                <svg class="fill-current h-6 w-6 text-red-500" role="button" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20"><title>Close</title><path d="M14.348 14.849a1.2 1.2 0 0 1-1.697 0L10 11.819l-2.651 3.029a1.2 1.2 0 1 1-1.697-1.697l2.758-3.15-2.759-3.152a1.2 1.2 0 1 1 1.697-1.697L10 8.183l2.651-3.031a1.2 1.2 0 1 1 1.697 1.697l-2.758 3.152 2.758 3.15a1.2 1.2 0 0 1 0 1.698z"/></svg>
                            </span>
                        </button>
                        
                    </div>
                </transition>

                <transition name="fade">
                    <div v-if="!isHidden && success" class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative" role="alert">
                        <strong class="font-bold">Success! </strong>
                        <span class="block sm:inline">Your track has been uploaded.</span>
                        <button v-on:click="hide">
                            <span class="absolute top-0 bottom-0 right-0 px-4 py-3">
                                <svg class="fill-current h-6 w-6 text-green-500" role="button" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20"><title>Close</title><path d="M14.348 14.849a1.2 1.2 0 0 1-1.697 0L10 11.819l-2.651 3.029a1.2 1.2 0 1 1-1.697-1.697l2.758-3.15-2.759-3.152a1.2 1.2 0 1 1 1.697-1.697L10 8.183l2.651-3.031a1.2 1.2 0 1 1 1.697 1.697l-2.758 3.152 2.758 3.15a1.2 1.2 0 0 1 0 1.698z"/></svg>
                            </span>
                        </button>
                    </div>
                </transition> -->

                <div class="main-form-row">
                    <div class="main-form-element vier-fünftel">
                        <label class="custom-text-label" for="title">Title</label><br>
                        <input
                            type="text"
                            name="beatTitle"
                            :class="[error && error.title && error.title[0].includes('required') && !title ? 'error' : '', 'custom-text-input main-text-input']"
                            id="title"
                            required
                            v-model="title"
                            @click="clickTitle"
                        />
                    </div>

                    <div class="main-form-element ein-fünftel flex flex-col items-end justify-end ">
                        <label class="inline-flex items-center">
                            <span class="ml-2">Beat </span>
                            <input
                            type="radio"
                            class="form-radio main-form-radio"
                            :class="[error && error.type && !type ? 'error' : '']"
                            name="beatType"
                            value="beat"
                            v-model="type"
                            required
                            />
                        </label>
                        <label class="inline-flex items-center">
                            <span class="ml-2">Sample </span>
                            <input
                            type="radio"
                            class="form-radio main-form-radio"
                            :class="[error && error.type && !type ? 'error' : '']"
                            name="beatType"
                            value="sample"
                            v-model="type"
                            />
                        </label>
                    </div>
                </div>
                <div class="main-form-row">
                    <div class="main-form-element half">  
                        <label class="custom-text-label" for="bpm">BPM</label><br>
                        <input
                            type="text"
                            name="beatBPM"
                            :class="[error && error.bpm && error.bpm[0].includes('required') && !bpm ? 'error' : '', 'custom-text-input main-text-input']"
                            id="bpm"
                            required
                            v-model="bpm"
                            v-on:keypress="NumbersOnly"
                        />
                    </div>

                    <div class="main-form-element half"
                        @mouseleave="mouseInpf(false)"
                        @mouseover="mouseInpf(true)"
                    >
                        <label class="custom-text-label" for="key">Key</label><br>
                        <input
                            type="text"
                            name="key"
                            :class="[error && error.key ? 'error' : '', 'custom-text-input main-text-input']"
                            id="key"
                            required
                            v-model="selectedKey"
                            @keydown="keyClick = false; mouseOptf(false)"
                            @focus="selectFocus = true"
                            @blur="leave('blur')"
                            ref="keyInp"
                        />
                        <div class="main-select-custom-options" 
                            v-if="selectFocus && !keyClick"
                            @mouseleave="mouseOptf(false)"
                            @mouseover="mouseOptf(true)"
                        >
                            <p v-for="k in keys">
                                <span v-if="!selectedKey || k.toUpperCase().includes(selectedKey.toUpperCase())"
                                    @click="selectedKey = k; $refs.keyInp.focus(); keyClick = true">
                                    {{k}}
                                </span>
                            </p>
                        </div>
                        <!-- <select :class="[error.key ? 'error' : '','form-control main-select-input']" id="key" @change="changeKey($event)">
                            <option value="" selected></option>
                            <option id="keyVal" v-for="k in keys" :value="k" :key="k">{{ k }}</option>
                        </select> -->
                        <!-- <v-select :options="[{country: 'Canada', code: 'CA'},]"></v-select> -->
                    </div>
                </div>
                <div class="main-form-row">
                    <div class="main-form-element">
                        <label class="custom-text-label" for="description">Description</label><br>
                            <input
                            type="text"
                            name="description"
                            :class="[error && error.desc ? 'error' : '', 'custom-text-input main-text-input']"
                            id="description"
                            v-model="description"
                        />
                    </div>
                </div>
                <div class="main-form-row">
                    <div class="main-form-element half">  
                        <label class="custom-text-label" for="price">Price</label><br>
                        <div v-if="currency == 'ETH' || currencySelect"
                            @click.stop 
                            @click="currencySelect = !currencySelect; currency= 'ETH'" 
                            class="main-form-input-select price top"
                            :class="[currencySelect ? 'open' : '']"
                        >
                            ETH
                            <i class="arrow down" v-if="!currencySelect"></i>
                        </div>
                        <div v-if="currency == 'USD' || currencySelect" 
                            @click.stop 
                            @click="currencySelect = !currencySelect; currency= 'USD'" 
                            class="main-form-input-select price bottom"
                            :class="[currencySelect ? 'open' : '']"
                        >
                            USD 
                            <i class="arrow down" v-if="!currencySelect"></i>
                        </div>
                        <i class="currency-icon">
                            <svg v-if="currency == 'ETH'" class="ETH-sign" width="20pt" height="20" version="1.1" viewBox="0 0 1200 1200" xmlns="http://www.w3.org/2000/svg">
                            <g>
                            <path d="m226.11 606.24c0.058593 2.4258 0.57422 4.7266 1.5469 6.9492 0.3125 0.74219 0.55078 1.4648 0.98438 2.1484 1.5 2.5078 3.4062 4.8008 6.0977 6.4336 0.035156 0.023438 0.10938 0.023438 0.14453 0.046875 0.046875 0.023438 0.058594 0.085938 0.10938 0.10938l357.07 209.22c0.27734 0.16797 0.60156 0.085938 0.89844 0.25391 2.5664 1.3203 5.3984 2.3047 8.5312 2.3047 3.1328 0 5.9648-0.98438 8.5547-2.3516 0.32422-0.13281 0.69531-0.085937 1.0078-0.25391l350.72-209.26c2.7461-1.6328 4.668-3.9727 6.2266-6.5273 0.42188-0.70703 0.68359-1.3672 0.98438-2.125 0.98438-2.2305 1.5-4.5234 1.5859-6.9492 0-0.51562 0.33594-0.9375 0.30078-1.4297 0-0.22656-0.17969-0.38281-0.22656-0.61328-0.21484-2.7461-0.89844-5.4375-2.3984-7.957l-350.74-587.14c-0.13281-0.23828-0.35937-0.33594-0.51562-0.5625-0.42188-0.67188-0.9375-1.1992-1.4531-1.8008-0.64844-0.75781-1.3086-1.4297-2.0625-2.1016-0.57422-0.46875-1.0938-0.98437-1.7031-1.3672-0.23828-0.16797-0.35938-0.40625-0.60156-0.55078-0.40625-0.25391-0.85156-0.25391-1.2617-0.45703-0.81641-0.43359-1.6328-0.74219-2.5312-1.0547-0.74219-0.26562-1.4883-0.52734-2.293-0.68359-0.79297-0.17969-1.5586-0.21484-2.3516-0.25391-0.42969-0.050781-0.8125-0.26562-1.2461-0.26562-0.43359 0-0.76953 0.21484-1.1992 0.23828-0.76953 0.070313-1.4883 0.085938-2.2305 0.23828-0.85156 0.15625-1.6328 0.43359-2.4727 0.70703-0.875 0.30078-1.6914 0.625-2.5078 1.0547-0.37109 0.20312-0.79297 0.20312-1.1641 0.40625-0.21484 0.13281-0.34766 0.35938-0.55078 0.49219-0.66016 0.43359-1.2227 0.96094-1.8477 1.5-0.74219 0.64844-1.4141 1.2852-2.0625 2.0391-0.46875 0.57422-0.98438 1.0938-1.3672 1.7031-0.17969 0.23828-0.40625 0.35938-0.5625 0.60156l-357.06 587.16c-1.5469 2.5195-2.2305 5.2695-2.4727 8.0625-0.023438 0.23828-0.19141 0.39453-0.21484 0.64844-0.019532 0.46484 0.30078 0.89453 0.32812 1.3867zm394.05-519.95 285.2 477.49-285.2-130.82zm-37.332 346.56-291.13 131.17 291.13-478.74zm0 40.969v308.62l-297.78-174.46zm37.332 308.32v-308.12l291.98 133.97z"/>
                            <path d="m969.28 670.03c-5.9141-6.4805-15.66-7.7656-23.219-3.4336l-344.57 203.44-344.44-203.43c-7.5234-4.3789-17.27-3.0469-23.258 3.4336-5.9648 6.5273-6.5898 16.246-1.4883 23.461l353.94 498.66c0.27734 0.38281 0.73047 0.50391 1.0078 0.85156 0.73047 0.88672 1.4648 1.668 2.3164 2.3984 0.40625 0.33594 0.64844 0.85156 1.0938 1.1523 0.34766 0.25391 0.79297 0.14453 1.1641 0.38281 0.60156 0.34766 1.1172 0.85156 1.7773 1.1758 2.375 1.1523 5.0039 1.8828 7.8125 1.8828h0.035156 0.023437 0.023438 0.035156c2.8086 0 5.4258-0.74219 7.8125-1.8828 0.66016-0.3125 1.1758-0.81641 1.7773-1.1758 0.35938-0.25391 0.80469-0.13281 1.1641-0.38281 0.44531-0.28906 0.68359-0.80469 1.0938-1.1523 0.85156-0.74219 1.5977-1.5117 2.3047-2.3984 0.27734-0.34766 0.74219-0.46875 1.0078-0.85156l354.07-498.66c5.1211-7.2148 4.4883-16.938-1.4883-23.465zm-386.45 232.39v220.34l-269.35-379.46zm37.332 220.34v-220.34l269.46-159.12z"/>
                            </g>
                            </svg>
                            <span class="doller-sign" v-else>$</span>
                        </i>
                        <input
                            type="text"
                            name="beatPrice"
                            :class="[error && error.price && error.price[0].includes('required') && !price ? 'error' : '', 'custom-text-input main-text-input price-input']"
                            id="price"
                            required 
                            @focus="currencySelect = false"
                            v-model="price"
                            v-on:keypress="NumbersOnly"
                        />
                    </div><div class="main-form-element half">
                        <label class="custom-text-label" for="feature">Featured Artists</label><br>
                            <input
                            type="text"
                            name="featArtist"
                            :class="[error && error.feature ? 'error' : '', 'custom-text-input main-text-input']"
                            id="feature"
                            v-model="feature"
                        />
                    </div>
                </div>
                <div class="main-form-row">
                    <div class="main-form-element">
                        <label class="custom-text-label" for="tags">Tags</label><br>
                        <vue-tags-input
                        v-model="tag"
                        :tags="tags"
                        @tags-changed="newTags => tags = newTags"
                        :max-tags="5"
                        :class="[error && error.tags ? 'error' : '', 'main-tag-input']"
                        placeholder=""
                        id="tags"
                        >
                            <div>

                            </div>
                        </vue-tags-input>
                    </div>
                </div>
                <div class="main-form-row flex justify-between">
                    <div class="main-form-element zwei-fünftel flex flex-col justify-end">
                        <label class="custom-text-label" for="chooseFile">Track</label>
                        <label 
                            v-if="beat" 
                            class="main-file-label-box checked mt-2" 
                            :class="[error && error.beat && error.beat[0].includes('required') && !beat ? 'error' : '']"
                            for="chooseFile"
                        ></label>
                        <label 
                            v-else 
                            class="main-file-label-box mt-2" 
                            :class="[error && error.beat && error.beat[0].includes('required') && !beat ? 'error' : '']"
                            for="chooseFile"
                        ></label>
                        <span class="main-file-name beat">{{beatFileName}}</span>
                        <input
                            type="file"
                            name="file"
                            class="custom-file-input"
                            id="chooseFile"
                            accept="audio/mp3"
                            v-on:change="onBeatChange"
                            style="display:none"
                        />
                    </div>
                    <div class="main-form-element zwei-fünftel flex flex-col items-end">
                        <div>
                            <label class="custom-text-label" for="cover">Custom Cover</label>
                            <!-- <span @click="coverCheck" :class="[coverCheckBool ? 'main-form-checkbox checked' : 'main-form-checkbox']"></span> -->
                            <input type="checkbox" class="form-checkbox main-form-checkbox" v-model="coverType" @click="changeCoverSel" name="coverType">
                        </div>
                        <label 
                            v-if="cover && coverType" 
                            class="main-file-label-box checked mt-2" 
                            :class="[error && error.bpm ? 'error' : '']"
                            for="cover"
                        ></label>
                        <label 
                            v-if="!cover && coverType" 
                            class="main-file-label-box mt-2" 
                            :class="[error && error.bpm ? 'error' : '']"
                            for="cover"
                        ></label>
                        <span class="main-file-name cover">{{coverFileName}}</span>
                        <input 
                            type="file" 
                            ref="file" 
                            name="file2" 
                            @change="loadImage($event)" 
                            accept="image/*" 
                            required
                            id="cover"
                            style="display:none"
                        />
                    </div>
                </div>
                <div class="main-form-row flex flex-col" v-if="error">
                    <span class="main-form-error-msg" v-for="e in error">{{e[0]}}<br></span>
                </div>
            </form>
            <div class="popup-footer">
                <button class="popup-cta back" @click="close()">Back</button>
                <button class="popup-cta submit"
                            :class="{ 'opacity-25': processing }" 
                            :disabled="processing" 
                @click="upload();">Upload</button>
            </div>
        </div>
        <popup-crop v-if="showPopupCrop" :img="cover"></popup-crop>
    </div>
</template>


<script>
import VueTagsInput from "@sipec/vue3-tags-input";
import PopupCrop from "./PopupCrop.vue";
// import vSelect from 'vue-select';

export default {

    name: 'PopupUpload',

    components: {
        VueTagsInput,
        // vSelect,
        PopupCrop,
    },

    data() {
        return {
            form: this.$inertia.form({}),
            processing: false,
            beat: '',
            cover: '',
            coverType: '',
            title: '',
            tag: '',
            tags: ['test1', 'test23', 'abcde'],
            bpm: '',
            keys: ["C", "Cm", "Db", "C#m", "D", "Dm", "Eb", "D#m", "E", "Em", "F", "Fm", "Gb", "F#m", "G", "Gm", "Ab", "G#m", "A", "Am", "Bb", "A#m", "B", "Bm"],
            selectedKey: '',
            type: '',
            description: '',
            feature: '',
            price: '',
            ethPrice: '',
            error: {},
            success: null,
            isHidden: false,
            loading: false,
            coverCheckBool: false,
            selectFocus:false,
            clickTitle:false,
            mouseOpt: false,
            mouseInp: false,
            keyClick: false,
            currency: 'ETH',
            currencySelect: false,
            beatFileName: '',
            coverFileName: '',
            showPopupCrop: '',
            length: null,
        }
    },
    
    props: {
        file1: String,
        file2: String,
        logedin: Number,
    },
    created(){
    },
	methods: {
        focus(bool){
            this.emitter.emit("focus", bool)
        },
        test(e){
            console.warn('help')
            console.log(e)
            e.preventDefault();
        },
		close() {
			console.log('yaay');
			this.emitter.emit("closePopupUpload");
		},
        hide(e){
            e.preventDefault();
            this.isHidden = true;
        },
        convert() {
            axios.get('https://api.coinbase.com/v2/exchange-rates')
            .then(res => {
              this.ethPrice = res.data.data.rates.ETH * this.price;
            })
        }, 
        changeCoverSel(){
            if(this.coverType){
                this.cover = ''
                this.coverFileName = ''
            }
        },
        // submit(){
        //     this.upload(document.getElementById("upload-form"));       
        // },
        upload(e){
            this.processing = true;
            if(e){
                console.log(e)
                e.preventDefault();
            }
            console.log('lezzgo')
            this.loading = true;
            let currentObj = this;
            const config = {
                headers: {
                    'content-type': 'multipart/form-data',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                },
            }

            let formData = new FormData();
            formData.append('beat', this.beat);
            formData.append('length', this.length);

            if (this.coverType) {
                formData.append('cover', this.cover);
            }

            var tagsFin = [];
            Object.values(this.tags).forEach(e => {
                tagsFin.push(e.text)
            });


            formData.append('title', this.title);
            formData.append('userID', this.$page.props.auth.user.id);
            formData.append('bpm', this.bpm);
            formData.append('tagsArr', tagsFin);
            formData.append('selectedKey', this.selectedKey);
            formData.append('type', this.type);
            formData.append('description', this.description);
            formData.append('feature', this.feature);


            axios.get('https://api.coinbase.com/v2/exchange-rates')
                .then(res => {
                    if(currentObj.currency == 'USD'){
                        currentObj.ethPrice = res.data.data.rates.ETH * currentObj.price;
                    }else{
                        currentObj.ethPrice = currentObj.price;
                    }
                    formData.append('ethPrice', currentObj.ethPrice);
                    formData.forEach(console.log)
                    return axios.post('/api/upload', formData, config)
                })
                .then(function (response) {
                        currentObj.loading = false;
                        currentObj.isHidden = false;
                        console.log(response);
                        if (response.data.error) {
                            currentObj.error = response.data.error
                            currentObj.success = null
                            currentObj.processing = false;
                            console.log(currentObj.error)
                        }else if (response.data.success) {
                            currentObj.emitter.emit('upload-success');
                            currentObj.success = response.data.success
                            currentObj.error = null
                            currentObj.close();
                            console.log(currentObj.success)
                        }
                    })
                    .catch(error => {
                        if(error.response){
                        console.log(error)
                        currentObj.error = error.response.data.errors
                        currentObj.success = null
                        console.log(error.response.data);
                        currentObj.processing = false; // logs an object to the console
                        }else{
                            currentObj.success = null
                            currentObj.processing = false
                            console.log('help')
                            currentObj.close();
                        }
                        // Do something with error data
                    });
        },
        onBeatChange(e) {
            this.beat = e.target.files[0];
            // var audio = document.createElement('audio');
            // console.log(e)
            // audio.src = e.target.result
            // audio.addEventListener('loadedmetadata', function(){
                // Obtain the duration in seconds of the audio file (with milliseconds as well, a float value)
                // this.length = audio.duration;
            
                // example 12.3234 seconds
                // console.log("The duration of the song is of: " + this.length + " seconds");
                // Alternatively, just display the integer value with
                // parseInt(duration)
                // 12 seconds
            // },false);
            // console.log(this.length)
            if(e.target.files[0]){
                this.beatFileName = e.target.files[0].name;
            }else{
                this.beatFileName = '';
            }
            },
		loadImage(event) {
            console.log(event.target.files[0]);
            // this.showPopupCrop = true;
            // console.error('hiiilfe');
            this.cover = event.target.files[0];
            if(event.target.files[0]){
                this.coverFileName = event.target.files[0].name;
            }else{
                this.coverFileName = '';
            }
		},

        NumbersOnly(evt){
            evt = (evt) ? evt : window.event;
            var charCode = (evt.which) ? evt.which : evt.keyCode;
            var dotPlaced = false;
            this.price.split('').forEach(c => {
                    if(c == '.'){
                        dotPlaced = true;
                    }
                });
            if(charCode == 46 && dotPlaced){
                evt.preventDefault();
                return false;
            }
            if(dotPlaced && this.currency == 'USD'){
                if(this.price.split('.')[1].length >= 2){
                    evt.preventDefault();
                    return false;
                }
            }
            if ((charCode > 31 && (charCode < 48 || charCode > 57)) && charCode !== 46) {
                evt.preventDefault();
            } else {
                return true;
            }
        }, 

        changeKey (event) {
            console.log(event.target.value);
            this.selectedKey = event.target.value
            // this.selectedKey = event.target.options[event.target.options.selectedIndex].text
        }, 
        leave(x){
            console.log(this.mouseOpt + '-'+ this.mouseInp)
            if(!this.mouseOpt && !this.mouseInp){
                this.selectFocus = false;
                this.keyClick = false;
            }
            console.log(x)
            var corrKey = false;
            this.keys.forEach(key => {
                // console.log(key +'-'+ this.selectedKey)
                if(this.selectedKey == key){
                    console.log(key)
                    corrKey = true;
                }
            })
            if(!corrKey){
                // this.error.key = ["Please use an existing key."]
            }else if(this.error.key){
                // this.$delete(this.error, 'key')
                // this.deleteError('key')
            }
        },
        mouseOptf(a){
            this.mouseOpt = a;
        },
        mouseInpf(a){
            this.mouseInp = a;
        },
        deleteError(field){
            var temp = this.error;
            if(typeof temp === 'object' && temp !== null){
                // temp.forEach((k, element) => {
                //     console.info(k)
                //     console.warn(element)
                // });
                for (const [key, value] of Object.entries(temp)) {
                    console.log(`${key}: ${value}`);
                }
            }else{
                console.log(temp)
            }
        },
    }, 
    mounted(){
        this.emitter.on("closePopupCrop", state => {
            if(!state){
                this.cover = ''
                this.coverFileName = ''
            }
            this.showPopupCrop = false;
            console.info('cl')
        });
        var audio = document.createElement('audio');
        let diese = this;
        document.getElementById("chooseFile").addEventListener('change', function(event){
            var target = event.currentTarget;
            var file = target.files[0];
            var reader = new FileReader();
        
            if (target.files && file) {
                var reader = new FileReader();

                reader.onload = function (e) {
                    audio.src = e.target.result;
                    audio.addEventListener('loadedmetadata', function(){
                        // Obtain the duration in seconds of the audio file (with milliseconds as well, a float value)
                        var duration = audio.duration;
                        diese.length = audio.duration;
                    
                        // example 12.3234 seconds
                        console.log("The duration of the song is of: " + duration + " seconds");
                        // Alternatively, just display the integer value with
                        // parseInt(duration)
                        // 12 seconds
                    },false);
                };

                reader.readAsDataURL(file);
            }
        }, false); 
        
        // var aud = document.getElementById("chooseFile")
        // if(aud) {
        //     aud.addEventListener('change', function(event){
        //         console.log('yes')
        //         console.log(event)
        //         console.log(event.target.result)
        //     })
        // }
    }
};
</script>
