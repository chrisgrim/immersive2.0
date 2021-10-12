<template>
    <div class="hero-section__wrapper">
        <div class="title">
            <h2>{{ name }}</h2>
            <div class="nav-scroll">
                <button 
                    aria-label="Scroll Left"
                    class="btn round four brd-grey" 
                    @click="scrollLeft">
                    <svg>
                        <use :xlink:href="`/storage/website-files/icons.svg#ri-arrow-left-s-line`" />
                    </svg>
                </button>
                <button 
                    aria-label="Scroll Right"
                    class="btn round four brd-grey" 
                    @click="scrollRight">
                    <svg class="remix">
                        <use :xlink:href="`/storage/website-files/icons.svg#ri-arrow-right-s-line`" />
                    </svg>
                </button>
            </div>
        </div>
        <div class="album-c">
            <div class="row" ref="scroll">
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
            },
            scrollRight() {
                let content =  this.$refs.scroll;
                content.scrollLeft += 500;
            },
            scrollLeft() {
                let content =  this.$refs.scroll;
                content.scrollLeft -= 500;
            },
        },
    }
</script>