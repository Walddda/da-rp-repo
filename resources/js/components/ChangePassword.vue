<template>
    <div>
        <form>
            <div>
                <label for="old_password">Old Password</label>
                <input type="text" v-model="old_password" id="old_password" placeholder="Enter your old password">
            </div>

            <div>
                <label for="new_password">New Password</label>
                <input type="text" v-model="new_password" id="new_password" placeholder="Enter your old password">
            </div>

            <div>
                <label for="confirm_password">Confirm Password</label>
                <input type="text" v-model="password_confirmation" id="password_confirmation" placeholder="Enter your old password">
            </div>

            <div>
                <button class="btn btn-primary" @click.prevent="updatePassword">Change Password</button>
            </div>
        </form>


    </div>
</template>

<script>

export default {
    name: "ChangePassword", 

    data() {
        return{
            old_password: '', 
            new_password: '', 
            password_confirmation: '',
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
