<template>
    <div>
    <div class="main-page-form-div responsive">
            <div class="main-page-form-heading">
                <div class="heading-title">Password</div>
                <div class="heading-logo"></div>
            </div>
            <div class="main-page-form-content">
                <div class="content-back">
                </div>
                <div class="content-form ">
                    <Head title="Register" />
                    <form @submit.prevent="submit" class="box-border">
                        <div class="main-form-row">
                            <div class="main-form-element">
                                <label class="custom-text-label" for="old_password">Old Password</label><br>
                                <BreezeInput 
                                    id="old_password" 
                                    type="text" 
                                    :class="[error && error.title && error.title[0].includes('required') && !title ? 'error' : '', 'custom-text-input main-text-input']" 
                                    v-model="old_password" 
                                    required 
                                />
                            </div>
                        </div>
                        <div class="main-form-row">
                            <div class="main-form-element">
                                <label class="custom-text-label" for="new_password">New Password</label><br>
                                <BreezeInput 
                                    id="new_password" 
                                    type="text" 
                                    :class="[error && error.title && error.title[0].includes('required') && !title ? 'error' : '', 'custom-text-input main-text-input']" 
                                    v-model="new_password" 
                                    required 
                                />
                            </div>
                        </div><div class="main-form-row">
                            <div class="main-form-element">
                                <label class="custom-text-label" for="confirm_password">Confirm new Password</label><br>
                                <BreezeInput 
                                    id="confirm_password" 
                                    type="text" 
                                    :class="[error && error.title && error.title[0].includes('required') && !title ? 'error' : '', 'custom-text-input main-text-input']" 
                                    v-model="password_confirmation" 
                                    required
                                />
                            </div>
                        </div>
                        
                        <div class="main-form-row flex items-center">
                            <div class="main-form-element max">
                                <button 
                                    class="main-page-form-cta submit" 
                                    @click.prevent="updatePassword"
                                >
                                    Change Password
                                </button>
                            </div>
                        </div>
                        
                        <BreezeValidationErrors class="mb-4" />
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
import BreezeValidationErrors from '@/Components/ValidationErrors.vue';
import { Head, Link } from '@inertiajs/inertia-vue3';
import { CogIcon } from '@heroicons/vue/outline';
import NavBarNew from '@/Components/NavBarNew.vue'
import Mobile from '@/Components/Mobile.vue'

export default {
    name: "ChangePassword", 
    
    components: {
        BreezeButton,
        BreezeInput,
        BreezeLabel,
        BreezeValidationErrors,
        Head,
        Link,
        CogIcon,
        NavBarNew,
        Mobile
    },
    data() {
        return{
            old_password: '', 
            new_password: '', 
            password_confirmation: '',
            error: {},
        }
    },

    methods: {
        updatePassword(){
            axios.post('/change-password', {
                old_password: this.old_password,
                password: this.new_password,
                password_confirmation: this.password_confirmation
            })
            .then((res) => {
                console.log(res.data)
                if(res.data.status == 'success'){
                window.location.href = "/profile/" + this.$page.props.auth.user.username 
                
                this.emitter.emit('success', 'Password was successfully changed')

                } else if(res.data.status == 'fail'){
                    alert("Old Password is wrong")
                } else if(res.data.status == 'match'){
                    alert("Passwords dont match")
                } 
            }).catch((err) => {
                console.log(err)
            })

        }
    }, 
}
</script>

<style>

</style>
