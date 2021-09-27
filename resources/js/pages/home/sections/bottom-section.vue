<template>
    <div>
        <div class="name">
            <h2>{{ shelf.name }}</h2>
        </div>
        <div class="album three image container">
            <div class="row">
                <div 
                    v-for="post in posts.slice(0,3)"
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
                            <div class="blurb">
                                <p>{{ post.blurb }}</p>
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
        
        props: [ 'shelf'],

        components: { ImageArray },

        computed: {
        },

        data() {
            return {
                posts:this.shelf ? this.shelf.published_posts : null,
                community: this.shelf.community ? this.shelf.community : null
            }
        },

        methods: {

        },
    }
</script>