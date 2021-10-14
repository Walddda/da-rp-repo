<template>
    <Head title="Test" />
    <div>
        <div>
            <hr>
            Penis:
            {{ beats.length }}
            <!-- <div v-if="beats.length > 0">
                <ul>
                    <li :v-for="x in beats">abc</li>
                </ul>
            </div> -->
            <!-- <div  :v-if="beats" :v-for="beat in beats" :key="beat.title">
                {{ beat.title }}
            </div> -->
            <!-- <test-comp-vue :v-if="beats.length" :v-for="(n, i) in beats" :beats="beats[i]"></test-comp-vue> -->
            <hr>

            <!-- <BreezeAuthenticatedLayout>
                <template #header>
                    <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                        Dashboard
                    </h2>
                </template>

                <div class="py-12">
                    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                        <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                            <div class="p-6 bg-white border-b border-gray-200">
                                You're logged in!
                            </div>
                        </div>
                    </div>
                </div>
            </BreezeAuthenticatedLayout> -->
        </div>
        <!-- <div>
            Loading
        </div> -->
    </div>
</template>

<script>
import BreezeAuthenticatedLayout from '@/Layouts/Authenticated.vue'
import { Head } from '@inertiajs/inertia-vue3';


export default {
    components: {
        BreezeAuthenticatedLayout,
        Head,
    },

    props: {'beats': Array},

    data() {
        return {
            loadedBeats: false,
        }
    },
    methods: {
        loadBeats(){

        }
    }
}
</script>
