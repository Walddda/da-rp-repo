<template>
    <div>
        <div class="details">
            <mobile></mobile>
        </div>
        <div class="main-page-form-div responsive">
            <div class="main-page-form-heading">
                <div class="heading-title">
                    Forgot Password
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
                    <Head title="Forgot Password" />

                    <div class="mb-4 main-form-text">
                        Forgot your password? No problem. Just let us know your email address and we will email you a password reset link that will allow you to choose a new one.
                    </div>

                    <div v-if="status" class="mb-4 font-medium text-sm text-green-600">
                        {{ status }}
                    </div>

                    <BreezeValidationErrors class="mb-4" />

                    <form @submit.prevent="submit">
                        <div>
                            <label class="custom-text-label" for="login"> Email</label><br>
                            <BreezeInput id="login" 
                                type="text" 
                                class="block w-full mt-1 custom-text-input main-text-input" 
                                v-model="form.login" 
                                required autofocus 
                                autocomplete="username" />
                        </div>

                        <div class="main-form-row flex items-center justify-end mt-4">
                            <div class="main-form-element max">
                                <button 
                                    class="main-page-form-cta submit" 
                                    :class="{ 'opacity-25': form.processing }" 
                                    :disabled="form.processing"
                                >
                                    Email Password Reset Link
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
import BreezeInput from '@/Components/Input.vue'
import BreezeLabel from '@/Components/Label.vue'
import BreezeValidationErrors from '@/Components/ValidationErrors.vue'
import { Head } from '@inertiajs/inertia-vue3';
import Mobile from '@/Components/Mobile.vue';

export default {

    components: {
        BreezeButton,
        BreezeInput,
        BreezeLabel,
        BreezeValidationErrors,
        Head,
        Mobile,
    },

    props: {
        status: String,
    },

    data() {
        return {
            form: this.$inertia.form({
                login: '',
            })
        }
    },

    methods: {
        submit() {
            this.form.post(this.route('password.email'))
        }
    }
}
</script>
