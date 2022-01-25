<template>
    <div>



        <nav-bar-new profile/>
        <!-- <BreezeValidationErrors class="mb-4" /> -->
        <div class="main-profile-content">
            <!-- <wallet></wallet> -->
            <div>
                <img src="/storage/assets/PROFILE_BG.jpg" class="profile-bg"/>
                <div class="profile-avatar">
                    <img src="/storage/covers/1638883713_1_2631819_15.png"/>
                </div>
            </div>
    
            <div class="profile-details">
                <label class="pd-username">{{form.username}} <a v-if="own" href="/settings"><CogIcon class="h-6 w-6" aria-hidden="true"/></a></label>
                <br>
                <label class="pd-email">{{form.email}} </label> <br>
                <label class="pd-location">{{form.location}} </label> <br>
                <label class="pd-joined">joined {{form.joined}} </label> <br>
                <label class="pd-desc">{{form.description}} </label> <br>
            </div>

            <!-- {{userData.username}} -->

            <!-- MyProfile, sehen andere nicht -->
            <div v-if="own">
                <a href="/settings">Settings</a><br>
                <a href="/settings"><CogIcon class="h-6 w-6" aria-hidden="true"/></a>
                <button>Purchased Tracks</button>
            </div> 
            
            <tracks :attr="{loc: 'feed'}"></tracks>

            <li v-for="(x, k) in purchasedFiles">
                <player v-if="k == currentPlaying-1" :track="x" :numb="k+1" current/>
                <player v-else :track="x" :numb="k+1"/>
            </li>
            
        </div>
    </div>
</template>

<script>
import { Head, Link } from '@inertiajs/inertia-vue3';
import Wallet from '@/Components/Wallet.vue';
import { CogIcon } from '@heroicons/vue/outline';
import NavBarNew from '@/Components/NavBarNew.vue'
import Tracks from '@/Components/Tracks.vue';
import Player from '@/Components/Player.vue';


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
            files: null,
            allFiles: null,
            transactions: [],
            purchasedTracks: [],
            purchasedFiles: [],
        }
    },

    methods: {
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
    },

}
</script>

<style>
input{
    width:300px;
}
</style>