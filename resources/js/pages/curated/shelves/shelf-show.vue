<template>
    <div class="shelf">
        <div class="name">
            <h3>{{ shelf.name }}</h3>
        </div>
        <div 
            v-if="posts && posts.length"
            class="posts">
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
    import PostAlbum from './vue-album-show.vue'
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