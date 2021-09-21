<template>
    <div>
        <div class="name">
            <h2>{{ shelf.name }}</h2>
        </div>
        <div class="album four image container">
            <div class="row">
                <div 
                    v-for="post in posts"
                    :key="post.id"
                    class="col">
                    <div class="card">
                        <a 
                            :href="`/communities/${community.slug}/${post.slug}`" 
                            class="card-url" />
                        <ImageArray 
                            :community="community"
                            :element="post" />
                        <div class="card-body">
                            <div class="name">
                                <p>{{ post.name }}</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
    import ImageArray from '../../curated/posts/shelves/vue-album-images.vue'
    export default {
        
        props: [ 'shelf', 'community'],

        components: { ImageArray },

        computed: {

        },

        data() {
            return {
                posts:this.shelf ? this.shelf.public_posts_with_cards : null,
            }
        },

        methods: {

        },
    }
</script>