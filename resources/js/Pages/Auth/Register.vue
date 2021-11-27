<template>
    <div>
        <Head title="Register" />

        <BreezeValidationErrors class="mb-4" />

        <form @submit.prevent="submit" class="box-border">
            <div class="inline-flex w-full box-border">
                <div class="mt-4 flex-1 w-2/5 box-border pr-4">
                    <BreezeLabel for="first_name" value="First Name" />
                    <BreezeInput id="first_name" type="text" class="mt-2 block w-full" v-model="form.first_name" required autofocus autocomplete="first_name" />
                </div>
                
                <div class="mt-4 flex-1 w-2/5 box-border pl-4">
                    <BreezeLabel for="last_name" value="Last Name" />
                    <BreezeInput id="last_name" type="text" class="mt-2 block w-full" v-model="form.last_name" required autofocus autocomplete="last_name"/>
                </div>
            </div>

            <div class="mt-4">
                <BreezeLabel for="email" value="Email" />
                <!-- warum autocomplete email? -->
                <BreezeInput id="email" type="email" class="mt-1 block w-full" v-model="form.email" required autocomplete="username" />
            </div>

            <div class="mt-4">
                <BreezeLabel for="username" value="Username" />
                <BreezeInput id="username" type="text" class="mt-1 block w-full" v-model="form.username" required autocomplete="username" />
            </div>

            <div class="mt-4">
                <BreezeLabel for="password" value="Password" />
                <BreezeInput id="password" type="password" class="mt-1 block w-full" v-model="form.password" required autocomplete="new-password"/>
            </div>

            <div class="mt-4">
                <BreezeLabel for="password_confirmation" value="Confirm Password" />
                <BreezeInput id="password_confirmation" type="password" class="mt-1 block w-full" v-model="form.password_confirmation" required autocomplete="new-password"  />
            </div>

            <div class="flex mt-6">
                <label class="flex items-center">
                    <input type="checkbox" class="form-checkbox" v-model="wallet" name="wallet">
                    <span class="ml-2">Connect <span class="underline"><a href="https://metamask.io/" target="_blank">Metamask Wallet</a></span></span>
                </label>
            </div>

            <div class="flex items-center justify-end mt-4">
                <Link :href="route('login')" class="underline text-sm text-gray-600 hover:text-gray-900">
                    Already registered?
                </Link>

                <BreezeButton @click="loginWeb3" class="ml-4" :class="{ 'opacity-25': form.processing }" :disabled="form.processing">
                    Register
                </BreezeButton>
            </div>

            
        </form>
    </div>
</template>

<script>
import BreezeButton from '@/Components/Button.vue'
import BreezeGuestLayout from '@/Layouts/Guest.vue'
import BreezeInput from '@/Components/Input.vue'
import BreezeLabel from '@/Components/Label.vue'
import BreezeValidationErrors from '@/Components/ValidationErrors.vue'
import { Head, Link, useForm } from '@inertiajs/inertia-vue3'
import Web3 from 'web3/dist/web3.min.js'

export default {
    layout: BreezeGuestLayout,

    components: {
        BreezeButton,
        BreezeInput,
        BreezeLabel,
        BreezeValidationErrors,
        Head,
        Link,
    },

    data() {
        return {
            form: this.$inertia.form({
                first_name: 'Roman',
                last_name: 'Stimpfl',
                email: 'beatchainbeatchain@gmail.com',
                username: 'test01',
                password: 'Hallo123',
                password_confirmation: 'Hallo123',
                terms: false,
            }),
            wallet: false,
        }
    },

    methods: {
        submit() {
            this.form.post(this.route('register'), {
                onFinish: () => this.form.reset('password', 'password_confirmation'),
            })
        },
        async loginWeb3() {
            if (this.wallet) {
                if (! window.ethereum) {
                    alert('MetaMask not detected. Please try again from a MetaMask enabled browser.')
                }

                const web3 = new Web3(window.ethereum);

                const message = [
                    "I have read and accept the terms and conditions of this app.",
                    "Please sign me in!"
                ].join("\n")

                const address = (await web3.eth.requestAccounts())[0]
                const signature = await web3.eth.personal.sign(message, address)

                return useForm({ message, address, signature }).post(this.route('wallet'))
            }
        }
    }
}
</script>
