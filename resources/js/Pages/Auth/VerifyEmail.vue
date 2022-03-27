<template>
    <div>
        <div class="details">
            <mobile></mobile>
        </div>
        <div class="main-page-form-div responsive">
            <div class="main-page-form-heading">
                <div class="heading-title">
                    Verify your E-Mail
                </div>
                <div class="heading-logo"><a href="/">
                    <img src="/storage/assets/logo.png" class="h-44 w-auto"/></a>
                </div>
            </div>
            <div class="main-page-form-content">
                <div class="content-back">
                    <!--  bg-yellow-500 -->
                    <a :href="route('home')">
                        <button class="main-page-form-cta back">Back</button>
                    </a>
                </div>
                <div class="content-form ">
                    <Head title="Email Verification" />

                    <div class="mb-4 main-form-text">
                        Thanks for signing up! Before getting started, could you verify your email address by clicking on the link we just emailed to you? If you didn't receive the email, we will gladly send you another. 
                    </div>

                    <div class="mb-4 main-form-text" v-if="verificationLinkSent">
                        A new verification link has been sent to the email address you provided during registration.
                    </div>

                    <BreezeValidationErrors class="mb-4" />

                    <form @submit.prevent="submit">
                        <div class="main-form-row text-right flex justify-end">
                            <div class="main-form-element half text-right">  
                                <Link :href="route('logout')" method="post" as="button" class="underline text-sm text-gray-600 hover:text-gray-900">Log Out</Link>
                            </div>
                        </div>
                        <div class="main-form-row flex items-center justify-end mt-4">
                            <div class="main-form-element max">
                                <button 
                                    class="main-page-form-cta submit" 
                                    :class="{ 'opacity-25': form.processing }" 
                                    :disabled="form.processing"
                                >
                                    Resend Verification Email
                                </button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
import BreezeButton from '@/Components/Button.vue'
import { Head, Link } from '@inertiajs/inertia-vue3';
import Mobile from '@/Components/Mobile.vue';

export default {

    components: {
        BreezeButton,
        Head,
        Link,
        Mobile,
    },

    props: {
        status: String,
    },

    data() {
        return {
            form: this.$inertia.form()
        }
    },

    methods: {
        submit() {
            this.form.post(this.route('verification.send'))
        },
    },

    computed: {
        verificationLinkSent() {
            return this.status === 'verification-link-sent';
        }
    }
}
</script>
