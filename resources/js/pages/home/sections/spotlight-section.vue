<template>
    <div class="my-8 md:mt-16 md:mb-24">
        <div class="w-full relative block overflow-hidden mb-8 rounded-xl md:flex md:h-[45rem]">
            <div class="flex items-center justify-center p-8 bg-black md:justify-start md:w-2/5 md:px-24 md:py-0">
                <div class="w-full">
                    <div>
                        <p class="text-white mb-2">Spotlight: </p>
                        <h2 class="text-5xl text-white">{{ name }}</h2>
                    </div>
                    <div class="h-full mt-4">
                        <p class="text-2xl text-white">{{ element.blurb }}</p>
                    </div>
                    <a :href="url()">
                        <button class="bg-white border-none p-4 mt-12 rounded-2xl font-bold text-xl">
                            Check it out
                        </button>
                    </a>
                </div>
            </div>
            <div class="relative inline-block bg-slate-400 md:w-3/5 md:h-[45rem] after:absolute after:left-0 after:top-0 after:inline-block after:h-full after:w-full after:bg-gradient-to-r from-black via-transparent to-transparent">
                <picture>
                    <source 
                        type="image/webp" 
                        :srcset="`${$envImageUrl}${element.largeImagePath}`"> 
                    <img 
                        loading="lazy" 
                        class=" object-cover align-bottom w-full h-full"
                        :src="`${$envImageUrl}${element.largeImagePath.slice(0, -4)}jpg`" 
                        :alt="`${element.name} Community`">
                </picture>
            </div>
        </div>
    </div>
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