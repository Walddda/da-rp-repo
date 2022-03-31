<template>
    <div>
        <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">
        <div class="popup-bg-blurr" @click="close">
            <div class="popup-main" @click.stop @click="currencySelect = false">
                <div class="popup-title">Edit</div>
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
        </div>
    </div>
</template>


<script>
import VueTagsInput from "@sipec/vue3-tags-input";

export default {

    name: 'PopupUpload',

    components: {
        VueTagsInput,
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
            //console.log(e)
            e.preventDefault();
        },
		close() {
			
			this.emitter.emit("closePopupEdit");
		},
        hide(e){
            e.preventDefault();
            this.isHidden = true;
        },
        upload(e){
            this.processing = true;
            if(e){
                e.preventDefault();
            }
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
                        
                        if (response.data.error) {
                            currentObj.error = response.data.error
                            currentObj.success = null
                            currentObj.processing = false;
                            //console.log(currentObj.error)
                        }else if (response.data.success) {
                            currentObj.emitter.emit('success', 'Your Track was successfully edited.')
                            currentObj.success = response.data.success
                            currentObj.error = null
                            //console.log(currentObj.success)
                            currentObj.emitter.emit('upload-success');
                            currentObj.close();
                        }
                    })
                    .catch(error => {
                        if(error.response){
                        //console.log(error)
                        currentObj.error = error.response.data.errors
                        currentObj.success = null
                        
                        currentObj.processing = false; // logs an object to the console
                        }else{
                            currentObj.success = null
                            currentObj.processing = false
                            //console.log('help')
                            currentObj.close();
                        }
                        // Do something with error data
                    });
                    
        },

		loadImage(event) {
            
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
            this.selectedKey = event.target.value
        }, 
        leave(x){
            if(!this.mouseOpt && !this.mouseInp){
                this.selectFocus = false;
                this.keyClick = false;
            }
            //console.log(x)
            var corrKey = false;
            this.keys.forEach(key => {
                if(this.selectedKey == key){
                    corrKey = true;
                }
            })
            if(!corrKey){
            }else if(this.error.key){
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
                for (const [key, value] of Object.entries(temp)) {
                    
                }
            }else{
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
        var audio = document.createElement('audio');
        let diese = this;

        this.convertTags();
    }
};
</script>
