<template>
    <div class="main-page-form-div">
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
                <Head title="Confirm Password" />

                <div class="mb-4 main-form-text">
        This is a secure area of the application. Please confirm your password before continuing.
                </div>

                <div v-if="status" class="mb-4 font-medium text-sm text-green-600">
                    {{ status }}
                </div>

                <BreezeValidationErrors class="mb-4" />

                <form @submit.prevent="submit">
                    <div>
                        <label class="custom-text-label" for="password"> Password</label><br>
                        <BreezeInput id="password" 
                            type="password" 
                            class="block w-full mt-1 custom-text-input main-text-input" 
                            v-model="form.password" 
                            required autofocus 
                            autocomplete="current-password" />
                    </div>

                    <div class="main-form-row flex items-center justify-end mt-4">
                        <div class="main-form-element max">
                            <button 
                                class="main-page-form-cta submit" 
                                :class="{ 'opacity-25': form.processing }" 
                                :disabled="form.processing"
                            >
                                Confirm
                            </button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</template>

<script>
import BreezeButton from '@/Components/Button.vue'
import BreezeGuestLayout from '@/Layouts/Guest.vue'
import BreezeInput from '@/Components/Input.vue'
import BreezeLabel from '@/Components/Label.vue'
import BreezeValidationErrors from '@/Components/ValidationErrors.vue'
import { Head } from '@inertiajs/inertia-vue3';

export default {

    components: {
        BreezeButton,
        BreezeInput,
        BreezeLabel,
        BreezeValidationErrors,
        Head,
    },

    data() {
        return {
            form: this.$inertia.form({
                password: '',
            })
        }
    },

    methods: {
        submit() {
            this.form.post(this.route('password.confirm'), {
                onFinish: () => this.form.reset(),
            })
        }
    }
}
</script>
