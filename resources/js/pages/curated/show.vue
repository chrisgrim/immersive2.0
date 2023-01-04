<template>
    <div>
        <div class="m-auto w-full md:px-12 md:py-8 lg:py-0 lg:px-32 max-w-screen-xl">
            <template v-if="mobile">
                <nav class="es__mobile-nav">
                    <button 
                        aria-label="back button"
                        type="button"
                        @click="onBack"
                        class="absolute flex top-8 left-8 z-10 p-1 rounded-full shadow-lg bg-white">
                        <svg class="h-12 w-12">
                            <use :xlink:href="`/storage/website-files/icons.svg#ri-arrow-left-s-line`" />
                        </svg>
                    </button>
                </nav>
            </template>
            <template v-else>
                <div class="py-12">
                    <p class="text-xl">
                        <a 
                            class="underline" 
                            :href="`/`">Everything Immersive</a> > {{ community.name }} Community
                    </p>
                </div>
            </template>
            <div class="relative overflow-hidden mb-8 rounded-2xl block h-full w-full md:flex md:h-[45rem]">
                <div 
                    v-if="curator" 
                    class="absolute right-20 bottom-20 z-10">
                    <a :href="`/communities/${community.slug}/edit`">
                        <button class="border-none rounded-2xl mt-16 py-6 px-8 bg-white">Edit Community</button>
                    </a>
                </div>
                <div class="p-8 items-center justify-center flex bg-black text-center md:justify-start md:text-left md:w-5/12 md:h-[45rem] md:px-24">
                    <div class="w-full mt-32 md:mt-0">
                        <h2 class="text-white text-6xl mb-4">{{ community.name }}</h2>
                        <p class="text-white mb-16">{{ community.blurb }}</p>
                    </div>
                </div>
                <div class="relative inline-block bg-slate-400 md:w-3/5 md:h-[45rem] after:absolute after:left-0 after:top-0 after:inline-block after:h-full after:w-full after:bg-gradient-to-r from-black via-transparent to-transparent">
                    <picture>
                        <source 
                            type="image/webp" 
                            :srcset="`${$envImageUrl}${headerImage}?timestamp=${community.updated_at}`"> 
                        <img 
                            loading="lazy" 
                            class=" object-cover align-bottom w-full h-full"
                            :src="`${$envImageUrl}${headerImage.slice(0, -4)}jpg?timestamp=${community.updated_at}`" 
                            :alt="`${community.name} Community`">
                    </picture>
                </div>
            </div>
        </div>
        <div class="m-auto w-full md:px-12 md:py-8 lg:py-0 lg:px-32 max-w-screen-xl">
            <div class="flex flex-col md:flex-row">
                <div class="w-full inline-block md:w-3/12 order-last md:order-first md:pb-8 md:pr-8">
                    <div class="sticky top-16 mb-8 px-8 md:px-0">
                        <div class="mb-16">
                            <h4 class="text-3xl font-medium">Meet your curators</h4>
                            <div 
                                v-for="curator in community.curators"
                                :key="curator.id"
                                class="px-4 border rounded-2xl flex items-center h-20 mt-8">
                                <template v-if="curator && curator.thumbImagePath">
                                    <picture>
                                        <source 
                                            type="image/webp" 
                                            :srcset="`${$envImageUrl}${curator.thumbImagePath}`"> 
                                        <img 
                                            class="w-12 rounded-full mr-4"
                                            :src="`${$envImageUrl}${curator.thumbImagePath.slice(0, -4)}`" 
                                            :alt="`${curator.name} Community`">
                                    </picture>
                                </template>
                                <p class="text-1xl">{{ curator.name }}</p>
                            </div>
                        </div>
                        <h4 class="font-medium text-3xl">About {{ value.name }}</h4>
                        <div class="p-8 rounded-2xl my-8 border">
                            <ShowMore 
                                :text="value.description"
                                :limit="150" />
                        </div>
                    </div>
                </div>
                <div class="inline-block w-full px-8 md:w-9/12">
                    <div 
                        class="relative" 
                        v-for="shelf in shelves"
                        :key="shelf.id">
                        <Shelf 
                            :community="community"
                            :shelf="shelf" />
                    </div>
                    <div 
                        v-if="shelfContainer && shelfContainer.next_page_url"
                        class="loadmore">
                        <button 
                            class="rounded-full py-2 px-4 bg-white hover:bg-black hover:text-white hover:border-black"
                            @click="fetchShelves">
                            Load More
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
    import Shelf from './Shelves/shelf-show.vue'
    import ShowMore from '../../components/ShowMore.vue'
    export default {
        
        props: [ 'value', 'loadshelves', 'curator', 'mobile' ],

        components: { Shelf, ShowMore },

        data() {
            return {
                community: this.value,
                shelfContainer: this.loadshelves,
                shelves:this.loadshelves.data,
                headerImage: this.mobile ? this.value.thumbImagePath : this.value.largeImagePath,
            }
        },

        methods: {
            onBack() {
                document.referrer == "" ? window.location.href = '/' : window.history.back()
            },
            async fetchShelves() {
                await axios.get(`/communities/${this.community.slug}/shelves/paginate?page=${this.shelfContainer.next_page_url.slice(-1)}`)
                .then( res => {
                    this.shelfContainer = res.data
                    this.shelves = this.shelves.concat(res.data.data);
                })
            },
        },

    }
</script>