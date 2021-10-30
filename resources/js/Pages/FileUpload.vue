<template>
    <div class="container" style="margin-top: 50px;">
        <form :action="route" method="post" enctype="multipart/form-data">
            <h3>Upload File</h3>
            <div v-if="success !== ''" class="alert alert-success" role="alert">
            {{success}}
            </div>
            <input type="hidden" name="_token" v-bind:value="token">

            {{token}}

            <div class="custom-file">
                <input type="text" name="beatTitle" class="custom-text-input" id="title">
                <label class="custom-text-label" for="title">Title</label>
            </div>

            <div class="custom-file">
                <input type="file" name="file" class="custom-file-input" id="chooseFile">
                <label class="custom-file-label" for="chooseFile">Select Beat</label>
            </div>
            <div class="custom-file">
                <input type="file" name="file2" class="custom-file-input" id="chooseFile">
                <label class="custom-file-label" for="chooseFile">Select Cover</label>
            </div>

            <!-- <div>
                <vue-tags-input
                v-model="tag"
                :tags="tags"
                @tags-changed="newTags => tags = newTags"
                :max-tags="5"
                />
            </div> -->

            <input type="submit" name="submit">

        </form>
    </div>
</template>
<script>
import { Head } from "@inertiajs/inertia-vue3";
import VueTagsInput from '@sipec/vue3-tags-input';

export default {
    components: {
        VueTagsInput,
    },
    data() {
        return {
            // route: "{{route('fileUpload')}}",
        };
    },
    props: {
        route: String,
        token: String,
        success: String,
        file1: String,
        file2: String,
    },
    methods: {
        
    },
}
</script>
