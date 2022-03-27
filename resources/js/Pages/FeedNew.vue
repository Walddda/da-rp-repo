<template>
    <div>
        <div class="details">
            <mobile></mobile>
        </div>
        <div class="responsive">
            <Head title="Home" />
            <div class="background-image" ref="feedLanding"><!-- Foto von Dmitry Demidov von Pexels -->
                <div v-if="!$page.props.auth.user" class="w-full h-full flex items-center" :style="{backgroundColor: 'rgba(0,0,0,'+backgroundOp+')'}">
                    <div :style="{opacity: (1-backgroundOp)}">
                    <p class="heading-feed">Start selling<br>
                    your beats now.</p>
                    <a href="/register"><button class="cta-main but-main mt-4" style="margin-top:30px">Get started</button></a>
                    </div>
                </div>
                <div v-else class="w-full h-full flex items-center" :style="{backgroundColor: 'rgba(0,0,0,'+backgroundOp+')'}">
                    <div :style="{opacity: (1-backgroundOp)}">
                    <p class="heading-feed">Find exactly<br>
                    what you need.</p>
                    <search :keywords="searchTerm" loc="feed" feed></search>
                    </div>
                </div>
            </div>
            
            <nav-bar-new :backgroundOp="backgroundOp" :searchTerm="searchTerm" feed></nav-bar-new>
            <tracks :attr="{loc: 'feed'}" :givenSearchTerm="searchTerm"></tracks>

            <upload v-if="showPopupUpload && $page.props.auth.user.eth_address"></upload>
            <popup-edit v-if="showPopupEdit" :track="editTrack"></popup-edit>
            <popup-payment v-if="showPopupPayment" :song="paymentTrack"></popup-payment>
            <success v-if="showSuccess" :text="messageSuccess" @close="showSuccess = false" />
            <error v-if="showError" :text="messageError" @close="showError = false" />
        </div>
    </div>
</template>

<script>
import { Head } from "@inertiajs/inertia-vue3";
import axios from 'axios';
import NavBarNew from '@/Components/NavBarNew.vue'
import Upload from '@/Components/PopupUpload.vue'
import PopupPayment from '@/Components/PopupPayment.vue'
import Search from '@/Components/Search.vue';
import Tracks from '@/Components/Tracks.vue';
import PopupEdit from '@/Components/PopupEdit.vue'
import Success from '@/Components/Success.vue';
import Error from '@/Components/Error.vue';
import Mobile from '@/Components/Mobile.vue';


export default {
    components: {
        Head,
        NavBarNew,
        Upload,
        PopupPayment,
        Search,
        Tracks,
        PopupEdit,
        Success,
        Error,
        Mobile,
    },

    props: { 
        paths: Array,
        logedIn: Number,
        givenSearchTerm: {type: String, default: ''},
    },

    data() {
        return {
            playing: false,
            currentPlaying: null,
            showPopupUpload: false,
            showPopupPayment: false,
            paymentTrack: null,
            backgroundOp: 0,
            searchTerm : this.givenSearchTerm,
            showPopupEdit: false,
            editTrack: null,
            showSuccess: false,
            messageSuccess: 'Gongrats',
            showError: false,
            messageError: 'Error',
        }
    },
    created () {
        window.addEventListener('scroll', this.onScroll);
        window.addEventListener('keydown', function(e) {
            if(e.keyCode == 32 && e.target == document.body) {
                e.preventDefault();
            }
            });
    },
    destroyed () {
        window.removeEventListener('scroll', this.onScroll);
    },
    methods: {
        onScroll (event) {
            if(window.scrollY <= this.$refs.feedLanding.scrollHeight - document.getElementById('feedNav').scrollHeight){
                this.backgroundOp = scrollY/(this.$refs.feedLanding.scrollHeight - document.getElementById('feedNav').scrollHeight);
            }else{
                this.backgroundOp = 1;
            }
        },
    },
    mounted() {
        this.emitter.on("closePopupUpload", () => {
            this.showPopupUpload = false;
        });
        this.emitter.on("openPopupUpload", () => {
            this.showPopupUpload = true;
        });
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
        });

        this.emitter.on("success", message => {
            this.messageSuccess = message;
            this.showSuccess = true;
        })

        this.emitter.on("error", message => {
            this.messageError = message;
            this.showError = true;
        })
        
        this.emitter.on('nav-change', e => {
            this.searchTerm = e;
        });
    }
};
</script>


<style scoped>
</style>