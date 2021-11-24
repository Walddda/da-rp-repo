<template>
    <div>
        <nav-bar :canLogin="canLogin" :canRegister="canRegister"></nav-bar>
        <BreezeValidationErrors class="mb-4" />

        <form @submit.prevent="submit" class="box-border">
        <label>Heiii</label>
        {{form.username}} <br>
        <label> Username: {{form.username}} </label> <br>
        <label> First Name: {{form.first_name}} </label> <br>
        <label> Last Name: {{form.last_name}} </label> <br>
        <label> EMail: {{form.email}} </label> <br>

        <div class="mt-4">
            <BreezeLabel for="username" value="Username" />
            <BreezeInput id="username" type="text" class="mt-1 block w-full" v-model="form.username" required autocomplete="username" />
        </div>

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

        <BreezeButton class="ml-4" :class="{ 'opacity-25': form.processing }" :disabled="form.processing">
            Update Profile
        </BreezeButton>

        <!--Username: <input type="text" id="username" /> <button class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 border border-blue-700 rounded" type="submit">Change1</button> <br>
        First Name: <input type="text" :placeholder="Hei" v-model="firstName" /> <button @click="submit" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 border border-blue-700 rounded" type="button">Change</button> <br>
        Last Name:<input type="text" :value="[[this.$page.props.auth.user.last_name]]" /> <button class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 border border-blue-700 rounded" type="submit">Change</button> <br>
        E-Mail <input type="text" :value="[[this.$page.props.auth.user.email]]" /> <button class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 border border-blue-700 rounded" type="submit">Change</button> <br>
        NewPassword: <input type ="password" /> <button class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 border border-blue-700 rounded" type="submit">Change</button> <br>
        New Password Repeat <input type ="password" /> <button class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 border border-blue-700 rounded" type="submit">Change</button> <br>
    -->
        </form>
    </div>
</template>

<script>
import BreezeButton from '@/Components/Button.vue'
import BreezeInput from '@/Components/Input.vue'
import BreezeLabel from '@/Components/Label.vue'
import BreezeValidationErrors from '@/Components/ValidationErrors.vue';
import { Head, Link } from '@inertiajs/inertia-vue3';
import NavBar from '@/Components/NavBar.vue'

export default {
    
    components: {
        BreezeButton,
        BreezeInput,
        BreezeLabel,
        BreezeValidationErrors,
        Head,
        Link,
        NavBar,
    },


    data(){
        return{
            form: this.$inertia.form({
                username: this.$page.props.auth.user.username,
                first_name: this.$page.props.auth.user.first_name,
                last_name: this.$page.props.auth.user.last_name,
                email: this.$page.props.auth.user.email,
                id: this.$page.props.auth.user.id,
            })
        }
    },

    methods: {
        submit() {
            this.form.post(this.route('myprofile'))
    }, 
    }, 

}
</script>

<style>
input{
    width:300px;
}
</style>