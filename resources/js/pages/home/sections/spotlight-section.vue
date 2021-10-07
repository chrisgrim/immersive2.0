<template>
    <section class="section-a">
        <div class="section-a__wrapper">
            <div class="header-a">
                <div class="header-a__content">
                    <div class="header-a__wrapper">
                        <div class="header-a__name">
                            <p>Spotlight: </p>
                            <h2>{{ name }}</h2>
                        </div>
                        <div class="header-a__blurb">
                            <p>{{ element.blurb }}</p>
                        </div>
                        <a :href="url()">
                            <button>
                                Check it out
                            </button>
                        </a>
                    </div>
                </div>
                <div class="header-a__image">
                    <picture>
                        <source 
                            type="image/webp" 
                            :srcset="`/storage/${element.thumbImagePath}`"> 
                        <img 
                            :src="`/storage/${element.thumbImagePath.slice(0, -4)}jpg`" 
                            :alt="`${element.name} Community`">
                    </picture>
                </div>
            </div>
        </div>
    </section>
</template>

<script>
    export default {
        
        props: [ 'dock'],

        computed: {
            element() {
                if (this.shelf) {return this.dock.shelves[0].published_posts[0]}
                if (this.community) {return this.dock.communities[0]}
                if (this.post) {return this.dock.posts}
                return null;
            },
            name() {
                if (this.dock.name) { return this.dock.name }
                if (this.shelf) {return this.dock.shelves[0].name}
                if (this.post) {return this.dock.posts}
                if (this.community) {return this.dock.communities[0].name}
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
            url() {
                if (this.shelf) { return `/communities/${this.element.community.slug}/${this.element.slug}` }
                if (this.community) { return `/communities/${this.element.slug}` }
            }
        },
    }
</script>