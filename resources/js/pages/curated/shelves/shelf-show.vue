<template>
    <div class="rounded-2xl mb-8">
        <div class="flex">
            <h3 class="text-4xl font-semibold">{{ shelf.name }}</h3>
        </div>
        <div class="relative w-full inline-block">
            <PostAlbum
                :title="true"
                :text="true"
                :loadshelf="shelf"
                :community="community" />
        </div>
    </div>
</template>

<script>
    import PostAlbum from './Components/vue-album-show.vue'
    export default {
        
        props: [ 'shelf', 'owner', 'community' ],

        components: { PostAlbum },

        computed: {

        },

        data() {
            return {
            }
        },

        methods: {
            
        },
    }
</script>