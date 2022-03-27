<template>
    <div class="inline pl-10">
        <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.15.4/css/all.css" integrity="sha384-DyZ88mC6Up2uqS4h/KRgHuoeGwBcD4Ng9SiP4dIRy0EXTlnuz47vAwmeGwVChigm" crossorigin="anonymous"/>
        <a class="nav-item dropdown">
            <!-- <a id="navbarDropdown" class="nav-link dropdown-toggle" href="#" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                <i class="fa fa-bell" id="notify_icon"></i>
                <span id="notifiy_num" v-show="unreadNotifications.length > 0">{{unreadNotifications.length}}</span>
            </a> -->
            <div class="dropdown-menu dropdown-menu-right main-noti text-center" aria-labelledby="navbarDropdown">
                <a class="dropdown-item" v-for="(unread, index) in unreadNotifications" :key="index">
                    <div v-if="unread.type == 'App\\Notifications\\LikeNotification'">
                        <h5>{{unread.data.username}} liked your track <strong>{{unread.data.title}}</strong></h5>
                    </div>
                    <div v-else-if="unread.type === 'App\\Notifications\\PaymentNotification'">
                        <h5>{{unread.data.username}} purchased your track <strong>{{unread.data.title}}</strong></h5>
                    </div>
                    <div v-else-if="unread.type === 'App\\Notifications\\DownloadCountNotification'">
                        <h5>Congratulations your track <strong>{{unread.data.title}}</strong> has reached <strong>{{unread.data.downloadCount}}</strong> Downloads</h5>
                    </div>
                </a>
                <button v-show="unreadNotifications.length > 0" @click="markAsRead">Mark all as read!</button>
                <a class="dropdown-item" v-show="unreadNotifications.length == 0">No new notifications</a>
            </div>
        </a>
    </div>
</template>

<script>
    export default {
        name: 'Notification',

        mounted() {
            this.getNotifications()
            // this.interval = setInterval(function() {
            //     this.getNotifications()
            // }
            // .bind(this), 500);
        },

        data(){
            return {
                unreadNotifications: {},
            }
        }, 
        methods: {
            getNotifications(){
                axios.get('/unreadNotifications')
                .then(response => {
                    this.unreadNotifications = response.data
                    this.$emit('unreadCount', response.data.length)
                })
                .catch(error => {
                    console.log(error)
                });
            },
            markAsRead() {
                axios.get('/markNotificationsAsRead')
                .then(response => {
                    this.getNotifications()
                })
                .catch(error => {
                    console.log(error)
                })
            }
            
        }
    };
</script>

<style>
button{
    color: #9034ec;
    font-weight: bold;
}
</style>
