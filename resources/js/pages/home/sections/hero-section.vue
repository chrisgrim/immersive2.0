<template>
    <div class="my-8 md:mt-16 md:mb-24">
        <div class="justify-between flex px-8 my-8 md:px-12 lg:px-32 lg:my-12">
            <h2>{{ name }}</h2>
            <div class="inline-block invisible md:visible">
                <button 
                    aria-label="Scroll Left"
                    class="rounded-full w-16 h-16 border border-gray-300 p-0" 
                    @click="scrollLeft">
                    <svg class="w-2/4 h-full m-auto">
                        <use :xlink:href="`/storage/website-files/icons.svg#ri-arrow-left-s-line`" />
                    </svg>
                </button>
                <button 
                    aria-label="Scroll Right"
                    class="rounded-full w-16 h-16 border border-gray-300 p-0" 
                    @click="scrollRight">
                    <svg class="w-2/4 h-full m-auto">
                        <use :xlink:href="`/storage/website-files/icons.svg#ri-arrow-right-s-line`" />
                    </svg>
                </button>
            </div>
        </div>
        <div 
            class="h-[calc(125vw-10rem)] overflow-y-hidden overflow-x-auto whitespace-nowrap md:h-128">
            <div 
                style="scroll-snap-type: x mandatory;" 
                class="overflow-x-auto flex scroll-smooth h-[calc(125vw-6.25rem)] custom-h-1 scroll-p-10 md:h-auto lg:scroll-p-32" 
                ref="scroll">
                <div 
                    v-for="(element) in elements"
                    :key="element.id"
                    :class="[ mobile ? 
                        'flex-[1_0_calc(100%-8rem)] last:pr-8 first:pl-8' : 
                        'first:pl-32 last:pr-32 flex-[1_0_calc(50%-1rem)] first:ml-0' ]"
                    class="ml-6 first:ml-0 snap-start snap-always md:min-w-[48rem] md:max-w-[68rem] lg:flex-[1_0_calc(50%-9rem)]">
                    <a 
                        :href="url(element)" 
                        class="relative block w-full">
                        <div class="relative h-[calc(125vw-10rem)] rounded-2xl overflow-hidden md:h-[32rem]">
                            <SingleImage :element="element" />
                            <div class="relative content-start flex h-full p-10 flex-col w-96">
                                <div class="flex-1 md:w-[30rem] lg:w-96">
                                    <div class="overflow-hidden text-ellipsis text-white text-lg">
                                        {{ element.blurb }}
                                    </div>
                                    <div class="text-white mt-4 whitespace-normal text-5xl font-medium">
                                        {{ element.name }}
                                    </div>
                                </div>
                                <button class="border-none rounded-2xl p-4 text-black bg-white w-40 font-bold text-xl">
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
    import SingleImage from './Components/vue-hero-image.vue'
    export default {
        
        props: [ 'dock', 'mobile'],

        components: { SingleImage },

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