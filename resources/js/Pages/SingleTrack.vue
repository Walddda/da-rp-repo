<template> 
    <div>
        <div class="details">
            <mobile></mobile>
        </div>
        <div class="responsive">
            <Head title="Track" />
            <nav-bar-new></nav-bar-new>
            <tracks :attr="{loc: 'single', id: id}"></tracks>
            <popup-edit v-if="showPopupEdit" :track="editTrack"></popup-edit>
            <popup-payment v-if="showPopupPayment" :song="paymentTrack"></popup-payment>
            <success v-if="showSuccess" :text="messageSuccess" @close="showSuccess = false" />
            <error v-if="showError" :text="messageError" @close="showError = false" />
            <upload v-if="showPopupUpload && $page.props.auth.user.eth_address"></upload>
        </div>
    </div>
</template>

<script>
import { Head, Link } from "@inertiajs/inertia-vue3";
import Tracks from '@/Components/Tracks.vue';
import PopupEdit from '@/Components/PopupEdit.vue'
import Mobile from '@/Components/Mobile.vue';
import NavBarNew from '@/Components/NavBarNew.vue'
import Success from '@/Components/Success.vue';
import Error from '@/Components/Error.vue';
import PopupPayment from '@/Components/PopupPayment.vue'
import Upload from '@/Components/PopupUpload.vue'

export default {
    components: {
        Head,
        Link,
        Mobile,
        NavBarNew,
        Tracks,
        Success,
        Error,
        PopupEdit,
        PopupPayment,
        Upload,
    },

    props: {
        id: String,
        beats: Array,
        paths: Array,
        logedIn: Number,
    },

    data() {
        return {
            showPopupEdit: false,
            showPopupPayment: false,
            editTrack: null,
            paymentTrack: null,
            showSuccess: false,
            messageSuccess: 'Gongrats',
            showError: false,
            messageError: 'Error',
            showPopupUpload: false,
        };
    },

    methods: {
    },
    mounted(){
        this.emitter.on("success", message => {
            this.messageSuccess = message;
            this.showSuccess = true;
        })

        this.emitter.on("error", message => {
            
            this.messageError = message;
            this.showError = true;
        })

        this.emitter.on("closePopupPayment", () => {
            this.showPopupPayment = false;
        });
        this.emitter.on("openPopupPayment", track => {
            this.paymentTrack = track;
            this.showPopupPayment = true;
        });
        
        this.emitter.on("focus", bool =>{
            this.searchFocus = bool;
        })
        this.emitter.on("closePopupEdit", () => {
            this.showPopupEdit = false;
        });

        this.emitter.on("openPopupEdit", track => {
            
            this.showPopupEdit = true;
            this.editTrack = track;
            console.info('op')
        });
        
        this.emitter.on("closePopupUpload", () => {
            this.showPopupUpload = false;
            console.info('cl')
        });
        this.emitter.on("openPopupUpload", () => {
            this.showPopupUpload = true;
            console.info('op')
        });
    }
};
</script>

