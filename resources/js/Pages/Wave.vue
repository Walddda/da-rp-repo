<template>
    <tracks :attr="{loc: 'wave'}" sum="2"></tracks>
</template>

<script>
import Tracks from '@/Components/Tracks.vue';
export default {
    components: {
        Tracks,
    },
    data(){
        return {
            files: null,
        }
    },

}
</script>

<style>

</style>