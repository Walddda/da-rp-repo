<template>
    <div>
        <div class="details"><mobile></mobile></div>
        <div class="responsive">
            <nav-bar-new profile/>
            <div class="settingsContent">
                <div class="main-page-form-div">
                    <div class="main-page-form-heading">
                        <div class="heading-title">
                            Settings
                        </div>
                    </div>
                    <div class="main-page-form-content">
                    <div class="content-back"></div>
                        <div class="content-form ">
                            <Head title="Settings" />
                            <form @submit.prevent="submit" class="box-border">
                                <div class="main-form-row">
                                    <div class="main-form-element">
                                        <label class="custom-text-label" for="username">Username</label><br>
                                        <BreezeInput 
                                            id="username" 
                                            type="text" 
                                            :class="[error && error.title && error.title[0].includes('required') && !title ? 'error' : '', 'custom-text-input main-text-input']" 
                                            v-model="form.username" 
                                            required 
                                            autofocus 
                                            autocomplete="first_name" 
                                        />
                                    </div>
                                </div>
                                <div class="main-form-row">
                                    <div class="main-form-element half">
                                        <label class="custom-text-label" for="firstname">First Name</label><br>
                                        <BreezeInput 
                                            id="firstname" 
                                            type="text" 
                                            :class="[error && error.title && error.title[0].includes('required') && !title ? 'error' : '', 'custom-text-input main-text-input']" 
                                            v-model="form.first_name" 
                                            autofocus 
                                            autocomplete="first name" 
                                        />
                                    </div>
                                    <div class="main-form-element half">
                                        <label class="custom-text-label" for="lastname">Last Name</label><br>
                                        <BreezeInput 
                                            id="lastname" 
                                            type="text" 
                                            :class="[error && error.title && error.title[0].includes('required') && !title ? 'error' : '', 'custom-text-input main-text-input']" 
                                            v-model="form.last_name" 
                                            autofocus 
                                            autocomplete="last name" 
                                        />
                                    </div>
                                </div>
                                <div class="main-form-row">
                                    <div class="main-form-element">
                                        <label class="custom-text-label" for="email">E-Mail</label><br>
                                        <BreezeInput 
                                            id="email" 
                                            type="text" 
                                            :class="[error && error.title && error.title[0].includes('required') && !title ? 'error' : '', 'custom-text-input main-text-input']" 
                                            v-model="form.email" 
                                            required
                                            autofocus 
                                            autocomplete="email" 
                                        />
                                    </div>
                                </div>
                                <div class="main-form-row">
                                    <div class="main-form-element"
                                    @mouseleave="mouseInpf(false)"
                                    @mouseover="mouseInpf(true)">
                                        <label class="custom-text-label" for="location">Location</label><br>
                                        <input
                                            type="text"
                                            name="location"
                                            :class="[error && error.key ? 'error' : '', 'custom-text-input main-text-input']"
                                            id="location"
                                            v-model="form.location"
                                            @keydown="keyClick = false; mouseOptf(false)"
                                            @focus="selectFocus = true"
                                            @blur="leave('blur')"
                                            ref="keyInp"
                                        />
                                        <div class="main-select-custom-options" 
                                            v-if="selectFocus && !keyClick"
                                            @mouseleave="mouseOptf(false)"
                                            @mouseover="mouseOptf(true)"
                                        >
                                            <p v-for="k in countries">
                                                <span v-if="!form.location || k.label.toUpperCase().includes(form.location.toUpperCase())"
                                                    @click="form.location = k.label; $refs.keyInp.focus(); keyClick = true">
                                                    {{k.label}}
                                                </span>
                                            </p>
                                        </div>
                                    </div>
                                </div>
                                <div class="main-form-row">
                                    <div class="main-form-element">
                                        <label class="custom-text-label" for="desc">Bio</label><br>
                                        <BreezeInput 
                                            id="desc" 
                                            type="text" 
                                            :class="[error && error.title && error.title[0].includes('required') && !title ? 'error' : '', 'custom-text-input main-text-input']" 
                                            v-model="form.desc"  
                                        />
                                    </div>
                                </div>
                                <div class="main-form-row">
                                    <div class="main-form-element max">
                                        <button 
                                            class="main-page-form-cta submit" 
                                            :class="{ 'opacity-25': form.processing }" 
                                            :disabled="form.processing"
                                        >
                                            Update Profile
                                        </button>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
                <change-password class="pwdSettings"></change-password>
            </div>
        </div>
    </div>
</template>


<script>
import BreezeButton from '@/Components/Button.vue'
import BreezeInput from '@/Components/Input.vue'
import BreezeLabel from '@/Components/Label.vue'
import BreezeValidationErrors from '@/Components/ValidationErrors.vue';
import { Head, Link } from '@inertiajs/inertia-vue3';
import { CogIcon } from '@heroicons/vue/outline';
import NavBarNew from '@/Components/NavBarNew.vue'
import ChangePassword from '@/Components/ChangePassword.vue'
import Mobile from '@/Components/Mobile.vue'

const countries = require('i18n-iso-countries')
countries.registerLocale(require('i18n-iso-countries/langs/en.json'))



export default {
    components: {
        BreezeButton,
        BreezeInput,
        BreezeLabel,
        BreezeValidationErrors,
        Head,
        Link,
        CogIcon,
        NavBarNew,
        ChangePassword,
        Mobile
    },

     data(){
        return{
            form: this.$inertia.form({
                username: this.$page.props.auth.user.username,
                first_name: this.$page.props.auth.user.first_name,
                last_name: this.$page.props.auth.user.last_name,
                email: this.$page.props.auth.user.email,
                id: this.$page.props.auth.user.id,
                location: this.$page.props.auth.user.location,
                desc: this.$page.props.auth.user.description,
            }), 
            mouseOpt: false,
            mouseInp: false,
            keyClick: false,
            selectFocus: false,
            error: {},
        }
    },

    computed: {
        countries(){
            const list = countries.getNames('en', { select: 'official' })
            return Object.keys(list).map((key) => ({ value: key, label: list[key] }))
        }
    },



    methods: {
        leave(x){
            
            if(!this.mouseOpt && !this.mouseInp){
                this.selectFocus = false;
                this.keyClick = false;
            }
            
            var corrKey = false;
            this.countries.forEach(key => {
                if(this.form.location == key.label){
                    
                    corrKey = true;
                }
            })
            if(!corrKey){
            }else if(this.error.key){
            }
        },
        mouseOptf(a){
            this.mouseOpt = a;
        },
        mouseInpf(a){
            this.mouseInp = a;
        },
        submit(e) {
            e.preventDefault();
            let currentObj = this;
            const config = {
                headers: {
                    'content-type': 'multipart/form-data',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                },
            }

            let formData = new FormData();
            formData.append('username', this.form.username);
            formData.append('first_name', this.form.first_name);
            formData.append('last_name', this.form.last_name);
            formData.append('email', this.form.email);
            formData.append('id', this.form.id);
            formData.append('location', this.form.location);
            formData.append('desc', this.form.desc);


            axios.post('/profile', formData, config)
                .then(function (response) {
                    
                    window.location.href= response.data;
                })
                .catch(error => {
            });
        },
        
        upload(e){
            e.preventDefault();
            let currentObj = this;
            const config = {
                headers: {
                    'content-type': 'multipart/form-data',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                },
            }

            let formData = new FormData();
            formData.append('form', this.form);


            axios.post('/profile', formData, config)
                .then(function (response) {
                    
                    if (response.data.error) {
                        
                    }else if (response.data.success) {
                        
                    }
                })
                .catch(error => {
                });
        },
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
    
}
</script>

<style>
    .pwdSettings {
        margin-top: -250px;
    }
</style>