<template>
    <div class="album listing container">
        <div>
            <draggable
                class="row"
                v-model="elements"
                draggable=".drag"
                @start="isDragging=true" 
                @end="debounce">
                <div 
                    v-for="(element, index) in elements"
                    :key="element.id"
                    @mouseover="showDelete = index"
                    @mouseleave="showDelete = null"
                    :class="{ drag: draggable}"
                    class="col">
                    <div 
                        v-if="showDelete === index"
                        class="delete">
                        <button 
                            @click="deleteElement(element)"
                            class="round">
                            <svg>
                                <use :xlink:href="`/storage/website-files/icons.svg#ri-close-line`" />
                            </svg>
                        </button>
                    </div>
                    <div class="card">
                        <a 
                            :href="`/communities/${community.slug}/${element.slug}/edit`" 
                            class="card-url" />
                        <ImageArray 
                            :community="community"
                            :element="element" />
                        <div class="card-body">
                            <p 
                                v-if="title" 
                                class="card-title">
                                {{ element.name }}
                                <span v-if="element.status === 'd'">(Not Live)</span>
                            </p>
                            <p 
                                v-if="text"
                                class="card-text">
                                {{ element.description }}
                            </p>
                            <a 
                                v-if="edit"
                                :href="`/communities/${community.slug}/${element.slug}`">
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

        props: ['value', 'title', 'text', 'link', 'community', 'edit', 'draggable', 'shelf'],

        components: { Draggable, ImageArray },

        computed: {
            elements: {
                get() {
                    return this.value;
                },
                set(val) {
                    this.$emit('input', val);
                }
            },
        },

        data() {
            return {
                isDisabled: false,
                showDelete: null,
            }
        },

        methods: {
            async deleteElement(element) {
                await axios.delete(`/listings/${element.slug}`)
                .then( res => { 
                    this.$emit('update', res.data);
                });
            },
            async updateListOrder() {
                var list = this.value.map(function(item, index){
                    item.order = index;
                    return item;
                })
                await axios.put(`/listing/order`, list)
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
        },

    }
</script>