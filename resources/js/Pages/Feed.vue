<template>
    <Head title="Test" />
    <div>
        <div v-if="files.length > 0">
            <div class="container mb-2 flex mx-auto w-full items-center justify-center">
                <ul class="flex flex-col p-4">
                    <li 
                        v-for="x in files" 
                        class="border-gray-400 flex flex-row my-5">
                        <div class="select-none flex flex-1 items-center p-4 transition duration-500 ease-in-out transform hover:-translate-y-2 rounded-2xl border-2 p-6 hover:shadow-2xl border-red-400">
                            <div class="flex-1 pl-1">
                                    <player :track="x"/>
                            </div>
                        </div>
                    </li>
                </ul>
            </div>

            
            
        </div>
    </div>
</template>

<script>
import BreezeAuthenticatedLayout from "@/Layouts/Authenticated.vue";
import { Head } from "@inertiajs/inertia-vue3";
import Player from '@/Components/Player.vue';




export default {
    components: {
        BreezeAuthenticatedLayout,
        Head,
        Player,
    },

    props: { 
        files: Array,
        paths: Array,
     },

    data() {
        return {
            loadedBeats: false,
        };
    },
    methods: {
        loadBeats() {},
    },
};
</script>
