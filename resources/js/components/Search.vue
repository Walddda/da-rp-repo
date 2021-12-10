<template>
    <div class="flex items-center justify-center">
    <div class="flex">
        <form action="/" method="post" name="searchForm" ref="form" class="flex flex-row main-search-form">
            <input type="hidden" name="_token" v-bind:value="$page.props.tokens.csrf" />
            <i v-if=" loc == 'feed'" class="main-search-icon">
                <svg class="w-14 h-14 text-gray-600" fill="currentColor" xmlns="http://www.w3.org/2000/svg"
                    viewBox="0 0 24 24">
                    <path
                        d="M16.32 14.9l5.39 5.4a1 1 0 0 1-1.42 1.4l-5.38-5.38a8 8 0 1 1 1.41-1.41zM10 16a6 6 0 1 0 0-12 6 6 0 0 0 0 12z" />
                </svg>
            </i>
            <input type="text" 
                v-if=" loc == 'nav'"
                class="px-4 py-2 w-80 search-text-input-nav" 
                name="searchTerm" 
                v-model="keywords" 
                v-on:keyup.enter="send" 
                :placeholder="'Search ...'" 
                @focus="focus(true)" 
                @blur="focus(false)"
            >
            <input type="text" 
                v-if=" loc == 'feed'"
                class="px-4 py-2 w-80 search-text-input-feed" 
                name="searchTerm" 
                v-model="keywords" 
                v-on:keyup.enter="send" 
                :placeholder='"\"Metro Boomin\""' 
                @focus="focus(true)" 
                @blur="focus(false)"
            >
            <button 
                class="flex items-center justify-center bg-white px-4 search-button-input-feed"
                v-if=" loc == 'feed'"
                @click="send"
            >
                Search
                <!-- <svg class="w-6 h-6 text-gray-600" fill="currentColor" xmlns="http://www.w3.org/2000/svg"
                    viewBox="0 0 24 24">
                    <path
                        d="M16.32 14.9l5.39 5.4a1 1 0 0 1-1.42 1.4l-5.38-5.38a8 8 0 1 1 1.41-1.41zM10 16a6 6 0 1 0 0-12 6 6 0 0 0 0 12z" />
                </svg> -->
            </button>
            <button 
                class="flex items-center justify-center bg-white px-4 search-button-input-nav"
                v-if=" loc == 'nav'"
                @click="send"
            >
                <svg class="w-6 h-6 text-white-600" fill="currentColor" xmlns="http://www.w3.org/2000/svg"
                    viewBox="0 0 24 24">
                    <path
                        d="M16.32 14.9l5.39 5.4a1 1 0 0 1-1.42 1.4l-5.38-5.38a8 8 0 1 1 1.41-1.41zM10 16a6 6 0 1 0 0-12 6 6 0 0 0 0 12z" />
                </svg>
            </button>
        </form>
    </div>
</div>
</template>

<script>
export default {
    data() {
        return {
        };
    },
    props: {
        keywords: {type: String, default: null},
        loc: String,
        feed: Boolean,
    },
    methods: {
        send(e){
            if(this.feed){
                e.preventDefault();
                this.emitter.emit("search", this.keywords);
                // if(window.scrolLY >= 840){
                //     window.scrollY = 840;
                // }
            }
            else{
                console.warn(window.location.href);
            }
        },
        focus(bool){
            this.emitter.emit("focus", bool)
        },
    },
}
</script>
