<template>
    <div class="album four grid-image container">
        <div>
            <draggable
                v-if="posts"
                class="row"
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
                    class="col">
                    <div 
                        v-if="showDelete === index"
                        class="delete-btn">
                        <button 
                            @click="selectedModal=post"
                            class="btn-icon">
                            <svg>
                                <use :xlink:href="`/storage/website-files/icons.svg#ri-close-line`" />
                            </svg>
                        </button>
                    </div>
                    <div class="card">
                        <a 
                            :href="`/communities/${community.slug}/${post.slug}/edit`" 
                            class="card-url" />
                        <ImageArray 
                            :community="community"
                            :element="post" />
                        <div class="card-body">
                            <div 
                                v-if="title"
                                class="name">
                                <p> {{ post.name }} <span v-if="post.status === 'd'">(Not Live)</span></p>
                            </div>
                            <div 
                                v-if="text"
                                class="blurb">
                                <p> {{ post.description }} </p>
                            </div>
                            <a 
                                v-if="edit"
                                :href="`/communities/${community.slug}/${post.slug}`">
                                <button>view</button>
                            </a>
                        </div>
                    </div>
                </div>
            </draggable>
        </div>
        <VueDeleteModal 
            v-if="selectedModal"
            :item="selectedModal"
            :strict="true"
            body="You are deleting the Post. Please be sure you know what you are doing."
            @close="selectedModal=null"
            @ondelete="onDelete" />
        <div 
            v-if="value && value.posts && value.posts.next_page_url"
            class="loadmore">
            <button @click="fetchPosts">
                Load More
            </button>
        </div>
    </div>
</template>

<script>
    import Draggable from "vuedraggable";
    import ImageArray from './vue-album-images.vue'
    import VueDeleteModal from '../../../../components/modals/Vue-Modal-Delete'
    export default {

        props: ['loadposts', 'title', 'text', 'link', 'community', 'edit', 'draggable', 'value'],

        components: { Draggable, ImageArray, VueDeleteModal },

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
            async onDelete() {
                await axios.delete(`/posts/${this.selectedModal.slug}`)
                .then( res => { 
                    this.posts = this.posts.filter( post => post.id !== this.selectedModal.id)
                    this.selectedModal = null
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