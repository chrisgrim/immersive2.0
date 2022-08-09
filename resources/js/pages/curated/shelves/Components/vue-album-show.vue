<template>
    <div class="whitespace-nowrap overflow-y-hidden overflow-x-auto m-auto w-full">
        <div 
            style="scroll-snap-type: x mandatory;" 
            class="flex w-full scroll-p-7 overflow-auto mt-8 scroll-smooth md:flex-wrap mx-[-1rem]">
            <div 
                v-for="(element) in posts"
                :key="element.id"
                class="relative w-full flex flex-[1_0_calc(100%-6rem)] snap-start snap-always px-4 md:flex-[0_1_33.3333333333%] md:w-4/12 lg:flex-[0_1_25%] lg:w-3/12">
                <div class="flex w-full flex-col overflow-hidden relative">
                    <a 
                        :href="`/communities/${community.slug}/${element.slug}`" 
                        class="block h-full absolute w-full rounded-2xl top-0 left-0 z-10" />
                    <CardImage 
                        :community="community"
                        :element="element" />
                    <div class="mb-8 mt-2">
                        <div 
                            v-if="title"
                            class="mt-4 overflow-hidden text-ellipsis max-h-16">
                            <p> {{ element.name }} </p>
                        </div>
                        <div 
                            v-if="text"
                            class="mt-2 overflow-hidden text-ellipsis max-h-16">
                            <p> {{ element.blurb }} </p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div 
            v-if="shelf && shelf.published_posts && shelf.published_posts.next_page_url"
            class="loadmore">
            <button 
                class="rounded-full py-2 px-4 bg-white hover:bg-black hover:text-white hover:border-black"
                @click="fetchPosts">
                Load More
            </button>
        </div>
    </div>
</template>

<script>
    import CardImage from './vue-image.vue'
    export default {

        props: ['title', 'text', 'link', 'edit', 'loadshelf', 'community'],

        components: { CardImage },

        data() {
            return {
                isDisabled: false,
                shelf: this.loadshelf,
                posts: this.loadshelf.published_posts.data,
            }
        },

        methods: {
            async fetchPosts() {
                await axios.get(`/shelves/${this.shelf.id}/paginate?page=${this.shelf.published_posts.next_page_url.slice(-1)}&type=published`)
                .then( res => {
                    this.shelf.published_posts = res.data
                    this.posts = this.posts.concat(res.data.data);
                })
            },
        },

    }
</script>