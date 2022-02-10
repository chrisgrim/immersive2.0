<template>
    <div class="mt-8 mb-0 md:mt-16 md:mb-24">
        <div>
            <h2>{{ name }}</h2>
        </div>
        <div class="whitespace-nowrap overflow-y-hidden overflow-x-auto m-auto w-full">
            <div 
                style="grid-template-columns: 1fr 1fr;" 
                class="w-full whitespace-nowrap overflow-visible my-8 grid md:inline-block gap-8">
                <div 
                    v-for="element in elements"
                    :key="element.id"
                    class="w-10/12 relative inline-block md:w-3/12">
                    <div class="w-[70vw] items-center relative flex md:w-full">
                        <a 
                            :href="url(element)" 
                            class="block h-full absolute left-0 top-0 w-full rounded-4 z-10" />
                        <SpotlightImage 
                            :community="element.community"
                            :element="element" />
                        <div class="flex overflow-x-hidden ml-8 flex-wrap">
                            <div>
                                <p class="truncate font-medium text-2xl">{{ element.name }}</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
    import SpotlightImage from './Components/vue-spotlight-image.vue'
    export default {
        
        props: [ 'dock'],

        components: { SpotlightImage },

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
                if (this.post) {return null }
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