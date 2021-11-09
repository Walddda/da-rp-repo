<template>
    <div class="bg-blurr" @click="close()">
        <div class="popup" @click.stop>
            {{$page.props.auth.user}}
            <form @submit="upload" enctype="multipart/form-data">
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
            <div class="upload-example">
                <div class="button-wrapper">
                        <input type="file" ref="file" name="file2" @change="onCoverChange" accept="image/*">
                        Load image
                </div>
                <!-- <cropper
                    ref="cropper"
                    class="upload-example-cropper max-w-7xl max-h-7xl block overflow-hidden"
                    imageClass="cropImg" 
                    :stencil-props="{
                        aspectRatio: 10/10
                    }"
                    :src="image.src"
                /> -->
            </div>

            <input type="submit" name="submit" />
        </form>
        </div>
    </div>
</template>

<script>
export default {

    name: 'PopupUpload',

    components: {
    },

    props: {
        route: String,
        token: String,
        success: String,
        file1: String,
        file2: String,
    },

    data() {
      return {
            beat: '',
            cover: '',
            title: '',
        logedin: this.$page.props.auth.user.id,
      }
    },
	methods: {
		close() {
			console.log('yaay');
			this.emitter.emit("closePopupUpload");
		},
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
        onCoverChange(e) {
                //console.log(e.target.files[0]);
                this.cover = e.target.files[0];
            },
    }
};
</script>

<style scoped>
.bg-blurr { 
    width: 100vw;
    height: 100vh;
    background-color:rgba(128, 128, 128, 0.233);
    position:fixed;
    top:0;
    display: flex;
    align-items: center;
    justify-content: center;
}
.popup{
    background-color: white;
    padding: 50px;
    border:black 1px solid;
    border-radius: 10%;
}
</style>