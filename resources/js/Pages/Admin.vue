<template>
    <div>
        <div class="details">
            <mobile></mobile>
        </div>
        <div class="responsive">
            <div class="main-toggle-own-tracks flex justify-center">
                <div class="flex justify-center">
                    <button @click="songs = !songs; user = !user; loadSongs()" class="toggle-option left" :class="[songs ? 'active' : '']">Songs</button>
                    <button @click="user = !user; songs = !songs; loadUser()" class="toggle-option right" :class="[user ? 'active' : '']">User</button>
                </div>
            </div>
            <div>
                <div class="fixed right-0 top-0 w-1/3 bg-red-300 h-full border-l-4 pl-5">
                    <div v-if="selectedSong && songs">
                        <form class="popup-content main-form" v-if="editS" @submit="upload()" enctype="multipart/form-data" >
                            <input type="hidden" name="_token" v-bind:value="$page.props.tokens.csrf" />

                            <label class="custom-text-label" for="title">Title</label><br>
                            <input
                                type="text"
                                name="beatTitle"
                                id="title"
                                required
                                v-model="selectedSong.is_beat.title"
                            />

                            <label class="inline-flex items-center">
                                <span class="ml-2">Beat </span>
                                <input
                                type="radio"
                                name="beatType"
                                value="beat"
                                v-model="selectedSong.is_beat.type"
                                required
                                />
                            </label>
                            <label class="inline-flex items-center">
                                <span class="ml-2">Sample </span>
                                <input
                                type="radio"
                                name="beatType"
                                value="sample"
                                v-model="selectedSong.is_beat.type"
                                />
                            </label>
                            <br>
                            <label class="custom-text-label" for="bpm">BPM</label><br>
                            <input
                                type="text"
                                name="beatBPM"
                                id="bpm"
                                required
                                v-model="selectedSong.is_beat.bpm"
                                v-on:keypress="NumbersOnly"
                                
                            />

                            <label class="custom-text-label" for="key">Key</label><br>
                            <input
                                type="text"
                                name="key"
                                id="key"
                                required
                                v-model="selectedSong.is_beat.key"
                                ref="keyInp"
                            />
                            <label class="custom-text-label" for="description">Description</label><br>
                                <input
                                type="text"
                                name="description"
                                id="description"
                                v-model="selectedSong.is_beat.description"
                            />
                            <label class="custom-text-label" for="feature">Featured Artists</label><br>
                                <input
                                type="text"
                                name="featArtist"
                                id="feature"
                                v-model="selectedSong.is_beat.feature"
                            />
                            <label class="custom-text-label" for="tags">Tags</label><br>
                            <vue-tags-input
                            v-model="selectedSong.is_beat.tag"
                            :tags="tags"
                            @tags-changed="newTags => tags = newTags"
                            :max-tags="5"
                            placeholder=""
                            id="tags"
                            >
                            </vue-tags-input>
                            <input type="submit"
                                :class="{ 'opacity-25': processing }" 
                                :disabled="processing" @click="upload" value="Edit" />
                        </form>
                        <form class="popup-content main-form" v-if="deleteS" enctype="multipart/form-data" >
                                <input type="hidden" name="_token" v-bind:value="$page.props.tokens.csrf" />
                                    <u>Möchtest du diesen Track wirklich löschen?</u><br>
                                    <b>{{selectedSong.is_beat.title}} - {{selectedSong.is_beat.from_user.username}}</b>
                                    <input type="submit"
                                        :class="{ 'opacity-25': processing }" 
                                        :disabled="processing" @click="deleteSubmit" value="Delete" />
                        </form>
                    </div>
                    <div v-if="selectedUser && user">
                        <form class="popup-content main-form" v-if="editU" @submit="upload()" enctype="multipart/form-data" >
                            <input type="hidden" name="_token" v-bind:value="$page.props.tokens.csrf" />

                            <label class="custom-text-label" for="username">Username</label><br>
                            <input
                                type="text"
                                id="username"
                                v-model="selectedUser.username"
                            />
                            <label class="custom-text-label" for="email">Email</label><br>
                            <input
                                type="text"
                                id="email"
                                v-model="selectedUser.email"
                            />
                            <label class="custom-text-label" for="firstname">First Name</label><br>
                            <input
                                type="text"
                                id="firstname"
                                v-model="selectedUser.first_name"
                            />
                            <label class="custom-text-label" for="lastname">Last Name</label><br>
                            <input
                                type="text"
                                id="lastname"
                                v-model="selectedUser.last_name"
                            />
                            <label class="custom-text-label" for="description">Description</label><br>
                            <textarea
                                id="description"
                                v-model="selectedUser.description"
                            />
                            <label class="custom-text-label" for="location">Location</label><br>
                            <select id="location" class="form-select block w-full mt-1" v-model="selectedUser.location">
                                <option v-for="country in countries" :value="country.value">
                                    {{ country.label }}
                                </option>
                            </select>
                            <input type="submit"
                                :class="{ 'opacity-25': processing }" 
                                :disabled="processing" @click="submitUser" value="Edit" />
                        </form>
                        <form class="popup-content main-form" v-if="deleteU" enctype="multipart/form-data" >
                                <input type="hidden" name="_token" v-bind:value="$page.props.tokens.csrf" />
                                    <u>Möchtest du diesen User wirklich löschen?</u><br>
                                    <b>{{selectedUser.username}} - {{selectedUser.email}}</b>
                                    <input type="submit"
                                        :class="{ 'opacity-25': processing }" 
                                        :disabled="processing" @click="deleteUserSubmit" value="Delete" />
                        </form>
                    </div>
                </div>
                <table v-if="songList && songs" class="w-2/3">
                    <th colspan="2">All Tracks</th>
                    <tr v-for="(x, k) in songList" :style="[k%2 == 0 ? {'backgroundColor': 'rgb(180, 141, 219)'} : '']">
                        <td style="width: 100px"><img :src="x.is_beat.get_cover.cover_path" /><audio controls :src="x.file_path"></audio></td>
                        <td>
                            <b>{{x.is_beat.title}}</b> from <i>{{x.is_beat.from_user.username}}</i><br>
                            <b>Tags:</b> <span v-if="x.is_beat.tag1">{{x.is_beat.tag1}}</span><span v-else>No tags</span>
                                <span v-if="x.is_beat.tag2">, {{x.is_beat.tag2}}</span>
                                <span v-if="x.is_beat.tag3">, {{x.is_beat.tag3}}</span>
                                <span v-if="x.is_beat.tag4">, {{x.is_beat.tag4}}</span>
                                <span v-if="x.is_beat.tag5">, {{x.is_beat.tag5}}</span><br>
                            <b>Desc:</b> <span v-if="x.is_beat.description">{{x.is_beat.description}}</span><span v-else>No description</span><br>
                            <b>BPM:</b> {{x.is_beat.bpm}}<br>
                            <b>Key:</b> {{x.is_beat.key}}<br>
                            <b>Price:</b> {{x.is_beat.price}} <br>
                            <b>Type:</b> {{x.is_beat.type}} <br>
                            <b>Uploaded:</b> {{parseISOString(x.is_beat.created_at)}} <span v-if="x.is_beat.created_at != x.is_beat.updated_at"> <b>last edited:</b> {{parseISOString(x.is_beat.updated_at)}}</span><br>
                            <b>Downloads:</b> {{x.is_beat.download_count}} <br>
                            <b>Likes:</b> {{x.is_beat.likes2.length}} <br>
                        </td>
                        <td>
                            <button @click="editTrack(x.is_beat.id, k)">edit</button><br>
                            <button @click="deleteTrack(x.is_beat.id, k)">delete</button>
                        </td>
                    </tr>
                </table>
                <table v-if="userList && user" class="w-2/3 pl-2">
                    <th colspan="2">All Users</th>
                    <tr v-for="(x, k) in userList" :style="[k%2 == 0 ? {'backgroundColor': 'rgb(180, 141, 219)'} : '']">
                        <td class="pl-2">
                            <h1 v-if="x.isadmin" class="text-2xl">Admin</h1>
                            ({{x.id}}) <b>{{x.username}}</b> - <i>{{x.email}}</i><br>
                            <b>First Name: </b>{{x.first_name}}<br>
                            <b>Last Name: </b>{{x.last_name}}<br>
                            <b>Created:</b> {{parseISOString(x.created_at)}} <span v-if="x.created_at != x.updated_at"> <b>last edited:</b> {{parseISOString(x.updated_at)}}</span><br>
                            <b>Verified:</b> {{parseISOString(x.email_verified_at)}}<br>
                            <b>ETH:</b> {{x.eth_address}}<br>
                            <b>Location:</b> {{x.location}} - {{dataLabel(x.location)}}<br>
                            <b>Description:</b> {{x.description}}<br>
                        </td>
                        <td>
                            <button @click="editUser(x.id, k)">edit</button><br>
                            <button @click="deleteUser(x.id, k)" :class="[this.$page.props.auth.user.id == x.id ? 'line-through': '']" :disabled="this.$page.props.auth.user.id == x.id">delete</button>
                        </td>
                    </tr>
                </table>
            </div>
        </div>
        <success v-if="showSuccess" :text="messageSuccess" @close="showSuccess = false" />
        <error v-if="showError" :text="messageError" @close="showError = false" />
    </div>
</template>

<script>
import Mobile from '@/Components/Mobile.vue';
import VueTagsInput from "@sipec/vue3-tags-input";
import Success from '@/Components/Success.vue';
import Error from '@/Components/Error.vue';
const countries = require('i18n-iso-countries')
countries.registerLocale(require('i18n-iso-countries/langs/en.json'))

export default {
    
    components: {
        VueTagsInput,
        Mobile,
        Success,
        Error,
    },

    props: {
    },

    computed: {
        countries(){
            const list = countries.getNames('en', { select: 'official' })
            return Object.keys(list).map((key) => ({ value: key, label: list[key] }))
        }
    },


    data(){
        return{
            songs: false,
            user: true,
            songList: null,
            userList: null,
            selectedSong: null,
            selectedUser: null,
            editS: false,
            deleteS: false,
            editU: false,
            deleteU: false,
            tags: [],
            processing: false,
            showSuccess: false,
            messageSuccess: 'Gongrats',
            showError: false,
            messageError: 'Error',
        }
    },

    methods: {
        loadSongs(term = ''){
            axios.get('/api/beats', { params: { keywords: term} })
            .then(response => {
                this.songList = response.data
            })
        },
        loadUser(){
            axios.get('/api/alluser')
            .then(response => {
                
                this.userList = response.data
            })
        },
        parseISOString(s) {
            if(s){
            var b = s.split(/\D+/);
            return new Date(Date.UTC(b[0], --b[1], b[2], b[3], b[4], b[5], b[6])).toLocaleString();}
        },
        editTrack(id, k){
            if(this.selectedSong && this.selectedSong.is_beat.id == id && this.editS){
                this.selectedSong = null;
            }else{
                this.selectedSong = this.songList[k]
                this.convertTags();
                this.editS = true;
                this.deleteS = false;
            }
        },
        deleteTrack(id, k){
            if(this.selectedSong && this.selectedSong.is_beat.id == id && this.deleteS){
                this.selectedSong = null;
            }else{
                this.selectedSong = this.songList[k]
                this.editS = false;
                this.deleteS = true;
            }
        },
        editUser(id, k){
            if(this.selectedUser && this.selectedUser.id == id && this.editU){
                this.selectedUser = null;
            }else{
                this.selectedUser = this.userList[k]
                this.editU = true;
                this.deleteU = false;
            }
        },
        deleteUser(id, k){
            if(this.selectedUser && this.selectedUser.id == id && this.deleteU){
                this.selectedUser = null;
            }else{
                this.selectedUser = this.userList[k]
                this.editU = false;
                this.deleteU = true;
            }
        },
        convertTags(){
            this.tags = []
            for (let i = 0; i < 5; i++) {
                if(this.selectedSong.is_beat['tag'+i]){
                    this.tags.push(this.selectedSong.is_beat['tag'+i])
                }
            }
        },
        deleteSubmit(e){
            e.preventDefault();
            this.processing = true;
            if(e){
                
                e.preventDefault();
            }
            
            let currentObj = this;
            const config = {
                headers: {
                    'content-type': 'multipart/form-data',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                },
            }
            
            axios.post('/api/delete/'+this.selectedSong.is_beat.id, config)
                .then(function (response) {
                            currentObj.processing = false;
                        
                        currentObj.loadSongs();
                        currentObj.editS = false;
                        currentObj.deleteS = false;
                    currentObj.emitter.emit('success','song deleted')
                    })
                    .catch(error => {
                        if(error.response){
                        currentObj.processing = false; 
                        }else{
                            currentObj.processing = false
                            
                        }
                    currentObj.emitter.emit('error', 'can\'t delete')
                    });
        },
        upload(e){
            e.preventDefault();
            this.processing = true;
            if(e){
                
                e.preventDefault();
            }
            
            let currentObj = this;
            const config = {
                headers: {
                    'content-type': 'multipart/form-data',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                },
            }

            let formData = new FormData();

            var tagsFin = [];
            Object.values(this.tags).forEach(e => {
                tagsFin.push(e.text)
            });


            formData.append('title', this.selectedSong.is_beat.title);
            formData.append('bpm', this.selectedSong.is_beat.bpm);
            formData.append('tagsArr', tagsFin);
            formData.append('selectedKey', this.selectedSong.is_beat.key);
            formData.append('type', this.selectedSong.is_beat.type);
            formData.append('description', this.selectedSong.is_beat.description);
            formData.append('feature', this.selectedSong.is_beat.feature);
            
            axios.post('/api/edit/'+this.selectedSong.is_beat.id, formData, config)
                .then(function (response) {
                            currentObj.processing = false;
                        
                        currentObj.loadSongs();
                        currentObj.editS = false;
                        currentObj.deleteS = false;
                    currentObj.emitter.emit('success','song edited')
                    })
                    .catch(error => {
                        if(error.response){
                        currentObj.processing = false; 
                        }else{
                            currentObj.processing = false
                            
                        }
                    currentObj.emitter.emit('error', 'can\'t edit')
                    });
        },
        dataLabel(x){
            return countries.getName(x, "en", {select: "alias"})
        },
        submitUser(e){
            
            e.preventDefault();
            let currentObj = this;
            const config = {
                headers: {
                    'content-type': 'multipart/form-data',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                },
            }

            let formData = new FormData();
            formData.append('use', 'admin');
            formData.append('username', this.selectedUser.username);
            formData.append('first_name', this.selectedUser.first_name);
            formData.append('last_name', this.selectedUser.last_name);
            formData.append('email', this.selectedUser.email);
            formData.append('id', this.selectedUser.id);
            formData.append('location', this.selectedUser.location);
            formData.append('desc', this.selectedUser.description);


            axios.post('/profile', formData, config)
                .then(function (response) {
                    currentObj.processing = false;
                    currentObj.loadUser();
                    currentObj.editU = false;
                    currentObj.deleteU = false;
                    currentObj.emitter.emit('success','user updated')
                })
                .catch(error => {
                    currentObj.emitter.emit('error', 'can\'t update')
            });
        },
        deleteUserSubmit(e){
            e.preventDefault();
            this.processing = true;
            if(e){
                
                e.preventDefault();
            }
            
            let currentObj = this;
            const config = {
                headers: {
                    'content-type': 'multipart/form-data',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                },
            }
            
            axios.post('/api/deleteUser/'+this.selectedUser.id, config)
                .then(function (response) {
                    currentObj.processing = false;
                    currentObj.loadUser();
                    currentObj.editU = false;
                    currentObj.deleteU = false;
                    currentObj.emitter.emit('success','user deleted')
                })
                .catch(error => {
                    currentObj.emitter.emit('error', 'can\'t delete')
                });
        },
    }, 
    created(){
        window.addEventListener('scroll', this.onScroll);
        // 
        if(!this.$page.props.auth.user || !this.$page.props.auth.user.isadmin){
            window.location.href = "/";
        };
        this.loadSongs();
        this.loadUser();
    },
    mounted() {
        this.emitter.on("success", message => {
            this.messageSuccess = message;
            this.showSuccess = true;
        })

        this.emitter.on("error", message => {
            this.messageError = message;
            this.showError = true;
        })
    },
    destroyed () {
    },

}
</script>

<style>
input{
    width:300px;
}
</style>