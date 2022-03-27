<template>
    <div>
        <div class="details">
            <mobile></mobile>
        </div>
        <div class="main-page-form-div responsive">
            <div class="main-page-form-heading">
                <div class="heading-title">
                    Reset Password
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
                    <Head title="Reset Password" />

                    <BreezeValidationErrors class="mb-4" />

                    <form @submit.prevent="submit">
                        <div class="main-form-row">
                            <div class="main-form-element">
                                <label class="custom-text-label" for="login">Email</label><br>
                                <BreezeInput 
                                    id="login" 
                                    type="text" 
                                    class="custom-text-input main-text-input" 
                                    v-model="form.login" 
                                    required 
                                    autofocus
                                />
                            </div>
                        </div>
                        <div class="main-form-row">
                            <div class="main-form-element">
                                <label class="custom-text-label" for="password">Password</label><br>
                                <BreezeInput 
                                    id="password" 
                                    type="password" 
                                    class="custom-text-input main-text-input" 
                                    v-model="form.password" 
                                    required 
                                    autocomplete="new-password" 
                                    autofocus
                                />
                            </div>
                        </div>
                        <div class="main-form-row">
                            <div class="main-form-element">
                                <label class="custom-text-label" for="password_confirmation">Confirm Password</label><br>
                                <BreezeInput 
                                    id="password_confirmation" 
                                    type="password" 
                                    class="custom-text-input main-text-input" 
                                    v-model="form.password_confirmation" 
                                    required 
                                    autocomplete="new-password" 
                                    autofocus
                                />
                            </div>
                        </div>
                        <div class="main-form-row flex items-center justify-end mt-4">
                            <div class="main-form-element max">
                                <button 
                                    class="main-page-form-cta submit" 
                                    :class="{ 'opacity-25': form.processing }" 
                                    :disabled="form.processing"
                                >
                                    Reset Password
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
    login: String,
    token: String,
    },

    data() {
        return {
        form: this.$inertia.form({
            token: this.token,
            login: this.login,
            password: '',
            password_confirmation: '',
        })
        }
    },

    methods: {
        submit() {
            this.form.post(this.route('password.update'), {
                onFinish: () => this.form.reset('password', 'password_confirmation'),
            })
        }
    }
}
</script>
