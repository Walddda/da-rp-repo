<template>
    <div>
        <nav-bar-new profile/>
        <BreezeValidationErrors class="mb-4" />
        <div>
            <wallet></wallet>
    
            <label> Username: {{form.username}} </label> <br>
            <label> First Name: {{form.first_name}} </label> <br>
            <label> Last Name: {{form.last_name}} </label> <br>
            <label> EMail: {{form.email}} </label> <br>

            <!-- {{userData.username}} -->

            <!-- MyProfile, sehen andere nicht -->
            <div v-if="own">
                <a href="/settings">Settings</a><br>
                <a href="/settings"><CogIcon class="h-6 w-6" aria-hidden="true"/></a>
            </div> 
        </div>
    </div>
</template>

<script>
import { Head, Link } from '@inertiajs/inertia-vue3';
import Wallet from '@/Components/Wallet.vue';
import { CogIcon } from '@heroicons/vue/outline';
import NavBarNew from '@/Components/NavBarNew.vue'

export default {
    
    components: {
        Head,
        Link,
        NavBarNew,
        Wallet,
        CogIcon,
    },

    props: {
        userData: Array,
    },


    data(){
        return{
            form: this.$inertia.form({
                username: this.userData.username,
                first_name: this.userData.first_name,
                last_name: this.userData.last_name,
                email: this.userData.email,
                password: this.userData.password,
                password_confirmation: this.userData.password_confirmation,
                id: this.userData.id,
            }),
            own: this.$page.props.auth.user.id == this.userData.id,
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