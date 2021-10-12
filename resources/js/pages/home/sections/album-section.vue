<template>
    <div class="section-a__wrapper" style="margin-bottom:0rem;">
        <div class="name">
            <h2>{{ name }}</h2>
        </div>
        <div 
            :class="number"
            class="album image container">
            <div class="row">
                <div 
                    v-for="element in elements.slice(0, count)"
                    :key="element.id"
                    class="col">
                    <div class="card">
                        <a 
                            :href="url(element)" 
                            class="card-url" />
                        <ImageArray 
                            :community="element.community"
                            :element="element" />
                        <div class="card-body">
                            <div class="name">
                                <p>{{ element.name }}</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
    import ImageArray from '../../curated/shelves/vue-album-images.vue'
    export default {
        props: {
            dock: {
                type: Object,
                default: null
            },
            number: {
                type: String,
                default: 'four'
            }
        },

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
                if (this.community) {return null }
                if (this.post) {return null}
                return null;
            },
        },

        data() {
            return {
                shelf: this.dock.shelves.length,
                community: this.dock.communities.length,
                post: this.dock.posts.length,
                count: this.number === 'four' ? 4 : 3
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