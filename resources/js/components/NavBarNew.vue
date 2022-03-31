<template>
  <nav class="fixed top-0 h-32 w-full" id="feedNav" :style="[backgroundOp >= 1 ? {backgroundColor: 'rgba(0,0,0,'+backgroundOp+')'} : [backgroundOp || backgroundOp == 0 ? '' : {backgroundColor: 'rgb(0,0,0)'} ]]"  ref="feedNav">
    <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.15.4/css/all.css" integrity="sha384-DyZ88mC6Up2uqS4h/KRgHuoeGwBcD4Ng9SiP4dIRy0EXTlnuz47vAwmeGwVChigm" crossorigin="anonymous"/>
    <div class="mx-auto px-2">
        <div class="relative flex justify-between h-32 pt-8">
            <div class="flex flex-col items-center space-x-4 pl-5" :class="[showNoti ? 'h-32' : 'h-16']" v-if="$page.props.auth.user">
                <div v-if="profile">
                    <span class="nav-link main-noti-span" @click="showNoti = !showNoti">
                        <i class="fa fa-bell main-noti-icon" id="notify_icon"></i>
                        <span style="color:white" id="notifiy_num" class="main-noti-indic" v-show="unreadNoti > 0 && unreadNoti < 10"> {{ unreadNoti }}</span>
                        <span style="color:white; padding-left: 2px;" id="notifiy_num" class="main-noti-indic" v-show="unreadNoti > 0 && unreadNoti > 9">&#32;!</span>
                    </span>

                    <div class="main-noti-back" @click="hideNotis" v-show="showNoti"></div>
                    <a href="/" @mouseenter="mouseEnter" @mouseleave="mouseLeavesMeth" class="nav-link ml-12">Home</a>
                    <a v-for="item in navigationProfile" :key="item.name" :href="item.href" @click="item.click" @mouseover="item.mouseEnter" class="nav-link pl-10" :style="item.style" >{{ item.name }}</a>
                    <span class="nav-link pl-10 inline" @click="toggleSearch" >Search</span>
                    <notification @unreadCount="(n) => unreadNoti = n" v-show="showNoti"></notification>

                    <div v-if="showDrop" class="absolute flex main-drop-list" @mouseleave="mouseLeavesMeth" @mouseenter="mouseEnter">
                        <a :href="'/profile/' + this.$page.props.auth.user.username" class="nav-link">
                            Profile
                        </a>
                        <a href="/" class="nav-link">Home</a>
                        <a href="/settings" class="nav-link">
                            Settings
                        </a>
                        <a v-if="$page.props.auth.user.isadmin" href="/admin" class="nav-link">
                            Admin
                        </a>
                        <a :href="route('logout')" onclick="event.preventDefault(); document.getElementById('logout-form').submit();" class="nav-link">Logout</a>

                        <form id="logout-form" :action="route('logout')" method="POST" style="display: none;">
                            <input type="hidden" name="_token" v-bind:value="$page.props.tokens.csrf" />
                        </form>
                    </div>

                </div>
                <div v-else >
                    <span class="nav-link main-noti-span" @click="showNoti = !showNoti">
                        <i class="fa fa-bell main-noti-icon" id="notify_icon"></i>
                        <span style="color:white" id="notifiy_num" class="main-noti-indic" v-show="unreadNoti > 0 && unreadNoti < 10"> {{ unreadNoti }}</span>
                        <span style="color:white; padding-left: 2px;" id="notifiy_num" class="main-noti-indic" v-show="unreadNoti > 0 && unreadNoti > 9">&#32;!</span>
                    </span>
                    <div class="main-noti-back" @click="hideNotis" v-show="showNoti"></div>
                    <a :href="userhref" @mouseenter="mouseEnter" @mouseleave="mouseLeavesMeth" class="nav-link ml-12">{{username}}</a>
                    <a v-for="item in navigationLoged" :key="item.name" :href="item.href" @click="item.click" @mouseenter="item.mouseEnter" @mouseleave="item.mouseLeave" class="nav-link ml-10" :style="item.style" >{{ item.name }}</a>
                    <span class="nav-link ml-10 inline cursor-pointer" @click="toggleSearch" :style="[this.backgroundOp <= 0.9 ? {display: 'none'} : this.backgroundOp <= 1 ?  {opacity: (-4+(this.backgroundOp/0.2)), display: 'inline'} : {display:'inline'}]">Search</span>
                    <notification @unreadCount="(n) => unreadNoti = n" v-show="showNoti"></notification>
                    <div v-if="showDrop" class="absolute flex main-drop-list" @mouseleave="mouseLeavesMeth" @mouseenter="mouseEnter">
                        <a :href="'/profile/' + this.$page.props.auth.user.username" class="nav-link">
                            Profile
                        </a>
                        <a href="/settings" class="nav-link">
                            Settings
                        </a>
                        <a v-if="$page.props.auth.user.isadmin" href="/admin" class="nav-link">
                            Admin
                        </a>
                        <a :href="route('logout')" onclick="event.preventDefault(); document.getElementById('logout-form').submit();" class="nav-link">Logout</a>

                        <form id="logout-form" :action="route('logout')" method="POST" style="display: none;">
                            <input type="hidden" name="_token" v-bind:value="$page.props.tokens.csrf" />
                        </form>
                    </div>

                    
                </div>
            </div>
            <div class="flex items-center  space-x-4 h-16 pl-5" v-else>
                <a v-for="item in navigation" :key="item.name" :href="item.href" @click="item.click" class="nav-link pl-10" >{{ item.name }}</a>
                    <span class="nav-link ml-10 pl-10 inline cursor-pointer" @click="toggleSearch" :style="[this.backgroundOp <= 0.9 ? {display: 'none'} : this.backgroundOp <= 1 ?  {opacity: (-4+(this.backgroundOp/0.2)), display: 'inline'} : {display:'inline'}]">Search</span>
            </div>
            <search :keywords="searchTerm" v-if="showSearch || searchTerm " loc="nav" :feed="feed"
                :style="[backgroundOp <= 0.9 ? {opacity: 0} : backgroundOp <= 1 ?  {opacity: (-4+(backgroundOp/0.2))} : '']"
            ></search>
            <div class="flex flex-auto"></div>
            <div class="pr-8">
                <a href="/">
                    <svg id="Layer_1" data-name="Layer 1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" viewBox="0 0 1245.94 693.88" class="h-44 w-auto"
                        :style="[backgroundOp >= 1 || (!backgroundOp && backgroundOp != 0) ? {marginTop: '-95px'} : [backgroundOp >= 0.88 ? {marginTop: ((-83.6+95*backgroundOp)/-0.12)+'px'} : '']]"
                    >
                        <defs>
                            <linearGradient id="gradient" x1="28.38" y1="511.63" x2="1249.68" y2="511.63" gradientUnits="userSpaceOnUse">
                                <stop offset="0.1" stop-color="#8f35e8" />
                                <stop offset="0.9" stop-color="#e20000" />
                            </linearGradient>
                        </defs>
                        <path d="M995.55,314a282.13,282.13,0,0,0-32,1.74,288.52,288.52,0,0,0-83.46,20,251.33,251.33,0,0,0-62.67,35A244.29,244.29,0,0,0,755,336.14,277.32,277.32,0,0,0,672.53,316,251.74,251.74,0,0,0,639,314a299.45,299.45,0,0,0-32.71,1.74,305.13,305.13,0,0,0-83.14,20A268.12,268.12,0,0,0,460.61,371a261,261,0,0,0-62.47-35.19A282.93,282.93,0,0,0,316,316,251.74,251.74,0,0,0,282.5,314C142.38,314,28.38,402.71,28.38,511.76s114,197.52,254.12,197.52a283,283,0,0,0,32-1.74,288.13,288.13,0,0,0,83.4-20,250.06,250.06,0,0,0,62.68-35,245.77,245.77,0,0,0,62.46,34.61,276.47,276.47,0,0,0,82.41,20.1A254.22,254.22,0,0,0,639,709.28a297.67,297.67,0,0,0,32.65-1.74,305.57,305.57,0,0,0,83.2-20,267.33,267.33,0,0,0,62.52-35.24,261.76,261.76,0,0,0,62.51,35.19A282.37,282.37,0,0,0,962,707.22a254.22,254.22,0,0,0,33.55,2.06c140.12,0,254.13-88.74,254.13-197.78S1135.67,314,995.55,314Zm0,350.77a59.21,59.21,0,0,1-6.91-.26l-.68,0a261.55,261.55,0,0,1-81.51-14.51,217.27,217.27,0,0,1-56.29-28.44,178.34,178.34,0,0,0,31.65-51.38,35.6,35.6,0,0,0,2.32-6.49,155.21,155.21,0,0,0-.1-104.56l-4.22-11.66L871,456.21c-.85.84-1.8,1.69-2.85,2.69-1.43,1.37-3.06,3-5,4.85l-18.2,18.89,1,4.22a110.68,110.68,0,0,1,2.64,24.64,118.48,118.48,0,0,1-2.38,23.84c-5,22.69-17,44.32-35,62.84a97.65,97.65,0,0,1-13,12.5l-.73.63a54,54,0,0,1-6.18,5.12c-17.83,14-39.25,25.37-63.73,33.82a264.67,264.67,0,0,1-82.88,14.19l-1,.1a31.68,31.68,0,0,1-4.64.21,59,59,0,0,1-6.91-.26l-.69,0a254.44,254.44,0,0,1-81.51-14.51,217.93,217.93,0,0,1-56.29-28.38,167.9,167.9,0,0,0,27.12-41.73,103.13,103.13,0,0,0,6.75-15.94,151.28,151.28,0,0,0,9.13-52.12,153.05,153.05,0,0,0-9.13-52.7l-3.9-10.71L515,455.79a52.49,52.49,0,0,0-5.65,5.85L488.83,484.8l.79,3.91a107.89,107.89,0,0,1,2.43,23.05,114.68,114.68,0,0,1-2.43,23.69l-.1.84c-5.17,22.42-17,43.84-34.19,61.83a176,176,0,0,1-13.56,12.66l-.52.53a43,43,0,0,1-6.28,5.07l-.48.37c-17.83,13.92-39.3,25.32-63.78,33.76a264.86,264.86,0,0,1-83.14,13.93l-1.22.1a21.16,21.16,0,0,1-3.85.21C167,664.75,73,596.12,73,511.76S167,358.5,282.5,358.5a55.63,55.63,0,0,1,6.86.27l.74.05a261.86,261.86,0,0,1,81.51,14.51,218.35,218.35,0,0,1,56.29,28.43,178.6,178.6,0,0,0-31.71,51.39,40.42,40.42,0,0,0-2.32,6.49A155.94,155.94,0,0,0,394,564.2l4.27,11.66,8.76-8.81c.84-.84,1.79-1.69,2.85-2.69,1.47-1.37,3.11-3,5-4.86l18.25-18.88-1-4.22a107.75,107.75,0,0,1-2.69-24.64,117.87,117.87,0,0,1,2.43-23.85c5-22.68,17-44.31,35-62.83a98.08,98.08,0,0,1,13-12.5l.74-.63a60.3,60.3,0,0,1,6.22-5.12c17.78-14,39.26-25.38,63.73-33.82a264.14,264.14,0,0,1,82.83-14.19l1.06-.1a30.19,30.19,0,0,1,4.64-.22,56.12,56.12,0,0,1,6.86.27l.74.05a254.65,254.65,0,0,1,81.5,14.51,218.57,218.57,0,0,1,56.29,28.38,166,166,0,0,0-27.11,41.73,103.57,103.57,0,0,0-6.81,15.93,152.55,152.55,0,0,0-9.07,52.13,154.44,154.44,0,0,0,9.07,52.7l3.91,10.71,8.7-7.44a51.12,51.12,0,0,0,6-6.22l20.16-22.79-.85-3.91a112,112,0,0,1-2.43-23,119,119,0,0,1,2.43-23.69l.16-.84c5.12-22.43,17-43.79,34.29-61.94,4.59-4.54,8.86-8.86,13.4-12.56l.58-.52a42.49,42.49,0,0,1,6.28-5.07l.47-.37c17.78-13.93,39.25-25.32,63.73-33.76a265.17,265.17,0,0,1,83.15-13.93l1.21-.1a20.82,20.82,0,0,1,3.9-.22c115.54,0,209.55,68.64,209.55,153S1111.09,664.75,995.55,664.75Z" 
                            transform="translate(-16.06 -15.4)" style="fill:url(#gradient)" 
                            :style="[backgroundOp >= 1 || (!backgroundOp && backgroundOp != 0) ? {fill: '#fff'} : '']"
                        />
                        <path d="M156.34,149.7c0,42.4-25.89,68.87-61.75,68.87-22.19,0-37-11.1-44.67-27h-.57a135.86,135.86,0,0,1-3.41,25.32H16.06V16H55v86.22c7.4-12,20.2-21.35,41.54-21.35C130.45,80.84,156.34,105.89,156.34,149.7Zm-39.55,0c0-22.19-11.38-36.13-30.73-36.13-19.92,0-31,17.07-31,31.87v8.82c0,15.93,11.67,31.58,31.87,31.58C105.12,185.84,116.79,171.9,116.79,149.7Z" 
                            transform="translate(-16.06 -15.4)" style="fill:#fff" />
                        <path d="M286.36,173.61,304.86,195c-9.11,12-28.74,23.62-57.77,23.62-45.24,0-72.27-28.46-72.27-68.87,0-40.12,27-68.86,68.57-68.86,39.27,0,61.75,26.75,61.75,63.46A80,80,0,0,1,304,158.53H213.52c3.41,17.92,15.93,30.44,38.13,30.44C270.71,189,280.67,179.87,286.36,173.61Zm-72.84-35.29h56c-.28-15.93-10-27.88-26.18-27.88C228,110.44,216.36,120.39,213.52,138.32Z" 
                            transform="translate(-16.06 -15.4)" style="fill:#fff" />
                        <path d="M462.77,216a71.22,71.22,0,0,1-19.07,2.56c-18.49,0-28.45-7.12-31-21.34-4.27,8.25-15.37,21.34-43.26,21.34-29,0-45.52-14.51-45.52-37,0-33.3,35-41.55,85.08-42.69h1.7v-6c0-13.65-8-22.76-24.47-22.76-14.8,0-23.05,6-23.05,15.65a9.77,9.77,0,0,0,1.14,5.12l-34.14,5.69a50.33,50.33,0,0,1-1.71-13.09c0-25.6,22.48-42.68,59.47-42.68,39.55,0,60.89,19.92,60.89,53.78v43.83c0,6.26,2.28,8.82,7.4,8.82a13.27,13.27,0,0,0,4.84-.86Zm-52.08-46.67V158.53H409c-32.16.85-47.24,5.4-47.24,17.92,0,8.83,7.11,13.38,18.78,13.38C399.31,189.83,410.69,180.15,410.69,169.34Z" 
                            transform="translate(-16.06 -15.4)" style="fill:#fff" />
                        <path d="M585.12,208.89s-14.23,9.68-40.13,9.68c-35.28,0-53.78-19.07-53.78-57.2V112.14H470.72V82.55h22.2V44.71l37.56-1.42V82.55h47.24v29.59H530.48v47.52c0,17.93,6.83,25.9,21.91,25.9,13.66,0,21.63-7.11,21.63-7.11Z" 
                            transform="translate(-16.06 -15.4)" style="fill:#fff" />
                        <path d="M719.41,128.08a52.69,52.69,0,0,1-.85,10l-34.43,2a22.5,22.5,0,0,0,.57-6c0-11.38-7.4-21.34-22.2-21.34-17.64,0-29.59,14.51-29.59,36.14,0,22.77,11.67,37.85,34.72,37.85,21,0,32.72-10.82,36.7-15.65l19.07,24.47c-8.54,10.81-28.17,23-58.34,23-45,0-71.7-28.46-71.7-68.3,0-41.25,28.45-69.43,69.71-69.43C698.64,80.84,719.41,101.9,719.41,128.08Z" 
                            transform="translate(-16.06 -15.4)" style="fill:#fff" />
                        <path d="M875.9,134.34v82.52H836.63V141.17c0-15.94-7.68-26.75-23.61-26.75-19.92,0-28.46,17.07-28.46,33v69.43h-39V16h38.13l.28,88.49h.86c6.82-13.66,19.06-23.62,40.68-23.62C859.11,80.84,875.9,103.89,875.9,134.34Z" 
                            transform="translate(-16.06 -15.4)" style="fill:#fff" />
                        <path d="M1038.36,216a71.1,71.1,0,0,1-19.06,2.56c-18.5,0-28.46-7.12-31-21.34-4.27,8.25-15.36,21.34-43.25,21.34-29,0-45.53-14.51-45.53-37,0-33.3,35-41.55,85.08-42.69h1.71v-6c0-13.65-8-22.76-24.47-22.76-14.8,0-23.05,6-23.05,15.65a9.68,9.68,0,0,0,1.14,5.12l-34.15,5.69a50.6,50.6,0,0,1-1.7-13.09c0-25.6,22.47-42.68,59.47-42.68,39.55,0,60.89,19.92,60.89,53.78v43.83c0,6.26,2.28,8.82,7.4,8.82a13.34,13.34,0,0,0,4.84-.86Zm-52.07-46.67V158.53h-1.71c-32.15.85-47.23,5.4-47.23,17.92,0,8.83,7.11,13.38,18.78,13.38C974.91,189.83,986.29,180.15,986.29,169.34Z" 
                            transform="translate(-16.06 -15.4)" style="fill:#fff" />
                        <path d="M1080.46,63.77c-13.94,0-25-10-25-24.18,0-13.94,11.1-24.19,25-24.19s25.33,10.25,25.33,24.19C1105.79,53.81,1094.41,63.77,1080.46,63.77Zm-19.63,18.78,39-1.14V216.86h-39Z" 
                            transform="translate(-16.06 -15.4)" style="fill:#fff" />
                        <path d="M1262,134.34v82.52h-39V141.17c0-15.94-8-26.75-23.62-26.75-19.92,0-28.46,17.36-28.46,33v69.43h-39V82.55l30.45-1.14c2.84,8.82,4.83,22.48,4.83,28.17h1.14c6.55-15.93,19.64-28.74,43.54-28.74C1245.21,80.84,1262,103.89,1262,134.34Z"
                            transform="translate(-16.06 -15.4)" style="fill:#fff" />
                    </svg>
                </a>
            </div>
        </div>
    </div>
  </nav>
</template>

<script>
import Search from '@/Components/Search.vue';
import { UploadIcon, MenuIcon, XIcon } from '@heroicons/vue/outline';
import Notification from "@/Components/Notification.vue";
import { useForm } from '@inertiajs/inertia-vue3'

export default {

    name: 'NavBarNew',
    components: {
        Search,
        UploadIcon,
        Notification,
        useForm,
    },
    props:{
        searchTerm: {type: String, required: false},
        backgroundOp: {type: Number, required: false},
        feed: Boolean,
        profile: Boolean,
    },

    data() {
      return {
        show: false,
        showSearch: false,
        showDrop: false,
        showNoti: false,
        mouseLeaves: this.mouseLeaves,
        unreadNoti: 0,
        username: '',
        userhref: '',
        accounts: '',
        address: '',
        navigation: [
                    { name: 'Login', href: '/login', current: false, click: '' },
                    { name: 'Register', href: '/register', current: false, click: '' },
                    ],
        navigationLoged: [
                    { name: 'Upload', href: '#', current: false, click: this.toggleUpload },
                    { name: 'Wallet', href: '#', current: false, click: this.connect },
                    ],
        navigationProfile: [
                    { name: 'Upload', href: '#', current: false, click: this.toggleUpload },
                    { name: 'Wallet', href: '#', current: false, click: this.connect },
                    ],
        lastScroll: 0,
      }
    },
    methods: {
        toggleSearch(){
            this.showSearch = !this.showSearch
        },
        
        toggleUpload(){
            if (ethereum.selectedAddress) {
                this.emitter.emit('openPopupUpload')
            } else {
                this.emitter.emit('error', 'Connect Wallet to upload')
            }
        },
        async connect() {
            if (this.$page.props.auth.user) {
                if (typeof window.ethereum !== 'undefined') {
                    this.accounts = await ethereum.request({ method: 'eth_requestAccounts' })
                    this.address = this.accounts[0]
                    this.emitter.emit('success', 'Your wallet was successfully connected.')
                    return axios.post('/wallet', {address: this.address})
                } else {
                    this.emitter.emit('error', 'MetaMask not detected')
                }
            } else {
                this.emitter.emit('error', 'Login to connect a wallet')
            }
        },
        mouseEnter() {
            this.showDrop = true;
        },
        mouseLeavesMeth() {
            this.showDrop = false;
        },
        test(){
            
        },
        showNotis(){
            this.showNoti = true;
        },
        hideNotis(){
            this.showNoti = false;
        },
        closeNoti(){
            
        },
    },
    mounted(){
        if(this.$page.props.auth.user){
            this.username = this.$page.props.auth.user.username
            this.userhref = '/profile/'+this.$page.props.auth.user.username
        }
        
    },

    created() {
        if (typeof window.ethereum !== 'undefined') {
        ethereum.on('accountsChanged', this.connect)
        }
    }

}
</script>

<style>

.fa-bell{
    color:#9034ec;
}

</style>