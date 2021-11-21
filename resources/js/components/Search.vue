<template>
    <div>
        <div>
            <div class="input-group">
                <input type="text" v-model="search" class="form-control">
                <div class="input-group-prepend">
                    <button @click.prevent="searchBeat()">SEARCH</button>
                </div>
            </div>
        </div>
    </div>
</template>


<script>

export default {
    name: 'Search',

    methods: {
        searchBeat() {
            fetch('/api/beat/search?q='+this.search)
            .then(res => res.json())
            .then(res => {
                this.results = res
                this.search = ''
                this.showsearch = true;
            })
            .catch(err => {
                console.log(err)
            })
        }
    }, 

    props: {
        search: String,
        results: Array,
    },
    data() {
        return {
            showsearch: false,
        }
    }
}
</script>

<style>

</style>
