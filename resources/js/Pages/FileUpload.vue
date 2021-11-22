<template>
<link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">

    <div class="container" style="margin-top: 50px">
        <!-- Logedin: {{ logedin }}; -->
        <!-- <form v-if="logedin" :action="route" method="post" enctype="multipart/form-data">upload -->
        <form v-if="logedin" @submit="upload" enctype="multipart/form-data">
            <h3>Upload File</h3>
            
            <transition name="fade">
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
            </transition>

            <input type="hidden" name="_token" v-bind:value="token" />

            <div class="custom-file">
                <input
                    type="text"
                    name="beatTitle"
                    class="custom-text-input"
                    id="title"
                    required
                    v-model="title"
                />
                <label class="custom-text-label" for="title">Title</label>
            </div>

            <div class="block">
            <span class="text-gray-700">Type</span>
            <div class="mt-2">
                <div>
                <label class="inline-flex items-center">
                    <input
                    type="radio"
                    class="form-radio"
                    name="beatType"
                    value="beat"
                    v-model="type"
                    required
                    />
                    <span class="ml-2">Beat</span>
                </label>
                </div>
                <div>
                <label class="inline-flex items-center">
                    <input
                    type="radio"
                    class="form-radio"
                    name="beatType"
                    value="sample"
                    v-model="type"
                    />
                    <span class="ml-2">Sample</span>
                </label>
                </div>
            </div>
            </div>


            <div class="">
                <input
                    type="text"
                    name="beatBPM"
                    class="custom-text-input"
                    id="bpm"
                    required
                    v-model="bpm"
                    v-on:keypress="NumbersOnly"
                />
                <label class="custom-text-label" for="title">BPM</label>
            </div>

            <div>
                <select class="form-control" @change="changeKey($event)">
                    <option value="" selected disabled>Select Key</option>
                    <option id="keyVal" v-for="k in keys" :value="k" :key="k">{{ k }}</option>
                </select>
            </div>

            <div class="custom-file">
                <input
                    type="file"
                    name="file"
                    class="custom-file-input"
                    id="chooseFile"
                    accept="audio/mp3"
                    v-on:change="onBeatChange"
                    
                />
                <label class="custom-file-label" for="chooseFile"
                    >Select Beat</label
                >
            </div>
            <!-- <div class="custom-file">
                <input
                    type="file"
                    name="file2"
                    class="custom-file-input"
                    id="chooseFile"
                />
                <label class="custom-file-label" for="chooseFile"
                    >Select Cover</label
                >
            </div> -->

            <div class="flex mt-6">
                <label class="flex items-center">
                    <input type="checkbox" class="form-checkbox" v-model="coverType" name="coverType">
                    <span class="ml-2">I wish to use a <span class="underline">custom cover</span></span>
                </label>
            </div>

            <div v-if="coverType" class="upload-example">
                <div class="button-wrapper">
                        <input type="file" ref="file" name="file2" @change="loadImage($event)" accept="image/*" required>
                        Load image
                </div>
                <cropper
                    ref="cropper"
                    class="upload-example-cropper max-w-7xl max-h-7xl block overflow-hidden"
                    imageClass="cropImg"
                    :stencil-props="{
                        aspectRatio: 10/10
                    }"
                    :src="image.src"
                />
            </div>

            <div>
                <vue-tags-input
                v-model="tag"
                :tags="tags"
                @tags-changed="newTags => tags = newTags"
                :max-tags="5"
                />
            </div>

            <div class="flex flex-wrap -mx-3 mb-6">
                <div class="w-full px-3">
                    <label class="block uppercase tracking-wide text-gray-700 text-xs font-bold mb-2" for="description">
                        Description
                    </label>
                    <textarea v-model="description" name="description" class="no-resize appearance-none block w-full bg-white-200 text-gray-700 border border-gray-200 rounded py-3 px-4 mb-3 leading-tight focus:outline-none focus:bg-white focus:border-gray-500 h-48 resize-none" id="description"></textarea>
                </div>
            </div>

            <input type="submit" name="submit" />
            <!-- <button @click="upload">Axios-Upload</button> -->
            <!-- <cropper
                class="cropper"
                src="/storage/covers/placeholder.jpg"
                :stencil-props="{
                aspectRatio: 10/10
                }"
                @change="change"
            ></cropper> -->
            <div class="loader ease-linear rounded-full border-4 border-t-4 border-gray-200 h-12 w-12" v-if="loading"></div>
        </form>

        <div v-else>
            Please log in in order to upload <br>
            <a href="/login">Login</a> or 
            <a href="/register" >Register</a>
        </div>

        
</div>
</template>
<script>
import { Head } from "@inertiajs/inertia-vue3";
import VueTagsInput from "@sipec/vue3-tags-input";
import { Cropper } from 'vue-advanced-cropper'
import 'vue-advanced-cropper/dist/style.css';

function getMimeType(file, fallback = null) {
	const byteArray = (new Uint8Array(file)).subarray(0, 4);
    let header = '';
    for (let i = 0; i < byteArray.length; i++) {
       header += byteArray[i].toString(16);
    }
	switch (header) {
        case "89504e47":
            return "image/png";
        case "47494638":
            return "image/gif";
        case "ffd8ffe0":
        case "ffd8ffe1":
        case "ffd8ffe2":
        case "ffd8ffe3":
        case "ffd8ffe8":
            return "image/jpeg";
        default:
            return fallback;
    }
}


export default {
    components: {
        VueTagsInput,
        Cropper,

    },
    data() {
        return {
            // route: "{{route('fileUpload')}}",
            image: {
				src: null,
				type: null
			},
            beat: '',
            cover: '',
            coverType: '',
            title: 'testTitle',
            tag: '',
            tags: [],
            bpm: '1232',
            keys: ["C", "Cm", "Db", "C#m", "D", "Dm", "Eb", "D#m", "E", "Em", "F", "Fm", "Gb", "F#m", "G", "Gm", "Ab", "G#m", "A", "Am", "Bb", "A#m", "B", "Bm"],
            selectedKey: '',
            type: 'beat',
            description: '',
            error: '',
            success: null,
            isHidden: false,
            loading: false,
        }
    },
    
    props: {
        route: String,
        token: String,
        file1: String,
        file2: String,
        logedin: Number,
    },
    methods: {
        hide(e){
            e.preventDefault();
            this.isHidden = true;
        },
        upload(e){
            console.log('lezzgo')
            this.loading = true;
            e.preventDefault();
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
            formData.append('userID', this.logedin);
            formData.append('bpm', this.bpm);
            formData.append('tagsArr', tagsFin);
            formData.append('selectedKey', this.selectedKey);
            formData.append('type', this.type);
            formData.append('description', this.description);

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
        change({coordinates, canvas}) {
            console.log(coordinates, canvas)
        },
        crop() {
			const { canvas } = this.$refs.cropper.getResult();
			canvas.toBlob((blob) => {
				// Do something with blob: upload to a server, download and etc.
                console.log('???');
			}, this.image.type);
		},
		reset() {
			this.image = {
				src: null,
				type: null
			}
		},
		loadImage(event) {
            this.cover = event.target.files[0];
			// Reference to the DOM input element
			const { files } = event.target;
			// Ensure that you have a file before attempting to read it
			if (files && files[0]) {
				// 1. Revoke the object URL, to allow the garbage collector to destroy the uploaded before file
				if (this.image.src) {
					URL.revokeObjectURL(this.image.src)
				}
				// 2. Create the blob link to the file to optimize performance:
				const blob = URL.createObjectURL(files[0]);
				
				// 3. The steps below are designated to determine a file mime type to use it during the 
				// getting of a cropped image from the canvas. You can replace it them by the following string, 
				// but the type will be derived from the extension and it can lead to an incorrect result:
				//
				// this.image = {
				//    src: blob;
				//    type: files[0].type
				// }
				
				// Create a new FileReader to read this image binary data
				const reader = new FileReader();
				// Define a callback function to run, when FileReader finishes its job
				reader.onload = (e) => {
					// Note: arrow function used here, so that "this.image" refers to the image of Vue component
					this.image = {
						// Set the image source (it will look like blob:http://example.com/2c5270a5-18b5-406e-a4fb-07427f5e7b94)
						src: blob,
						// Determine the image type to preserve it during the extracting the image from canvas:
						type: getMimeType(e.target.result, files[0].type),
					};
				};
				// Start the reader job - read file as a data url (base64 format)
				reader.readAsArrayBuffer(files[0]);
			}
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

        getType($file){
            //chooseFile
        }
    },

	destroyed() {
		// Revoke the object URL, to allow the garbage collector to destroy the uploaded before file
		if (this.image.src) {
			URL.revokeObjectURL(this.image.src)
		}
    }, 
};
</script>

<style>
.cropImg{
    border: 2px solid green;
}

.loader {
  border-top-color: #3498db;
  -webkit-animation: spinner 1.5s linear infinite;
  animation: spinner 1.5s linear infinite;
}

@-webkit-keyframes spinner {
  0% { -webkit-transform: rotate(0deg); }
  100% { -webkit-transform: rotate(360deg); }
}

@keyframes spinner {
  0% { transform: rotate(0deg); }
  100% { transform: rotate(360deg); }
}

.fade-enter-active, .fade-leave-active {
  transition: opacity .3s;
}
.fade-enter, .fade-leave-to /* .fade-leave-active below version 2.1.8 */ {
  opacity: 0;
}


</style>