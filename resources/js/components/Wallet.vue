<template>
    <div class="pt-4 pb-8 border-b border-gray-200">
            <Button class="ml-4 cta-main but-main" @click="connect(); close()">
                Connect Wallet
            </Button>
    </div>
</template>

<script>
import Web3 from 'web3/dist/web3.min.js'
import { useForm } from '@inertiajs/inertia-vue3'
import BreezeButton from '@/Components/Button.vue'

    export default {

        components: {
            BreezeButton,
            useForm,
            Web3,
    },

        methods: {
            submit() {

            },

            async connect() {
                if (typeof window.ethereum !== 'undefined') {

                    const accounts = await ethereum.request({ method: 'eth_requestAccounts' })
                    const address = accounts[0]


                    console.log('hi')

                    return useForm({ address }).post(this.route('wallet'))
                } else {
                    alert('MetaMask not detected. Please try again from a MetaMask enabled browser.')
                }
            },

            close() {
                this.emitter.emit('closePopupWallet');
            }
        }    
    }
</script>

<style>

</style>