<template>
    <div class="container" style="margin-top: 50px">
        <!-- Logedin: {{ logedin }}; -->
        <!-- <form v-if="logedin" :action="route" method="post" enctype="multipart/form-data">upload -->
        <form v-if="logedin" @submit="upload" enctype="multipart/form-data">
            <h3>Upload File</h3>
            <div v-if="success !== ''" class="alert alert-success" role="alert">
                {{ success }}
            </div>
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

            <div class="custom-file">
                <input
                    type="file"
                    name="file"
                    class="custom-file-input"
                    id="chooseFile"
                    accept="audio/*"
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
            <div class="upload-example">
                <div class="button-wrapper">
                        <input type="file" ref="file" name="file2" @change="loadImage($event)" accept="image/*">
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

            <!-- <div>
                <vue-tags-input
                v-model="tag"
                :tags="tags"
                @tags-changed="newTags => tags = newTags"
                :max-tags="5"
                />
            </div> -->

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
            title: '',
        };
    },
    props: {
        route: String,
        token: String,
        success: String,
        file1: String,
        file2: String,
        logedin: Number,
    },
    methods: {
        upload(e){
            console.log('lezzgo')
            e.preventDefault();
                let currentObj = this;
                const config = {
                    headers: {
                        'content-type': 'multipart/form-data',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                    }
                }

            let formData = new FormData();
            formData.append('beat', this.beat);
            formData.append('cover', this.cover);
            formData.append('title', this.title);
            formData.append('userID', this.logedin);

            axios.post('/api/upload', formData, config)
                    .then(function (response) {
                        console.log(response);
                        // currentObj.success = response.data.success;
                        // currentObj.filename = "";
                    })
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
</style>