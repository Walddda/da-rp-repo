<template>
    <div class="flex items-center justify-center">
    <div class="flex">
        <form action="/" method="post" name="searchForm" ref="form">
            <input type="hidden" name="_token" v-bind:value="$page.props.tokens.csrf" />
            <input type="text" class="px-4 py-2 w-80" name="searchTerm" v-model="keywords" v-on:keyup.enter="send" placeholder="Search ... " @focus="focus(true)" @blur="focus(false)">
            <button class="flex items-center justify-center bg-white px-4" @click="send">
                <svg class="w-6 h-6 text-gray-600" fill="currentColor" xmlns="http://www.w3.org/2000/svg"
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
    },
    methods: {
        send(e){
            if(window.location.href == 'http://localhost:8000/'){
                e.preventDefault();
                this.emitter.emit("search", this.keywords);
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
