<template>        
<div id="details">Geh auf den Pc bruh... </div>
    <div class="main-page-form-div responsive">
        <div class="main-page-form-heading">
            <div class="heading-title">Login</div>
            <div class="heading-logo">
                <a href="/">
                    <img src="/storage/assets/logo.png" class="h-44 w-auto"
                /></a>
            </div>
        </div>
        <div class="main-page-form-content">
            <div class="content-back">
                <a :href="route('home')">
                    <button class="main-page-form-cta back">Back</button>
                </a>
            </div>
            <div class="content-form">
                <Head title="Log in" />
                <form @submit.prevent="submit" class="box-border">
                    <div class="main-form-row">
                        <div class="main-form-element">
                            <label class="custom-text-label" for="login"
                                >Email / Username</label
                            ><br />
                            <BreezeInput
                                id="login"
                                type="text"
                                :class="[
                                    error &&
                                    error.login &&
                                    error.login[0].includes('required') &&
                                    !form.login
                                        ? 'error'
                                        : '',
                                    'custom-text-input main-text-input',
                                ]"
                                v-model="form.login"
                                required
                                autocomplete="username"
                                autofocus
                            />
                        </div>
                    </div>
                    <div class="main-form-row">
                        <div class="main-form-element">
                            <label class="custom-text-label" for="password"
                                >Password</label
                            ><br />
                            <BreezeInput
                                id="password"
                                type="password"
                                :class="[
                                    error &&
                                    error.password &&
                                    error.password[0].includes('required') &&
                                    !form.password
                                        ? 'error'
                                        : '',
                                    'custom-text-input main-text-input',
                                ]"
                                v-model="form.password"
                                required
                                autocomplete="current-password"
                            />
                        </div>
                    </div>
                    <div class="main-form-row">
                        <div class="main-form-element half flex items-center">
                            <input
                                type="checkbox"
                                id="checkRemember"
                                class="form-checkbox main-form-checkbox left"
                                v-model="form.remember"
                                name="coverType"
                            />
                            <label class="custom-text-label" for="checkRemember"
                                >Remember me</label
                            >
                        </div>
                        <div class="main-form-element half text-right">
                            <a
                                v-if="canResetPassword"
                                :href="route('password.request')"
                                class="underline text-sm text-gray-600 hover:text-gray-900"
                            >
                                Forgot your password?
                            </a>
                        </div>
                    </div>
                    <div class="main-form-row flex items-center justify-end">
                        <div class="main-form-element max">
                            <Link
                                :href="route('register')"
                                class="underline text-sm text-gray-600 hover:text-gray-900"
                            >
                                Register
                            </Link>
                        </div>
                        <div class="main-form-element min">
                            <button
                                class="main-page-form-cta submit"
                                :class="{ 'opacity-25': form.processing }"
                                :disabled="form.processing"
                            >
                                Login
                            </button>
                        </div>
                    </div>

                    <BreezeValidationErrors class="mb-4" />
                </form>
            </div>
        </div>
    </div>
</template>

<script>
import BreezeButton from "@/Components/Button.vue";
import BreezeCheckbox from "@/Components/Checkbox.vue";
import BreezeGuestLayout from "@/Layouts/Guest.vue";
import BreezeInput from "@/Components/Input.vue";
import BreezeLabel from "@/Components/Label.vue";
import BreezeValidationErrors from "@/Components/ValidationErrors.vue";
import { Head, Link } from "@inertiajs/inertia-vue3";

export default {
    components: {
        BreezeButton,
        BreezeCheckbox,
        BreezeInput,
        BreezeLabel,
        BreezeValidationErrors,
        Head,
        Link,
    },

    props: {
        canResetPassword: Boolean,
        status: String,
    },

    data() {
        return {
            form: this.$inertia.form({
                login: "",
                password: "",
                remember: false,
            }),
        };
    },

    methods: {
        submit() {
            this.form.post(this.route("login"), {
                onFinish: () => this.form.reset("password"),
            });
        },
    },
};
</script>

