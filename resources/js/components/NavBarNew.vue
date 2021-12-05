<template>
  <nav v-if="backgroundOp || backgroundOp == 0" class="fixed top-0 h-32 w-full" :style="[backgroundOp >= 1 ? {backgroundColor: 'rgba(0,0,0,'+backgroundOp+')'} :'' ]" ref="nav">
    <div class="mx-auto px-2">
        <div class="relative flex justify-between h-32 pt-8">
            <!-- <div class="flex items-center  space-x-4 h-16 pl-5" :style="[backgroundOp >= 1 ? {color: 'black'} : {color:'rgb('+(255-backgroundOp*255*2)+','+(255-backgroundOp*255*2)+','+(255-backgroundOp*255*2)+')'} ]"> -->
            <div class="flex items-center space-x-4 h-16 pl-5" v-if="$page.props.auth.user">
                <a v-for="item in navigationLoged" :key="item.name" :href="item.href" @click="item.click" @mouseover="item.mouseEnter" class="nav-link pl-10" >{{ item.name }}</a>
                <div v-if="showDrop" class="absolute w-40 bg-opacity-0 mt-48" @mouseleave="mouseLeave">
                    <a href="/" class="nav-link">
                        Home
                    </a>
                    <a :href="'/myprofile/' + this.$page.props.auth.user.username" class="nav-link">
                        Profile
                    </a>
                    <a href="/settings" class="nav-link">
                        Settings
                    </a>
                    <a href="#" class="nav-link">
                        Logout
                    </a>
                </div>
            </div>
            <div class="flex items-center  space-x-4 h-16 pl-5" v-else>
                <a v-for="item in navigation" :key="item.name" :href="item.href" @click="item.click" class="nav-link pl-10" >{{ item.name }}</a>
            </div>
            <!-- [item.current ? 'bg-gray-900 text-white' : 'text-gray-300 hover:bg-gray-700 hover:text-white', 'px-6 py-3 rounded-md text-4xl font-medium']" 
            :aria-current="item.current ? 'page' : undefined" -->
            <!--  :@click="item.click" -->
            <search :keywords="searchTerm" v-if="showSearch || searchTerm "></search>
            <div class="pr-8">
                <img src="/storage/assets/logo_w.png" class="h-44 w-auto"/>
            </div>
        </div>
    </div>
  </nav>
  <nav v-else class="fixed top-0 h-32 w-full" :style="{backgroundColor: 'rgba(0,0,0,1)'}" ref="nav">
    <div class="mx-auto px-2">
        <div class="relative flex justify-between h-32 pt-8">
            <!-- <div class="flex items-center  space-x-4 h-16 pl-5" :style="[backgroundOp >= 1 ? {color: 'black'} : {color:'rgb('+(255-backgroundOp*255*2)+','+(255-backgroundOp*255*2)+','+(255-backgroundOp*255*2)+')'} ]"> -->
            <div class="flex items-center space-x-4 h-16 pl-5" v-if="$page.props.auth.user">
                <a v-for="item in navigationLoged" :key="item.name" :href="item.href" @click="item.click" @mouseover="item.mouseEnter" class="nav-link pl-10" >{{ item.name }}</a>
                <div v-show="showDrop" class="absolute w-40 bg-black mt-48" @mouseleave="mouseLeave">
                    <a href="/" class="nav-link">
                        Home
                    </a>
                    <a :href="'/myprofile/' + this.$page.props.auth.user.username" class="nav-link">
                        Profile
                    </a>
                    <a href="/settings" class="nav-link">
                        Settings
                    </a>
                    <a href="#" class="nav-link">
                        Logout
                    </a>
                </div>
            </div>
            <div class="flex items-center  space-x-4 h-16 pl-5" v-else>
                <a v-for="item in navigation" :key="item.name" :href="item.href" @click="item.click" class="nav-link pl-10" >{{ item.name }}</a>
            </div>
            <!-- [item.current ? 'bg-gray-900 text-white' : 'text-gray-300 hover:bg-gray-700 hover:text-white', 'px-6 py-3 rounded-md text-4xl font-medium']" 
            :aria-current="item.current ? 'page' : undefined" -->
            <!--  :@click="item.click" -->
            <search :keywords="searchTerm" v-if="showSearch || searchTerm "></search>
            <div class="pr-8">
                <img src="/storage/assets/logo_w.png" class="h-44 w-auto"/>
            </div>
        </div>
    </div>
  </nav>
</template>

<script>
import Search from '@/Components/Search.vue';
import { UploadIcon, MenuIcon, XIcon } from '@heroicons/vue/outline';

// const navigation = [
//   { name: 'Sign in', href: '#', current: false, click: this.test },
//   { name: 'Sign up', href: '#', current: false, click: '' },
//   { name: 'Search', href: '#', current: false, click: this.toggleSearch },
// ]

export default {

    name: 'NavBarNew',
    components: {
        Search,
        UploadIcon,
    },
    props:{
        searchTerm: {type: String, required: false},
        backgroundOp: {type: Number, required: false},
    },

    data() {
      return {
        show: false,
        showSearch: false,
        showDrop: false,
        mouseLeave: this.mouseLeave,
        navigation: [
                    { name: 'Sign in', href: '/login', current: false, click: '' },
                    { name: 'Sign up', href: '/register', current: false, click: '' },
                    { name: 'Search', href: '#', current: false, click: this.toggleSearch },
                    ],
        navigationLoged: [
                    { name: 'You shouldn\'t be logged in lol', href: '/myprofile/' + this.$page.props.auth.user.username, current: false, click: '' , mouseEnter: this.mouseEnter, mouseLeave: this.mouseLeave},
                    { name: 'Upload', href: '#', current: false, click: this.toggleUpload },
                    { name: 'Wallet', href: '#', current: false, click: this.toggleWallet },
                    { name: 'Search', href: '#', current: false, click: this.toggleSearch },
                    ],
        lastScroll: 0,
      }
    },
    methods: {
        toggleSearch(){
            this.showSearch = !this.showSearch
        },
        
        toggleUpload(){
            this.emitter.emit('openPopupUpload')
        },
        toggleWallet(){
            this.emitter.emit('openPopupWallet')
        },
        mouseEnter() {
            this.showDrop = !this.showDrop;
        },
        mouseLeave() {
            this.showDrop = false;
        },
        test(){
            console.log('lol')
        },
    },
    mounted(){
        if(this.$page.props.auth.user){
            this.navigationLoged[0].name = this.$page.props.auth.user.username
        }
    }

}
</script>

<style>

</style>