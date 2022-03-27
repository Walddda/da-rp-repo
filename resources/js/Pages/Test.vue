<template>
    <Head title="Test" />
    <div>
        <div v-if="beats.length > 0">
            <hr />
            Test:
            {{text}}
            {{beats[1].likes2.length}}
            <pre>
            {{ beats[1] }}
            </pre>
            <!--
            <ul>
                <li v-for="x in beats">
                    {{x.title}} {{x.likes}} &lt;3
                    </li>
            </ul> -->
            
            <!-- <div  :v-if="beats" :v-for="beat in beats" :key="beat.title">
                {{ beat.title }}
            </div> -->
            <!-- <test-comp-vue :v-if="beats.length" :v-for="(n, i) in beats" :beats="beats[i]"></test-comp-vue> -->
            <hr />

            <!-- <nav-bar></nav-bar> -->

        </div>
        <!-- <div>
            Loading
        </div> -->
    </div>
</template>

<script>
import BreezeAuthenticatedLayout from "@/Layouts/Authenticated.vue";
import { Head } from "@inertiajs/inertia-vue3";
import NavBar from '@/Components/NavBar.vue';

export default {
    components: {
        BreezeAuthenticatedLayout,
        Head,
        NavBar
    },

    props: { beats: Array, text: String },

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
