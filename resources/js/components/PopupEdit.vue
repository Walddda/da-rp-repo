<template>
<link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">
    <div class="popup-bg-blurr" @click="close">
        <div class="popup-main" @click.stop @click="currencySelect = false">
            <!-- {{$page.props.auth.user}} -->
            <div class="popup-title">Edit</div>
    <!-- <form v-if="logedin" :action="route" method="post" enctype="multipart/form-data">upload -->
            <form class="popup-content main-form" v-if="$page.props.auth.user" @submit="upload()" enctype="multipart/form-data">
                <input type="hidden" name="_token" v-bind:value="$page.props.tokens.csrf" />

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
                            v-model="key"
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
                                <span v-if="!k.toUpperCase().includes(key.toUpperCase())"
                                    @click="key = k; $refs.keyInp.focus(); keyClick = true">
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
                    <div class="main-form-element">
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
                <div class="main-form-row flex flex-col" v-if="error">
                    <span class="main-form-error-msg" v-for="e in error">{{e[0]}}<br></span>
                </div>
            </form>
            <div class="popup-footer">
                <button class="popup-cta back" @click="close()">Back</button>
                <button class="popup-cta submit"
                            :class="{ 'opacity-25': processing }" 
                            :disabled="processing" 
                @click="upload();">Edit</button>
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
            cover: '',
            coverType: '',
            title: this.track.is_beat.title,
            tag: '',
            tags: [],
            bpm: this.track.is_beat.bpm,
            keys: ["C", "Cm", "Db", "C#m", "D", "Dm", "Eb", "D#m", "E", "Em", "F", "Fm", "Gb", "F#m", "G", "Gm", "Ab", "G#m", "A", "Am", "Bb", "A#m", "B", "Bm"],
            type: this.track.is_beat.type,
            key: this.track.is_beat.key,
            description: this.track.is_beat.description,
            feature: this.track.is_beat.feature,
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
        }
    },
    
    props: {
        file1: String,
        file2: String,
        logedin: Number,
        track: Object,
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
			this.emitter.emit("closePopupEdit");
		},
        hide(e){
            e.preventDefault();
            this.isHidden = true;
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
            formData.append('selectedKey', this.key);
            formData.append('type', this.type);
            formData.append('description', this.description);
            formData.append('feature', this.feature);
            
            axios.post('/api/edit/'+this.track.beat_id, formData, config)
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
        convertTags(){
            for (let i = 0; i < 5; i++) {
                if(this.track.is_beat['tag'+i]){
                    this.tags.push(this.track.is_beat['tag'+i])
            }
                
            }
        }
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

        this.convertTags();
    }
};
</script>
