<template>
    <section class="hero-section">
        <div class="hero-section__wrapper">
            <div class="title">
                <h2>{{ name }}</h2>
            </div>
            <div class="album-c">
                <div class="row">
                    <div 
                        v-for="(element, index) in elements"
                        :key="element.id"
                        :class="{ first : index=='0', last : index==elements.length -1}"
                        class="col">
                        <a 
                            :href="url(element)" 
                            class="card-url">
                            <div class="card">
                                <ImageArray 
                                    :community="element.community"
                                    quality="large"
                                    :element="element" />
                                <div class="card-body">
                                    <div class="wrapper">
                                        <div class="blurb">
                                            {{ element.blurb }}
                                        </div>
                                        <div class="name">
                                            {{ element.name }}
                                        </div>
                                    </div>
                                    <button>
                                        Show me
                                    </button>
                                </div>
                            </div>
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </section>
</template>

<script>
    import ImageArray from '../../curated/shelves/vue-album-images.vue'
    export default {
        
        props: [ 'dock'],

        components: { ImageArray },

        computed: {
            elements() {
                if (this.shelf) {return this.dock.shelves[0].published_posts}
                if (this.community) {return this.dock.communities}
                if (this.post) {return this.dock.posts}
                return null;
            },
            name() {
                if (this.dock.name) { return this.dock.name }
                if (this.shelf) {return this.dock.shelves[0].name}
                if (this.community) {return null}
                if (this.post) {return null}
                return null;
            },
        },

        data() {
            return {
                shelf: this.dock.shelves.length,
                community: this.dock.communities.length,
                post: this.dock.posts.length,
            }
        },

        methods: {
            url(element) {
                if (this.shelf) { return `/communities/${element.community.slug}/${element.slug}` }
                if (this.community) { return `/communities/${element.slug}` }
            }
        },
    }
</script>