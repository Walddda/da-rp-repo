<template>
    <div>
        <input type="text" v-model="keywords">
        <ul v-if="results.length > 0">
            <!-- <li v-for="result in results" :key="result.id" v-text="result.title"></li> -->
            <li v-for="(x, k) in results">
                {{x}}: {{k}}
                
            </li>
        </ul>
    </div>
</template>


<script>
import Player from '@/Components/Player.vue';

export default {

    components: {
        Player,
    }, 

    data() {
        return {
            keywords: null,
            results: []
        };
    },
    watch: {
        keywords(after, before) {
            this.fetch();
        }
    },
    methods: {
        fetch() {
            axios.get('/api/search', { params: { keywords: this.keywords } })
                .then(response => {
                    this.results = response.data;
                    console.log(this.results);
                })
                .catch(error => {console.log("Lol")});
        }
    }
}
</script>

<style>

</style>
