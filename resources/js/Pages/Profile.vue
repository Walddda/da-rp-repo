<template>
    <div>
        <!-- <nav-bar :canLogin="canLogin" :canRegister="canRegister"></nav-bar> -->
        <BreezeValidationErrors class="mb-4" />

        <wallet></wallet>

            <a href="/settings">Settings</a>

            <label> Username: {{form.username}} </label> <br>
            <label> First Name: {{form.first_name}} </label> <br>
            <label> Last Name: {{form.last_name}} </label> <br>
            <label> EMail: {{form.email}} </label> <br>

            <a href="/settings"><CogIcon class="h-6 w-6" aria-hidden="true"/></a>

    </div>
</template>

<script>
import { Head, Link } from '@inertiajs/inertia-vue3';
import NavBar from '@/Components/NavBar.vue';
import Wallet from '@/Components/Wallet.vue';
import { CogIcon } from '@heroicons/vue/outline';

export default {
    
    components: {
        Head,
        Link,
        NavBar,
        Wallet,
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
            })
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
input{
    width:300px;
}
</style>