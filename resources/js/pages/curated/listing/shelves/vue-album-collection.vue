<template>
    <div class="album four grid-image container">
        <div>
            <draggable
                class="row"
                v-model="featured"
                draggable=".drag"
                @start="isDragging=true" 
                @end="debounce">
                <div 
                    v-for="(feature, index) in featured"
                    :key="feature.featureable.id"
                    @mouseover="showDelete = index"
                    @mouseleave="showDelete = null"
                    :class="{ drag: draggable}"
                    class="col">
                    <div 
                        v-if="showDelete === index"
                        class="delete">
                        <button 
                            @click="deleteListing(feature.featureable)"
                            class="round">
                            <svg>
                                <use :xlink:href="`/storage/website-files/icons.svg#ri-close-line`" />
                            </svg>
                        </button>
                    </div>
                    <div class="card">
                        <a 
                            :href="url(feature)" 
                            class="card-url" />
                        <ImageArray 
                            :community="community"
                            :element="feature" />
                        <div class="card-body">
                            <p 
                                v-if="title" 
                                class="card-title">
                                {{ feature.featureable.name }}
                                <span v-if="feature.featureable.status === 'd'">(Not Live)</span>
                            </p>
                            <p 
                                v-if="text"
                                class="card-text">
                                {{ feature.featureable.description }}
                            </p>
                            <a 
                                v-if="edit"
                                :href="`/communities/${community.slug}/${feature.featureable.slug}`">
                                <button>view</button>
                            </a>
                        </div>
                    </div>
                </div>
            </draggable>
        </div>
    </div>
</template>

<script>
    import Draggable from "vuedraggable";
    import ImageArray from './vue-album-images.vue'
    export default {

        props: ['loadlistings', 'title', 'text', 'link', 'community', 'edit', 'draggable', 'shelf'],

        components: { Draggable, ImageArray },

        computed: {
        
        },

        data() {
            return {
                isDisabled: false,
                showDelete: null,
                listings: this.loadlistings.map(feat => feat.featureable),
                featured: this.loadlistings,
            }
        },

        methods: {
            async deleteListing(listing) {
                await axios.delete(`/listings/${listing.slug}`)
                .then( res => { 
                    console.log(res.data);
                    // this.$emit('update', res.data);
                });
            },
            async updateListOrder() {
                var list = this.featured.map(function(item, index){
                    item.pivot.order = index;
                    return item;
                })
                await axios.put(`/featured/${this.featured[0].pivot.section_id}/reorder`, list)
                .then( res => {
                    this.$emit('updated')
                })
            },
            debounce() {
                if (this.timeout) 
                    clearTimeout(this.timeout); 
                this.timeout = setTimeout(() => {
                    this.updateListOrder()
                }, 500); // delay
            },
            url(element) {
                if (element.type === 'e') { return `/events/${element.featureable.slug}` }
                if (element.type === 'l') { return `/communities/${this.community.slug}/${element.featureable.slug}/edit` }
            }
        },

    }
</script>