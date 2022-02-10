<template>
    <div class="rounded-2xl mb-8">
        <div class="flex">
            <h3 class="text-4xl font-semibold">{{ shelf.name }}</h3>
        </div>
        <div 
            v-if="posts && posts.length"
            class="relative w-full inline-block">
            <PostAlbum
                :title="true"
                :text="true"
                :shelf="shelf"
                :community="community"
                v-model="posts" />
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
                posts:this.shelf.published_posts,
            }
        },

        methods: {
            
        },
    }
</script>