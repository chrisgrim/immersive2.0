<template>
    <div class="w-full inline-block md:w-4/12 md:py-8 md:pr-8">
        <div class="sticky top-16 overflow-auto h-[calc(100vh-20rem)]">
            <template>
                <div class="">
                    <a :href="`/communities/${community.slug}/${value.slug}`">
                        <button class="bg-black px-4 py-2 rounded-full text-white hover:bg-white hover:text-black mb-4">
                            View Post {{ inputVal.status !== 'p' ? `(Draft)` : ''}}
                        </button>
                    </a>
                </div>
            </template>
            <div class="">
                <button 
                    @click="showStatus=!showStatus"
                    class="flex justify-between h-16 p-4 items-center w-full border mb-4">
                    <p>Status</p>
                    <svg class="w-6 h-6">
                        <use v-if="showStatus" :xlink:href="`/storage/website-files/icons.svg#ri-arrow-up-s-line`" />
                        <use v-else :xlink:href="`/storage/website-files/icons.svg#ri-arrow-down-s-line`" />
                    </svg>
                </button>
                <template v-if="showStatus">
                    <div class="w-full h-full p-8">
                        <div class="flex justify-between items-center">
                            <p>Visibility:</p>
                            <button 
                                class="bg-black rounded-full px-4 py-2 text-white hover:bg-white hover:text-black"
                                @click="updateStatus">
                                {{ postStatus }}
                            </button>
                        </div>
                    </div>
                </template>
            </div>
            <div class="menu">
                <button 
                    @click="showShelf=!showShelf"
                    class="flex justify-between h-16 p-4 items-center w-full border mb-4">
                    <p>Shelf</p>
                    <svg class="w-6 h-6">
                        <use v-if="showShelf" :xlink:href="`/storage/website-files/icons.svg#ri-arrow-up-s-line`" />
                        <use v-else :xlink:href="`/storage/website-files/icons.svg#ri-arrow-down-s-line`" />
                    </svg>
                </button>
                <template v-if="showShelf">
                    <div class="w-full h-full p-8">
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
                    class="flex justify-between h-16 p-4 items-center w-full border mb-4">
                    <p>Featured Image</p>
                    <svg class="w-6 h-6">
                        <use v-if="showFeatured" :xlink:href="`/storage/website-files/icons.svg#ri-arrow-up-s-line`" />
                        <use v-else :xlink:href="`/storage/website-files/icons.svg#ri-arrow-down-s-line`" />
                    </svg>
                </button>
                <template v-if="showFeatured">
                    <div class="w-full h-full p-8">
                        <div class="h-64 relative rounded-2xl">
                            <CardImage
                                :locked="hasImage ? true :false"
                                @onDelete="deleteImage"
                                :loading="loading"
                                :image="hasImage ? `${$envImageUrl}${hasImage}` : null"
                                @addImage="addImage" />
                        </div>
                        <div class="flex justify-between items-center">
                            <p>Featured visible:</p>
                            <button 
                                class="bg-black px-4 py-2 rounded-full text-white hover:bg-white hover:text-black mb-4"
                                @click="updateType">
                                {{ postType }}
                            </button>
                        </div>
                        <div v-if="!value.thumbImagePath">
                            Use Event Image
                            <v-select
                                v-model="searchInput"
                                :get-option-label="searchInput => searchInput.model.name"
                                :reduce="searchInput => searchInput.model"
                                :options="options"
                                placeholder="Select event"
                                label="name"
                                @search="debounce" 
                                @input="eventImage"
                                @search:focus="debounce">
                                <template #option="{ model }">
                                    <div class="flex p-3">
                                        <svg class="w-8 h-8 mr-4">
                                            <use :xlink:href="`/storage/website-files/icons.svg#ri-map-pin-line`" />
                                        </svg>
                                        <p>{{ model.name }}</p>
                                    </div>
                                </template>
                            </v-select>
                        </div>
                    </div>
                </template>
            </div>
            <div class="menu">
                <button 
                    @click="showOrder=!showOrder"
                    class="flex justify-between h-16 p-4 items-center w-full border mb-4">
                    <p>Card Order</p>
                    <svg class="w-6 h-6">
                        <use v-if="showOrder" :xlink:href="`/storage/website-files/icons.svg#ri-arrow-up-s-line`" />
                        <use v-else :xlink:href="`/storage/website-files/icons.svg#ri-arrow-down-s-line`" />
                    </svg>
                </button>
                <template v-if="showOrder">
                    <div class="component-body overflow-auto max-h-screen">
                        <draggable
                            v-model="inputVal.cards" 
                            @start="isDragging=true" 
                            @end="reOrder">
                            <div 
                                v-for="card in inputVal.cards"
                                :key="`list${card.id}`"
                                class="border mb-2 p-2 w-full truncate cursor-pointer inline-block">
                                <span 
                                    v-if="card.type==='e' && card.event"
                                    class="text-xl inline"><b class="mr-2">event block </b> ({{ card.event.name  }})</span>
                                <span 
                                    v-else-if="card.type==='i'"
                                    class="text-xl">image block</span>
                                <span 
                                    v-else
                                    class="text-xl inline"><b class="mr-2">text block</b> (<span v-html="card.blurb.slice(0,50)" class="text-force__medium" />)</span>
                            </div>
                        </draggable>
                    </div>
                </template>
            </div>
        </div>
    </div>
</template>

<script>
    import CardImage from './vue-add-image.vue'
    import formValidationMixin from '../../../../mixins/form-validation-mixin'
    export default {
        
        props: [ 'value', 'user', 'community', 'shelves', 'mobile', 'loading'],

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
            hasImage() {
                if (this.inputVal.event_id) { return this.inputVal.featured_event_image.thumbImagePath}
                return this.inputVal.thumbImagePath
            },

        },

        data() {
            return {
                searchInput: this.value.event_id ? this.value.event : null,
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
                    this.options = res.data;
                })
            },
            eventImage() {
                this.$emit('addEventFeaturedImage', this.searchInput);
            },
            update() {
                this.$emit('update')
            },
            addImage(image) {
                this.searchInput=null
                this.$emit('addImage', image)
            },
            deleteImage() {
                this.searchInput=null
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