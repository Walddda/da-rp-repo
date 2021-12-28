<template>
    <div class="main-page-form-div">
        <div class="main-page-form-heading">
            <div class="heading-title">
                Register
            </div>
            <div class="heading-logo"><a href="/">
                <img src="/storage/assets/logo.png" class="h-44 w-auto"/>
                </a>
            </div>
        </div>
        <div class="main-page-form-content">
            <div class="content-back">
                <!--  bg-yellow-500 -->
                <button class="main-page-form-cta back" @click="back">Back</button>
            </div>
            <div class="content-form ">
                <!-- bg-red-400 -->
                 <Head title="Register" />
                <form @submit.prevent="submit" class="box-border">
                    <div class="main-form-row">
                        <div class="main-form-element half">
                            <label class="custom-text-label" for="first_name">First Name</label><br>
                            <BreezeInput 
                                id="first_name" 
                                type="text" 
                                :class="[error && error.title && error.title[0].includes('required') && !title ? 'error' : '', 'custom-text-input main-text-input']" 
                                v-model="form.first_name" 
                                required 
                                autofocus 
                                autocomplete="first_name" 
                            />
                        </div>
                        <div class="main-form-element half">
                            <label class="custom-text-label" for="last_name">Last Name</label><br>
                            <BreezeInput 
                                id="last_name" 
                                type="text" 
                                :class="[error && error.title && error.title[0].includes('required') && !title ? 'error' : '', 'custom-text-input main-text-input']" 
                                v-model="form.last_name" 
                                required 
                                autofocus 
                                autocomplete="last_name"
                            />
                        </div>
                    </div>
                    <div class="main-form-row">
                        <div class="main-form-element">
                            <label class="custom-text-label" for="email">Email</label><br>
                            <BreezeInput 
                                id="email" 
                                type="email" 
                                :class="[error && error.title && error.title[0].includes('required') && !title ? 'error' : '', 'custom-text-input main-text-input']" 
                                v-model="form.email" 
                                required 
                                autocomplete="username" 
                            />
                        </div>
                    </div>
                    <div class="main-form-row">
                        <div class="main-form-element half">
                            <label class="custom-text-label" for="username">Username</label><br>
                            <BreezeInput 
                                id="username" 
                                type="text" 
                                :class="[error && error.title && error.title[0].includes('required') && !title ? 'error' : '', 'custom-text-input main-text-input']" 
                                v-model="form.username"
                                required 
                                autocomplete="username" 
                            />
                        </div>
                        <div class="main-form-element half"
                        @mouseleave="mouseInpf(false)"
                        @mouseover="mouseInpf(true)"
                    >
                        <label class="custom-text-label" for="key">Location</label><br>
                        <input
                            type="text"
                            name="key"
                            :class="[error && error.key ? 'error' : '', 'custom-text-input main-text-input']"
                            id="key"
                            v-model="form.location"
                            @keydown="keyClick = false; mouseOptf(false)"
                            @focus="selectFocus = true"
                            @blur="leave('blur')"
                            ref="keyInp"
                        />
                        <div class="main-select-custom-options" 
                            v-if="selectFocus && !keyClick"
                            @mouseleave="mouseOptf(false)"
                            @mouseover="mouseOptf(true)"
                        >
                            <p v-for="k in countries">
                                <span v-if="!form.location || k.label.toUpperCase().includes(form.location.toUpperCase())"
                                    @click="form.location = k.label; $refs.keyInp.focus(); keyClick = true">
                                    {{k.label}}
                                </span>
                            </p>
                        </div>
                        <!-- <select :class="[error.key ? 'error' : '','form-control main-select-input']" id="key" @change="changeKey($event)">
                            <option value="" selected></option>
                            <option id="keyVal" v-for="k in keys" :value="k" :key="k">{{ k }}</option>
                        </select> -->
                        <!-- <v-select :options="[{country: 'Canada', code: 'CA'},]"></v-select> -->
                    </div>
                        <!--<select id="location" class="form-select block w-full mt-1" v-model="form.location">
                            <option v-for="country in countries" :value="country.value">
                                {{ country.label }}
                            </option>
                        </select> -->
                    </div>
                    <div class="main-form-row">
                        <div class="main-form-element">
                            <label class="custom-text-label" for="password_confirmation">Description</label><br>
                            <BreezeInput 
                                id="password_confirmation" 
                                type="text" 
                                :class="[error && error.title && error.title[0].includes('required') && !title ? 'error' : '', 'custom-text-input main-text-input']" 
                                v-model="form.description"  
                                autocomplete="new-password"  
                            />
                        </div>
                    </div>
                    <div class="main-form-row">
                        <div class="main-form-element half">
                            <label class="custom-text-label" for="password">Password</label><br>
                            <BreezeInput 
                                id="password" 
                                type="password" 
                                :class="[error && error.title && error.title[0].includes('required') && !title ? 'error' : '', 'custom-text-input main-text-input']" 
                                v-model="form.password" 
                                required 
                                autocomplete="new-password"
                            />
                        </div>
                        <div class="main-form-element half">
                            <label class="custom-text-label" for="password_confirmation">Confirm Password</label><br>
                            <BreezeInput 
                                id="password_confirmation" 
                                type="password" 
                                :class="[error && error.title && error.title[0].includes('required') && !title ? 'error' : '', 'custom-text-input main-text-input']" 
                                v-model="form.password_confirmation" 
                                required 
                                autocomplete="new-password"  
                            />
                        </div>
                    </div>
                    <div class="main-form-row flex items-center justify-end">
                        <div class="main-form-element max">
                            <Link :href="route('login')" class="underline text-sm text-gray-600 hover:text-gray-900">
                            Login
                            </Link>
                        </div>
                        <div class="main-form-element min">
                            <button 
                                class="main-page-form-cta submit" 
                                :class="{ 'opacity-25': form.processing }" 
                                :disabled="form.processing"
                            >
                                Register
                            </button>
                        </div>
                    </div>
                    
                    <BreezeValidationErrors class="mb-4" />
                    <!-- <div class="flex items-center justify-end mt-4">
                        <a :href="route('login')" class="underline text-sm text-gray-600 hover:text-gray-900">
                            Already registered?
                        </a>

                        <button 
                            class="main-page-form-cta submit" 
                            :class="{ 'opacity-25': form.processing }" 
                            :disabled="form.processing"
                        >
                            Register
                        </button>
                    </div> -->
                </form>
            </div>
        </div>

        <!-- <Head title="Register" />

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
                warum autocomplete email??
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

            <div class="flex items-center justify-end mt-4">
                <Link :href="route('login')" class="underline text-sm text-gray-600 hover:text-gray-900">
                    Already registered?
                </Link>

                <BreezeButton @click="loginWeb3" class="ml-4" :class="{ 'opacity-25': form.processing }" :disabled="form.processing">
                    Register
                </BreezeButton>
            </div>

            
        </form> -->
    </div>
</template>

<script>
import BreezeButton from '@/Components/Button.vue'
import BreezeGuestLayout from '@/Layouts/Guest.vue'
import BreezeInput from '@/Components/Input.vue'
import BreezeLabel from '@/Components/Label.vue'
import BreezeValidationErrors from '@/Components/ValidationErrors.vue'
import { Head, Link, useForm } from '@inertiajs/inertia-vue3'

const countries = require('i18n-iso-countries')
countries.registerLocale(require('i18n-iso-countries/langs/en.json'))

export default {

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
                description: '',
                location: '',
            }),
            mouseOpt: false,
            mouseInp: false,
            keyClick: false,
            selectFocus:false,
            error: {},
        }
    },

    computed: {
        countries(){
            const list = countries.getNames('en', { select: 'official' })
            return Object.keys(list).map((key) => ({ value: key, label: list[key] }))
        }
    },

    methods: {
        submit() {
            this.form.post(this.route('register'), {
                onFinish: () => this.form.reset('password', 'password_confirmation'),
            })
        },
        
        changeKey (event) {
            console.log(event.target.value);
            this.form.location = event.target.value
            // this.selectedKey = event.target.options[event.target.options.selectedIndex].text
        }, 
        leave(x){
            console.log(this.mouseOpt + '-'+ this.mouseInp)
            if(!this.mouseOpt && !this.mouseInp){
                this.selectFocus = false;
                this.keyClick = false;
            }
            console.log(x)
            var corrKey = false;
            this.countries.forEach(key => {
                // console.log(key.label +'-'+ this.form.location)
                if(this.form.location == key.label){
                    console.log(key.label)
                    corrKey = true;
                }
            })
            if(!corrKey){
                // this.error.key = ["Please use an existing key."]
            }else if(this.error.key){
                // this.$delete(this.error, 'key')
                // this.deleteError('key')
            }
        },
        mouseOptf(a){
            this.mouseOpt = a;
        },
        mouseInpf(a){
            this.mouseInp = a;
        },
        back(){
history.back()
        },
    }
}
</script>
