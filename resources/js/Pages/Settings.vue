<template>
    <div>
        <form @submit.prevent="submit" class="box-border">
            <!--<div class="mt-4">
                <BreezeLabel for="username" value="Username" />
                <BreezeInput id="username" type="text" class="mt-1 block w-full" v-model="form.username" required autocomplete="username" />
            </div>-->

            <div class="mt-4">
                <BreezeLabel for="firstname" value="First Name" />
                <BreezeInput id="firstname" type="text" class="mt-1 block w-full" v-model="form.first_name" required autocomplete="username" />
            </div>

            <div class="mt-4">
                <BreezeLabel for="lastname" value="Last Name" />
                <BreezeInput id="lastname" type="text" class="mt-1 block w-full" v-model="form.last_name" required autocomplete="username" />
            </div>

            <div class="mt-4">
                    <BreezeLabel for="email" value="Email" />
                    <BreezeInput id="email" type="email" class="mt-1 block w-full" v-model="form.email" required autocomplete="username" />
                </div>

            <!--
            <div class="mt-4">
                <BreezeLabel for="password" value="Password" />
                <BreezeInput id="password" type="password" class="mt-1 block w-full" v-model="form.password" required autocomplete="new-password"/>
            </div>

            <div class="mt-4">
                <BreezeLabel for="password_confirmation" value="Confirm Password" />
                <BreezeInput id="password_confirmation" type="password" class="mt-1 block w-full" v-model="form.password_confirmation" required autocomplete="new-password"  />
            </div>-->

            <div class="text-center pt-4 pb-8 border-b border-gray-200">
                <BreezeButton class="ml-4" :class="{ 'opacity-25': form.processing }" :disabled="form.processing">
                    Update Profile
                </BreezeButton>
            </div>


            <select v-model="selected">
                <option v-for="country in countries" :value="country.value">
                    {{ country.label }}
                </option>
            </select>

            <div class="w-full px-3">
                    <label class="block uppercase tracking-wide text-gray-700 text-xs font-bold mb-2" for="description">
                        Description
                    </label>
                    <!-- v-model="description" -->
                    <textarea name="desc" class="no-resize appearance-none block w-full bg-white-200 text-gray-700 border border-gray-200 rounded py-3 px-4 mb-3 leading-tight focus:outline-none focus:bg-white focus:border-gray-500 h-48 resize-none" id="desc"></textarea>
            </div>
        </form>

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
    },

     data(){
        return{
            form: this.$inertia.form({
                username: this.$page.props.auth.user.username,
                first_name: this.$page.props.auth.user.first_name,
                last_name: this.$page.props.auth.user.last_name,
                email: this.$page.props.auth.user.email,
                password: this.$page.props.auth.user.password,
                password_confirmation: this.$page.props.auth.user.password_confirmation,
                id: this.$page.props.auth.user.id,
                desc: this.$page.props.auth.user.description,
            }), 
            selected: null, 
        }
    },

    computed: {
        countries(){
            const list = countries.getNames('en', { select: 'official' })
            return Object.keys(list).map((key) => ({ value: key, label: list[key] }))
        }
    },



    methods: {
        submit() {
            this.form.post(this.route('myprofile'), {
                onFinish: () => this.form.reset('password', 'password_confirmation'),
            })
    }, 
    }, 
    
}
</script>

<style>

</style>