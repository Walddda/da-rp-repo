<template>
    <div class="container" style="margin-top: 50px;">
        <form action="{{route('upload')}}" method="post" enctype="multipart/form-data">
            <h3>Upload File</h3>
            <div v-if="success !== ''" class="alert alert-success" role="alert">
            {{success}}
            </div>

            <div class="input-group">
                <div class="custom-file">
                    <input type="text" name="beatTitle" class="custom-text-input" id="title">
                    <label class="custom-text-label" for="title">Title</label>
                </div>

                <div class="custom-file">
                    <input type="file" name="file" class="custom-file-input" id="chooseFile" v-on:change="onFileChange">
                    <label class="custom-file-label" for="chooseFile">Select Beat</label>
                </div>

                <div class="custom-file">
                    <input type="file" name="file2" class="custom-file-input" id="chooseFile" v-on:change="onFileChange">
                    <label class="custom-file-label" for="chooseFile">Select Cover</label>
                </div>

                <div>
                    <vue-tags-input
                    v-model="tag"
                    :tags="tags"
                    @tags-changed="newTags => tags = newTags"
                    :max-tags="5"
                    />
                </div>
            </div>

            <div class="input-group-append">
                <input type="submit" class="btn btn-primary" value="Upload">
            </div>

            <p class="text-danger font-weight-bold">{{filename}}</p>
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
        filename: '',
        file: '',
        success: '',
        };
    },
    methods: {
        onFileChange(e) {
        //console.log(e.target.files[0]);
        this.filename = "Selected File: " + e.target.files[0].name;
        this.file = e.target.files[0];
        },
    },
}
</script>
