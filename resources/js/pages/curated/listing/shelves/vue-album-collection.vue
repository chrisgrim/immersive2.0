<template>
    <div class="album four grid-image container">
        <div>
            <draggable
                v-if="listings"
                class="row"
                v-model="listings"
                draggable=".drag"
                @start="isDragging=true" 
                @end="debounce">
                <div 
                    v-for="(post, index) in listings"
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
                            <p 
                                v-if="title" 
                                class="card-title">
                                {{ post.name }}
                                <span v-if="post.status === 'd'">(Not Live)</span>
                            </p>
                            <p 
                                v-if="text"
                                class="card-text">
                                {{ post.description }}
                            </p>
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
            body="You are deleting the Listings. Please be sure you know what you are doing."
            @close="selectedModal=null"
            @ondelete="onDelete" />
    </div>
</template>

<script>
    import Draggable from "vuedraggable";
    import ImageArray from './vue-album-images.vue'
    import VueDeleteModal from '../../../../components/modals/Vue-Modal-Delete'
    export default {

        props: ['loadlistings', 'title', 'text', 'link', 'community', 'edit', 'draggable', 'shelf'],

        components: { Draggable, ImageArray, VueDeleteModal },

        computed: {
        
        },

        data() {
            return {
                isDisabled: false,
                showDelete: null,
                listings: this.loadlistings,
                selectedModal: null,
            }
        },

        methods: {
            async onDelete() {
                await axios.delete(`/listings/${this.selectedModal.slug}`)
                .then( res => { 
                    this.listings = this.listings.filter( list => list.id !== this.selectedModal.id)
                    this.selectedModal = null
                });
            },
            async updateShelfOrder() {
                var list = this.listings.map(function(item, index){
                    item.order = index;
                    return item;
                })
                await axios.put(`/listings/${this.community.slug}/order`, list)
                .then( res => {
                    this.$emit('updated')
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