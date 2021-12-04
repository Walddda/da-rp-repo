<template>
<link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">
    <div class="popup-bg-blurr" >
        <div class="popup-main" >
            <!-- {{$page.props.auth.user}} -->
            <div class="popup-title">Upload</div>
    <!-- <form v-if="logedin" :action="route" method="post" enctype="multipart/form-data">upload -->
            <form class="popup-content main-form" v-if="$page.props.auth.user" @submit="upload()" v-on:keyup.enter="upload" enctype="multipart/form-data">
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
                            :class="[error.title && error.title[0].includes('required') && !title ? 'error' : '', 'custom-text-input main-text-input']"
                            id="title"
                            required
                            v-model="title"
                        />
                    </div>

                    <div class="main-form-element ein-fünftel flex flex-col items-end justify-end ">
                        <label class="inline-flex items-center">
                            <span class="ml-2">Beat </span>
                            <input
                            type="radio"
                            class="form-radio main-form-radio"
                            :class="[error.type && !type ? 'error' : '']"
                            name="beatType"
                            value="beat"
                            v-model="type"
                            required
                            />
                        </label><br>
                        <label class="inline-flex items-center">
                            <span class="ml-2">Sample </span>
                            <input
                            type="radio"
                            class="form-radio main-form-radio"
                            :class="[error.type && !type ? 'error' : '']"
                            name="beatType"
                            value="sample"
                            v-model="type"
                            />
                        </label>
                    </div>
                </div>

                <div class="main-form-row">
                    <div class="main-form-element">
                        <label class="custom-text-label" for="feature">Featured Artists</label><br>
                            <input
                            type="text"
                            name="featArtist"
                            :class="[error.feature ? 'error' : '', 'custom-text-input main-text-input']"
                            id="feature"
                            v-model="feature"
                        />
                    </div>
                </div>

                <div class="main-form-row">
                    <div class="main-form-element half">  
                        <label class="custom-text-label" for="bpm">BPM</label><br>
                        <input
                            type="text"
                            name="beatBPM"
                            :class="[error.bpm && error.bpm[0].includes('required') && !bpm ? 'error' : '', 'custom-text-input main-text-input']"
                            id="bpm"
                            required
                            v-model="bpm"
                            v-on:keypress="NumbersOnly"
                        />
                    </div>

                    <div class="main-form-element half">
                        <label class="custom-text-label" for="key">Key</label><br>
                        <select :class="[error.key ? 'error' : '','form-control main-select-input']" id="key" @change="changeKey($event)">
                            <option value="" selected></option>
                            <option id="keyVal" v-for="k in keys" :value="k" :key="k">{{ k }}</option>
                        </select>
                    </div>
                </div>
                
                <div class="main-form-row">
                    <div class="main-form-element">
                        <label class="custom-text-label" for="description">Description</label><br>
                            <input
                            type="text"
                            name="description"
                            :class="[error.desc ? 'error' : '', 'custom-text-input main-text-input']"
                            id="description"
                            v-model="description"
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
                        :class="[error.tags ? 'error' : '', 'main-tag-input']"
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
                        <label class="custom-text-label" for="chooseFile">Track</label><br>
                        <label 
                            v-if="beat" 
                            class="main-file-label-box checked mt-2" 
                            :class="[error.beat && error.beat[0].includes('required') && !beat ? 'error' : '']"
                            for="chooseFile"
                        ></label>
                        <label 
                            v-else 
                            class="main-file-label-box mt-2" 
                            :class="[error.beat && error.beat[0].includes('required') && !beat ? 'error' : '']"
                            for="chooseFile"
                        ></label>
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
                            <input type="checkbox" class="form-checkbox main-form-checkbox" v-model="coverType" name="coverType">
                        </div>
                        <label 
                            v-if="cover && coverType" 
                            class="main-file-label-box checked mt-2" 
                            :class="[error.bpm ? 'error' : '']"
                            for="cover"
                        ></label>
                        <label 
                            v-if="!cover && coverType" 
                            class="main-file-label-box mt-2" 
                            :class="[error.bpm ? 'error' : '']"
                            for="cover"
                        ></label>
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
                <button class="popup-cta submit" @click="upload()">Upload</button>
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
            beat: '',
            cover: '',
            coverType: '',
            title: 'testTitle',
            tag: '',
            tags: [],
            bpm: '1232',
            keys: ["C", "Cm", "Db", "C#m", "D", "Dm", "Eb", "D#m", "E", "Em", "F", "Fm", "Gb", "F#m", "G", "Gm", "Ab", "G#m", "A", "Am", "Bb", "A#m", "B", "Bm"],
            selectedKey: '',
            type: '',
            description: '',
            feature: '',
            error: '',
            success: null,
            isHidden: false,
            loading: false,
            coverCheckBool: false,
            errors: {title: '', bpm: ''},
        }
    },
    
    props: {
        file1: String,
        file2: String,
        logedin: Number,
    },
	methods: {
        focus(bool){
            this.emitter.emit("focus", bool)
        },
		close() {
			console.log('yaay');
			this.emitter.emit("closePopupUpload");
		},
        hide(e){
            e.preventDefault();
            this.isHidden = true;
        },
        // submit(){
        //     this.upload(document.getElementById("upload-form"));       
        // },
        upload(e){
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


            axios.post('/api/upload', formData, config)
                    .then(function (response) {
                        currentObj.loading = false;
                        currentObj.isHidden = false;
                        console.log(response);
                        if (response.data.error) {
                            currentObj.error = response.data.error
                            currentObj.success = null
                            console.log(currentObj.error)
                        }else if (response.data.success) {
                            currentObj.emitter.emit('upload-success');
                            currentObj.success = response.data.success
                            currentObj.error = null
                            console.log(currentObj.success)
                        }
                    })
                    .catch(error => {
                        currentObj.error = error.response.data.errors
                        currentObj.success = null
                        console.log(error.response.data); // logs an object to the console

                        // Do something with error data
                    });
        },
        onBeatChange(e) {
                //console.log(e.target.files[0]);
                this.beat = e.target.files[0];
            },
		loadImage(event) {
            console.error('hiiilfe');
            this.cover = event.target.files[0];
		},

        NumbersOnly(evt){
            evt = (evt) ? evt : window.event;
            var charCode = (evt.which) ? evt.which : evt.keyCode;
            if ((charCode > 31 && (charCode < 48 || charCode > 57)) && charCode !== 46) {
                evt.preventDefault();;
            } else {
                return true;
            }
        }, 

        changeKey (event) {
            console.log(event.target.value);
            this.selectedKey = event.target.value
            // this.selectedKey = event.target.options[event.target.options.selectedIndex].text
        }, 
    }, 
};
</script>
