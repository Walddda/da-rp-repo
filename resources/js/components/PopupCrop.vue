<template>
<link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">
    <div class="popup-bg-blurr" @click="close">
        <div class="popup-main" @click.stop @click="currencySelect = false">
            <div class="popup-title">Crop Cover</div>
            <form class="popup-content main-form" v-if="$page.props.auth.user" @submit="upload()" enctype="multipart/form-data">
                <input type="hidden" name="_token" v-bind:value="$page.props.tokens.csrf" />
                
                <!-- <clipper-basic class="my-clipper" src="/storage/assets/BEATCHAIN_BACKGR.png" /> -->

                <div class="main-form-row">
                    <div class="main-form-element vier-fünftel">
                        
                    </div>

                    <div class="main-form-element ein-fünftel flex flex-col items-end justify-end ">
                        
                    </div>
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
    </div>
</template>

<script>
import VueTagsInput from "@sipec/vue3-tags-input";
// import vSelect from 'vue-select';

// import VueRx from "vue-rx";
// // Use build files
// import VuejsClipper from "vuejs-clipper/dist/vuejs-clipper.umd";
// import { clipperBasic } from 'vuejs-clipper';
// import "vuejs-clipper/dist/vuejs-clipper.css";

export default {

    name: 'PopupCrop',

    components: {
    },

    data() {
        return {
            processing: false,
        }
    },
    
    props: {
        img: File,
    },
	methods: {
        close() {
			console.log('yaay');
			this.emitter.emit("closePopupCrop");
		},
    }, 
};
</script>
