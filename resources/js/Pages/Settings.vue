<template>
    <div>
        <nav-bar-new profile/>
        <form @submit.prevent="submit" class="w-full max-w-sm m-40">
            <div class="mt-4">
                <BreezeLabel for="username" value="Username" />
                <BreezeInput id="username" type="text" class="mt-1 block w-full" v-model="form.username" required autocomplete="username"/>
            </div>

            <div class="mt-4">
                <BreezeLabel for="firstname" value="First Name" />
                <BreezeInput id="firstname" type="text" class="mt-1 block w-full" v-model="form.first_name" required autocomplete="first name"/>
            </div>

            <div class="mt-4">
                <BreezeLabel for="lastname" value="Last Name" />
                <BreezeInput id="lastname" type="text" class="mt-1 block w-full" v-model="form.last_name" required autocomplete="last name"/>
            </div>

            <div class="mt-4">
                    <BreezeLabel for="email" value="Email" />
                    <BreezeInput id="email" type="email" class="mt-1 block w-full" v-model="form.email" required autocomplete="email"/>
                </div>

            

            <div class="mt-4">
                <BreezeLabel for="location" value="Location" />
                <select id="location" class="form-select block w-full mt-1" v-model="form.location">
                    <option v-for="country in countries" :value="country.value">
                        {{ country.label }}
                    </option>
                </select>
            </div>

            <div class="w-full px-3">
                    <BreezeLabel for="desc" value="Description" />
                    <textarea id="desc" class="no-resize appearance-none block w-full bg-white-200 text-gray-700 border border-gray-200 rounded py-3 px-4 mb-3 leading-tight focus:outline-none focus:bg-white focus:border-gray-500 h-48 resize-none" v-model="form.desc"></textarea>
            </div>

            <!-- <div class="mt-4">
                <BreezeLabel for="password" value="Password" />
                <BreezeInput id="password" type="password" class="mt-1 block w-full" v-model="form.password" required autocomplete="new-password"/>
            </div>

            <div class="mt-4">
                <BreezeLabel for="password_confirmation" value="Confirm Password" />
                <BreezeInput id="password_confirmation" type="password" class="mt-1 block w-full" v-model="form.password_confirmation" required autocomplete="new-password"/>
            </div> -->

            <div class="text-center pt-4 pb-8 border-b border-gray-200">
                <Button class="ml-4 cta-main but-main" :class="{ 'opacity-25': form.processing }" :disabled="form.processing">
                    Update Profile!
                </Button>
            </div>
        </form>

        <change-password></change-password>

        <success v-if="showSuccess" :text="messageSuccess" @close="showSuccess = false" />

        <error v-if="showError" :text="messageError" @close="showError = false" />

    </div>
</template>


<script>
import BreezeButton from '@/Components/Button.vue'
import BreezeInput from '@/Components/Input.vue'
import BreezeLabel from '@/Components/Label.vue'
import BreezeValidationErrors from '@/Components/ValidationErrors.vue';
import { Head, Link } from '@inertiajs/inertia-vue3';
import Wallet from '@/Components/Wallet.vue'
import { CogIcon } from '@heroicons/vue/outline';
import NavBarNew from '@/Components/NavBarNew.vue'
import ChangePassword from '@/Components/ChangePassword.vue'

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
        ChangePassword
    },

     data(){
        return{
            form: this.$inertia.form({
                username: this.$page.props.auth.user.username,
                first_name: this.$page.props.auth.user.first_name,
                last_name: this.$page.props.auth.user.last_name,
                email: this.$page.props.auth.user.email,
                // password: this.$page.props.auth.user.password,
                // password_confirmation: this.$page.props.auth.user.password_confirmation,
                id: this.$page.props.auth.user.id,
                location: this.$page.props.auth.user.location,
                desc: this.$page.props.auth.user.description,
            }), 
        }
    },

    computed: {
        countries(){
            const list = countries.getNames('en', { select: 'official' })
            return Object.keys(list).map((key) => ({ value: key, label: list[key] }))
        }
    },



    methods: {
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
            // formData.append('password', this.form.password);
            // formData.append('password_confirmation', this.form.password_confirmation);
            formData.append('id', this.form.id);
            formData.append('location', this.form.location);
            formData.append('desc', this.form.desc);


            axios.post('/profile', formData, config)
                .then(function (response) {
                    console.log(response);
                    window.location.href= response.data;
                })
                .catch(error => {
                    console.log(error.response.data); // logs an object to the console

                    // Do something with error data
            });



            // this.form.post(this.route('myprofile'), {
            //     onFinish: () => this.form.reset('password', 'password_confirmation'),
            // })
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
                    console.log(response);
                    if (response.data.error) {
                        console.log(currentObj.error)
                    }else if (response.data.success) {
                        console.log(currentObj.success)
                    }
                })
                .catch(error => {
                    console.log(error.response.data); // logs an object to the console

                    // Do something with error data
                });
        },
    },

    mounted() {
        this.emitter.on("success", message => {
            console.log(message)
            this.messageSuccess = message;
            this.showSuccess = true;
        })

        this.emitter.on("error", message => {
            console.log(message)
            this.messageError = message;
            this.showError = true;
        })
    },
    
}
</script>

<style>

</style>