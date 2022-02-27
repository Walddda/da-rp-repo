<template>
    <div>

        
        <!-- <BreezeValidationErrors class="mb-4" /> -->
        <div class="main-profile-content">
            <!-- <wallet></wallet> -->
            <div>
                <img src="/storage/assets/PROFILE_BG_cut1.jpg" class="profile-bg"/>
                <div 
                    class="profile-avatar" 
                    :class="[toggleScrollView ? 'toggleScrollView' : '']"
                    :style="[!toggleScrollView 
                    ? {
                        'width': ((400 - scrollY)/20)+'vw',
                        'left': 'calc( 50vw - '+ ((400 - scrollY)/40)+'vw',
                        'padding': (-0.0025 * scrollY +1)+'vw',
                        'margin-top': (-0.0125 * scrollY +5)+'%',
                        }
                    : {'width': '10vw', 'padding':'0.5vw',
                    'margin-top': '2.5%'} ]"
                >
                    <img src="/storage/covers/1638883713_1_2631819_15.png"/>
                </div>
            </div>
            <div class="main-scroll-username" v-if="toggleScrollView">{{headUsername}}</div>
            <!-- <svg viewBox="0 0 100 100" class="main-scroll-username">
                <defs>
                    <linearGradient id="linear-gradient" gradientUnits="userSpaceOnUse">
                        <stop offset="0.1" stop-color="#8f35e8" />
                        <stop offset="0.9" stop-color="#e20000" />
                    </linearGradient>
                </defs>
                <text x="0" y="10" style="fill:url(#linear-gradient)">{{headUsername}}</text>
            </svg> -->
            
            <div class="profile-details flex flex-row justify-between">
                <div class="profile-details-left">
                    <label class="pd-username" v-if="!toggleScrollView" >{{form.username}}
                        <a v-if="own" href="/settings"><CogIcon class="h-6 w-6" aria-hidden="true"/></a>
                    </label>
                    <br>
                    <label class="pd-email">{{form.email}} </label> <br><br>
                    <label class="pd-location">{{form.location}} </label> <br>
                    <label class="pd-joined">joined {{form.joined}} </label>
                </div>
                <div class="profile-details-right">
                    <label class="pd-desc">{{form.description}} </label>
                </div>
            </div>

            <!-- {{userData.username}} -->

            <!-- MyProfile, sehen andere nicht -->
            <div v-if="own" class="main-toggle-own-tracks flex justify-center">
                <div class="flex justify-center">
                    <!-- <a href="/settings">Settings</a><br>
                    <a href="/settings"><CogIcon class="h-6 w-6" aria-hidden="true"/></a> -->
                    <button @click="purchased = !purchased; myTracks = !myTracks" class="toggle-option left" :class="[purchased ? 'active' : '']">purchased tracks</button>
                    <button @click="myTracks = !myTracks; purchased = !purchased" class="toggle-option right" :class="[myTracks ? 'active' : '']">my tracks</button>
                </div>
            </div>
            
            <div v-if="myTracks">
                <tracks :attr="{loc: 'prof', id: form.id}"></tracks>
            </div>

            <div v-if="purchased">
                <li v-for="(x, k) in purchasedFiles">
                    <player v-if="k == currentPlaying-1" :track="x" :numb="k+1" current/>
                    <player v-else :track="x" :numb="k+1"/>
                </li>
            </div>
            
            <popup-edit v-if="showPopupEdit" :track="editTrack"></popup-edit>
        </div>
        <nav-bar-new profile/>
    </div>
</template>

<script>
import { Head, Link } from '@inertiajs/inertia-vue3';
import Wallet from '@/Components/Wallet.vue';
import { CogIcon } from '@heroicons/vue/outline';
import NavBarNew from '@/Components/NavBarNew.vue'
import Tracks from '@/Components/Tracks.vue';
import Player from '@/Components/Player.vue';
import PopupEdit from '@/Components/PopupEdit.vue'


const countries = require('i18n-iso-countries')
countries.registerLocale(require('i18n-iso-countries/langs/en.json'))

export default {
    
    components: {
        Head,
        Link,
        NavBarNew,
        Wallet,
        CogIcon,
        Tracks,
        Player,
        PopupEdit,
    },

    props: {
        userData: Object,
    },

    computed: {
        countries(){
        }
    },


    data(){
        return{
            form: this.$inertia.form({
                username: this.userData.username,
                first_name: this.userData.first_name,
                last_name: this.userData.last_name,
                email: this.userData.email,
                location: this.dataLabel(this.userData.location),
                description: this.userData.description,
                joined: this.joinedReadable(this.userData.created_at),
                password: this.userData.password,
                password_confirmation: this.userData.password_confirmation,
                id: this.userData.id,
            }),
            own: this.currentUser(this.userData.id),
            myTracks: true,
            purchased: false,
            files: null,
            allFiles: null,
            transactions: [],
            purchasedTracks: [],
            purchasedFiles: [],
            showPopupEdit: false,
            editTrack: null,
            toggleScrollView: false,
            scrollY: 0,
            headUsername: this.userData.username.substring(0,15),
        }
    },

    methods: {
        onScroll (event) {
            console.log(window.scrollY);
            this.scrollY = window.scrollY;
            if(window.scrollY <= 200){
                this.toggleScrollView = false;
            }else{
                this.toggleScrollView = true;
            }
            // console.log(this.backgroundOp)
        },
        submit() {
            this.form.post(this.route('myprofile'), {
                onFinish: () => this.form.reset('password', 'password_confirmation'),
            })
        }, 
        joinedReadable(x){
            return x.split('T')[0]
        },
        currentUser(x){
            if(this.$page.props.auth.user){
                return this.$page.props.auth.user.id == x
            } return false
        },
        dataLabel(x){
            // console.log('hey')
            // const list = countries.getNames('en', { select: 'official' })
            // console.log(Object.keys(list).map((key) => ({ value: key, label: list[key] })))
            // return Object.keys(list).map((key) => ({ value: key, label: list[key] }))[x]
            return countries.getName(x, "en", {select: "alias"})
        },
        getSongs(){
            axios.get('/api/beats', { params: { user: this.form.id } })
            .then(response => {
                if(!response.data.length){
                    console.info('empty')
                }else{
                    this.files = response.data
                    console.info('finish: ');
                    //console.info(response.data);

                }
            })
        },
        getTransactions() {
            axios.get('/api/beats')
            .then(response => {
                if(!response.data.length){
                    console.info('empty')
                }else{
                    this.allFiles = response.data
                    //console.info('finish: ');
                    //console.info(response.data);
                }

                return axios.get('/api/transactions')
                    .then(response => {
                    this.transactions = response.data
                    this.transactions.forEach((transaction) => {
                        if (transaction.buyer_id === this.$page.props.auth.user.id) {
                        this.purchasedTracks.push(transaction)
                        }
                    })

                    

                    this.allFiles.forEach((file) => {
                        this.purchasedTracks.forEach((track) => {
                            if (file.beat_id == track.beat_id) {
                                this.purchasedFiles.push(file)
                            }
                        })
                    })
                    })
                    .catch((error) => console.error)
                })
        },
    },
    created(){
        this.getSongs()
        this.getTransactions()
        window.addEventListener('scroll', this.onScroll);
    },
    mounted() {
        this.emitter.on("closePopupEdit", () => {
            this.showPopupEdit = false;
            console.info('cl')
        });

        this.emitter.on("openPopupEdit", track => {
            console.log(track)
            this.showPopupEdit = true;
            this.editTrack = track;
            console.info('op')
        });

    },
    destroyed () {
        window.removeEventListener('scroll', this.onScroll);
    },

}
</script>

<style>
input{
    width:300px;
}
</style>