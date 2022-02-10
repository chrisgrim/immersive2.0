<template>
    <div class="mt-8 mb-0 md:mt-16 md:mb-24">
        <div>
            <h2>{{ name }}</h2>
        </div>
        <div class="whitespace-nowrap overflow-y-hidden overflow-x-auto m-auto w-full">
            <div 
                style="scroll-snap-type: x mandatory;" 
                class="flex w-full scroll-p-7 overflow-auto mt-8 scroll-smooth">
                <div 
                    v-for="element in elements.slice(0, count)"
                    :key="element.id"
                    :class="[ number === 'three' 
                        ? 'md:flex-[0_1_50%] md:w-6/12 lg:flex-[0_1_33.3333333333%] lg:w-4/12' 
                        : 'md:flex-[0_1_33.3333333333%] md:w-4/12 lg:flex-[0_1_25%] lg:w-3/12' ]"
                    class="relative w-full flex flex-[1_0_calc(100%-6rem)] snap-start snap-always px-4 first:ml-[-1rem] last:mr-[-1rem]">
                    <div class="flex w-full flex-col overflow-hidden relative">
                        <a 
                            :href="url(element)" 
                            class="block h-full absolute w-full rounded-2xl top-0 left-0 z-10" />
                        <AlbumImage 
                            :community="element.community"
                            :element="element" />
                        <div class="mb-8 mt-2">
                            <div class="mt-4 overflow-hidden text-ellipsis max-h-16 font-medium text-xl">
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
    import AlbumImage from './Components/vue-album-image.vue'
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

        components: { AlbumImage },

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