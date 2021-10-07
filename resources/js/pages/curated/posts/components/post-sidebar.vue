<template>
    <div class="sidebar">
        <div class="sticky">
            <template>
                <div class="menu">
                    <a :href="`/communities/${community.slug}/${value.slug}`">
                        <button class="outline">
                            view
                        </button>
                    </a>
                </div>
            </template>
            <div class="menu">
                <button 
                    @click="showStatus=!showStatus"
                    class="component-title">
                    <p>Status</p>
                    <svg v-if="showStatus"><use :xlink:href="`/storage/website-files/icons.svg#ri-arrow-up-s-line`" /></svg>
                    <svg v-else><use :xlink:href="`/storage/website-files/icons.svg#ri-arrow-down-s-line`" /></svg>
                </button>
                <template v-if="showStatus">
                    <div class="component-body">
                        <div class="flex btw">
                            <p>Visibility:</p>
                            <button @click="updateStatus">
                                {{ postStatus }}
                            </button>
                        </div>
                    </div>
                </template>
            </div>
            <div class="menu">
                <button 
                    @click="showShelf=!showShelf"
                    class="component-title">
                    <p>Shelf</p>
                    <svg v-if="showShelf"><use :xlink:href="`/storage/website-files/icons.svg#ri-arrow-up-s-line`" /></svg>
                    <svg v-else><use :xlink:href="`/storage/website-files/icons.svg#ri-arrow-down-s-line`" /></svg>
                </button>
                <template v-if="showShelf">
                    <div class="component-body">
                        <v-select
                            v-model="inputVal.shelf_id"
                            :reduce="shelf => shelf.id"
                            :options="shelves"
                            placeholder="Shelf"
                            @input="update"
                            label="name" />
                    </div>
                </template>
            </div>
            <div class="menu">
                <button 
                    @click="showFeatured=!showFeatured"
                    class="component-title">
                    <p>Featured Image</p>
                    <svg v-if="showFeatured"><use :xlink:href="`/storage/website-files/icons.svg#ri-arrow-up-s-line`" /></svg>
                    <svg v-else><use :xlink:href="`/storage/website-files/icons.svg#ri-arrow-down-s-line`" /></svg>
                </button>
                <template v-if="showFeatured">
                    <div class="component-body">
                        <div class="post-image">
                            <template v-if="imageType === 'e'">
                                event
                            </template>
                            <template v-else>
                                <CardImage
                                    text="Image must be at least 900px by 500px"
                                    :height="500"
                                    :width="900"
                                    :locked="inputVal.thumbImagePath ? true :false"
                                    @onDelete="deleteImage"
                                    :can-delete="true"
                                    :image="inputVal.thumbImagePath ? `/storage/${inputVal.thumbImagePath}` : null"
                                    @addImage="addImage" />
                            </template>
                        </div>
                        <div class="flex btw">
                            <p>Featured visible:</p>
                            <button @click="updateType">
                                {{ postType }}
                            </button>
                        </div>
                        <div>
                            Use Event Image
                            <v-select
                                v-model="searchInput"
                                :options="options"
                                placeholder="Select event"
                                label="name"
                                @search="debounce" 
                                @input="eventImage"
                                @search:focus="debounce" />
                        </div>
                    </div>
                </template>
            </div>
            <div class="menu">
                <button 
                    @click="showOrder=!showOrder"
                    class="component-title">
                    <p>Card Order</p>
                    <svg v-if="showOrder"><use :xlink:href="`/storage/website-files/icons.svg#ri-arrow-up-s-line`" /></svg>
                    <svg v-else><use :xlink:href="`/storage/website-files/icons.svg#ri-arrow-down-s-line`" /></svg>
                </button>
                <template v-if="showOrder">
                    <div class="component-body">
                        <draggable
                            v-model="inputVal.cards" 
                            @start="isDragging=true" 
                            @end="reOrder">
                            <div 
                                v-for="card in inputVal.cards"
                                :key="`list${card.id}`"
                                class="nav-card__item">
                                <span v-if="card.type==='e'">event block</span>
                                <span v-else-if="card.type==='i'">image block</span>
                                <span v-else>text block</span>
                            </div>
                        </draggable>
                    </div>
                </template>
            </div>
        </div>
    </div>
</template>

<script>
    import CardImage from '../../../../components/Upload-Image.vue'
    import formValidationMixin from '../../../../mixins/form-validation-mixin'
    export default {
        
        props: [ 'value', 'user', 'community', 'shelves'],

        mixins: [formValidationMixin],

        components: { CardImage },

        computed: {
            inputVal: {
                get() { return this.value },
                set(val) { this.$emit('input', val) }
            },
            postStatus() {
                if (this.inputVal.status === 'p') { return 'Live'}
                return 'Draft'
            },
            postType() {
                if (this.inputVal.type === 'h') { return 'Hidden'}
                return 'Visible'
            },
            imageType() {
                if (this.inputVal.image_type === 'e') { return 'Event'}
                return 'Upload'
            }

        },

        data() {
            return {
                searchInput: null,
                options: [],
                showStatus: false,
                showFeatured: false,
                showOrder: false,
                showShelf: false,
                updated: false,
            }
        },

        methods: {
            debounce(query) {
                if (this.timeout) 
                    clearTimeout(this.timeout); 
                this.timeout = setTimeout(() => {
                    this.generateSearchList(query);
                }, 500); // delay
            },
            async generateSearchList (query) {
                await axios.get('/api/search/events', { params: { keywords: query } })
                .then( res => {
                    this.options = res.data.data;
                })
            },
            eventImage() {
                this.$emit('addEventFeaturedImage', this.searchInput);
            },
            update() {
                this.$emit('update');
            },
            addImage(image) {
                this.$emit('addImage', image);
            },
            deleteImage() {
                this.$emit('deleteImage');
            },
            reOrder() {
                this.$emit('reOrder');
            },
            updateStatus() {
                if (this.inputVal.status === 'd') {
                    this.inputVal.status = 'p'
                } else {
                    this.inputVal.status = 'd'
                }
                this.update();
            },
            updateType() {
                if (this.inputVal.type === 's') {
                    this.inputVal.type = 'h'
                } else {
                    this.inputVal.type = 's'
                }
                this.update();
            },
        },
    }
</script>