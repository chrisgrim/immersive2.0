<template>
    <div class="m-auto gap-6 whitespace-nowrap overflow-y-hidden overflow-x-auto w-full md:whitespace-normal md:overflow-visible">
        <div>
            <draggable
                v-if="posts"
                style="scroll-snap-type: x mandatory;" 
                class="flex w-full scroll-p-7 overflow-visible mt-8 scroll-smooth md:flex-wrap"
                v-model="posts"
                draggable=".drag"
                @start="isDragging=true" 
                @end="debounce">
                <div 
                    v-for="(post, index) in posts"
                    :key="post.id"
                    @mouseover="showDelete = index"
                    @mouseleave="showDelete = null"
                    :class="{ drag: draggable}"
                    class="relative w-full flex flex-[1_0_calc(100%-6rem)] snap-start snap-always px-4 first:ml-[-1rem] last:mr-[-1rem] md:flex-[0_1_33.3333333333%] md:w-4/12 lg:flex-[0_1_25%] lg:w-3/12">
                    <button 
                        v-if="showDelete === index && value.id !== archived.id"
                        @click="selectedModal=post"
                        class="absolute top-[-1rem] z-20 right-[-.4rem] items-center justify-center rounded-full p-0 w-12 h-12 flex border-2 bg-white border-black hover:bg-black hover:fill-white">
                        <svg class="w-12 h-12">
                            <use :xlink:href="`/storage/website-files/icons.svg#ri-close-line`" />
                        </svg>
                    </button>
                    <div class="flex w-full flex-col overflow-hidden relative">
                        <a 
                            :href="`/communities/${community.slug}/${post.slug}/edit`" 
                            class="block h-full absolute w-full rounded-2xl top-0 left-0 z-10" />
                        <CardImage 
                            :community="community"
                            :element="post" />
                        <div class="mb-8 mt-2">
                            <div 
                                v-if="title"
                                class="mt-4 overflow-hidden text-ellipsis max-h-16">
                                <p> {{ post.name }} <span v-if="post.status === 'd'">(Not Live)</span></p>
                            </div>
                            <div 
                                v-if="text"
                                class="blurb">
                                <p> {{ post.description }} </p>
                            </div>
                        </div>
                    </div>
                </div>
            </draggable>
        </div>
        <VueArchiveModal 
            v-if="selectedModal"
            :item="selectedModal"
            :strict="true"
            body="You are archiving the Post"
            @close="selectedModal=null"
            @archive="onArchive" />
        <div 
            v-if="value && value.posts && value.posts.next_page_url"
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
    import Draggable from "vuedraggable";
    import CardImage from './vue-image.vue'
    import VueArchiveModal from '../../../../components/modals/Vue-Modal-Archive'
    export default {

        props: ['loadposts', 'title', 'text', 'link', 'community', 'edit', 'draggable', 'value', 'archived'],

        components: { Draggable, CardImage, VueArchiveModal },

        computed: {
            inputVal: {
                get() { return this.value },
                set(val) { this.$emit('input', val) }
            },
        },

        data() {
            return {
                isDisabled: false,
                showDelete: null,
                posts: this.loadposts,
                selectedModal: null,
            }
        },

        methods: {
            async onArchive() {
                this.selectedModal.shelf_id = this.archived.id
                this.update()
            },
            async update() {
                await axios.put(`/communities/${this.community.slug}/${this.selectedModal.slug}/update`, this.selectedModal)
                .then( res => { 
                    console.log(res.data);
                    location.reload()
                    // this.posts = this.posts.filter( post => post.id !== this.selectedModal.id)
                    // this.selectedModal = null
                });
            },
            async updateShelfOrder() {
                var list = this.posts.map(function(item, index){
                    item.order = index;
                    return item;
                })
                await axios.put(`/posts/${this.community.slug}/order`, list)
                .then( res => {
                    this.$emit('updated')
                })
            },
            async fetchPosts() {
                await axios.get(`/shelves/${this.value.id}/paginate?page=${this.value.posts.next_page_url.slice(-1)}`)
                .then( res => {
                    this.inputVal.posts = res.data
                    this.posts = this.posts.concat(res.data.data);
                })
            },
            debounce() {
                if (this.timeout) 
                    clearTimeout(this.timeout); 
                this.timeout = setTimeout(() => {
                    this.updateShelfOrder()
                }, 500); // delay
            },
        },

    }
</script>