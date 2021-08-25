<template>
    <div class="listing-edit lay-a">
        <div class="breadcrumbs">
            <p v-if="owner">
                <a :href="`/communities/${community.slug}/edit`">{{community.name}}</a> > {{listing.name}}
            </p>
            <p v-else>
                <a :href="`/communities/${community.slug}`">{{community.name}}</a> > {{listing.name}}
            </p>
        </div>
        <div class="wrapper">
            <div class="content">
                <div class="edit-listing__body grid">
                    <template v-if="nameEdit">
                        <div class="field h3">
                            <input 
                                type="text" 
                                v-model="listing.name"
                                @input="clearErrors"
                                :class="{ 'error': $v.listing.name.$error }"
                                placeholder="Collection Name">
                            <div v-if="$v.listing.name.$error" class="validation-error">
                                <p class="error" v-if="!$v.listing.name.required">Please add a name.</p>
                                <p class="error" v-if="!$v.listing.name.maxLength">The name is too long.</p>
                            </div>
                        </div>
                        <div class="editor__footer borderless">
                            <button class="outline" @click="resetListing">Cancel</button>
                            <button @click="patchListing">Save</button>
                        </div>
                    </template>
                    <template v-else>
                        <div 
                            @click="nameEdit=true"
                            class="listing-name">
                            <h2>{{ listing.name }}</h2>
                        </div>
                    </template>
                    <template v-if="tagEdit">
                        <div class="field">
                            <input 
                                type="text"
                                @blur="patchListing"
                                v-model="listing.blurb"
                                :class="{ 'error': $v.listing.blurb.$error }"
                                placeholder="Collection tag line">
                            <div v-if="$v.listing.blurb.$error" class="validation-error">
                                <p class="error" v-if="!$v.listing.blurb.maxLength">The name is too long.</p>
                            </div>
                        </div>
                        <div class="editor__footer borderless">
                            <button class="outline" @click="resetListing">Cancel</button>
                            <button @click="patchListing">Save</button>
                        </div>
                    </template>
                    <template v-else>
                        <div 
                            @click="tagEdit=true"
                            class="listing-blurb blurb">
                            <p>{{ listing.blurb }}</p>
                        </div>
                    </template>
                    <draggable
                        v-model="listing.cards" 
                        @start="isDragging=true" 
                        @end="debounce">
                        <div 
                            v-for="card in listing.cards"
                            :key="card.id"
                            class="listing-card">
                            <div class="edit-block">
                                <EditCard 
                                    @update="updateListing"
                                    :parent-card="card" />
                            </div>
                        </div>
                    </draggable>
                    <EventBlock 
                        v-if="blockType==='e'"
                        @cancel="clear"
                        @update="patchListing"
                        :listing="listing" />
                    <ImageBlock 
                        v-if="blockType==='i'"
                        @update="patchListing"
                        :listing="listing" />
                    <TextBlock 
                        v-if="blockType==='t'"
                        @cancel="clear"
                        @update="patchListing"
                        :listing="listing" />
                    <div class="add-new-listing-card">
                        <button 
                            @click="showAddButtonOptions"
                            class="main">
                            add new card
                            <svg class="remix">
                                <use v-if="!buttonOptions" :xlink:href="`/storage/website-files/icons.svg#ri-add-circle-line`" />
                                <use v-else :xlink:href="`/storage/website-files/icons.svg#ri-close-circle-line`" />
                            </svg>
                        </button>
                        <template v-if="buttonOptions">
                            <button class="sub" @click="selectButton('i')">
                                Image Block
                            </button>
                            <button class="sub" @click="selectButton('t')">
                                Text Block
                            </button>
                            <button class="sub" @click="selectButton('e')">
                                Event Block
                            </button>
                        </template>
                    </div>
                </div>
            </div>
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
                                        {{ listingStatus }}
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
                                    v-model="listing.section_id"
                                    :reduce="section => section.id"
                                    :options="sections"
                                    :class="{ 'error': $v.listing.section_id.$error }"
                                    placeholder="Shelf"
                                    @input="patchListing"
                                    label="name" />
                                <div v-if="$v.listing.section_id.$error" class="validation-error">
                                    <p class="error" v-if="!$v.listing.section_id.required">Please select the shelf.</p>
                                </div>
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
                                <div class="listing-image">
                                    <template v-if="listing.thumbImagePath">
                                        <div class="delete">
                                            <button 
                                                @click="deleteFeaturedImage"
                                                class="icon">
                                                <svg>
                                                    <use :xlink:href="`/storage/website-files/icons.svg#ri-close-line`" />
                                                </svg>
                                            </button>
                                        </div>
                                    </template>
                                    <CardImage
                                        :width="600"
                                        :height="300"
                                        :image="`/storage/${listing.thumbImagePath}`"
                                        @addImage="addImage" />
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
                                    v-model="listing.cards" 
                                    @start="isDragging=true" 
                                    @end="debounce">
                                    <div 
                                        v-for="(card) in listing.cards"
                                        :key="`list${card.id}`"
                                        class="nav-card__item">
                                        <span v-if="card.name">event</span>
                                        <span v-else> {{ card.blurb ? `text` : `image` }} </span>
                                    </div>
                                </draggable>
                            </div>
                        </template>
                    </div>
                </div>
            </div>
        </div>
        <transition name="slide-fade">
            <div 
                v-if="updated" 
                class="updated-notifcation">
                <p>Your collection has been updated.</p>
            </div>
        </transition>
        <div v-if="serverErrors" class="updated-notifcation">
            <transition-group name="slide-fade">
                <ul 
                    v-for="(error, index) in serverErrors"
                    :key="`name${index}`">
                    <li>
                        <p> {{ error.toString() }}</p>
                    </li>
                </ul>
            </transition-group>
        </div>
    </div>
</template>

<script>
    import EditCard  from './cards/card-edit.vue'
    import EventBlock from './cards/block-event.vue'
    import ImageBlock from './cards/block-image.vue'
    import TextBlock from './cards/block-text.vue'
    import CardImage from '../../../components/Upload-Image.vue'
    import Draggable from "vuedraggable";
    import formValidationMixin from '../../../mixins/form-validation-mixin'
    import { required, maxLength } from 'vuelidate/lib/validators';
    export default {
        
        props: [ 'value', 'user', 'owner', 'community', 'sections'],

        mixins: [formValidationMixin],

        components: { CardImage, EventBlock, Draggable, EditCard, ImageBlock, TextBlock },

        computed: {
            listingStatus() {
                if (this.listing.status === 'p') { return 'live'}
                return 'Draft'
            }
        },

        data() {
            return {
                listing: this.value,
                listingBeforeEdit: { ...this.value },
                onEdit: false,
                buttonOptions:false,
                blockType:null,
                formData: new FormData(),
                serverErrors: null,
                showStatus: false,
                showFeatured: false,
                showOrder: false,
                showShelf: false,
                updated: false,
                nameEdit: false,
                tagEdit: false,
            }
        },

        methods: {

            async patchListing() {
                if ( this.checkVuelidate()) { return }
                this.addListingData();
                await axios.post(`/communities/${this.community.slug}/${this.listing.slug}/update`, this.formData)
                .then( res => {
                    window.history.pushState(null, null, "/communities" + `/${this.community.slug}/${res.data.slug}/edit`);
                    this.listing = res.data;
                    this.onUpdated();
                    this.clear();
                })
                .catch(err => {
                    this.onErrors(err);
                });
            },
            async deleteFeaturedImage() {
                await axios.put(`/listings/${this.listing.slug}/update`, {name:this.listing.name,blurb:this.listing.blurb, deleteImage:true})
                .then( res => {
                    this.listing = res.data;
                    this.onUpdated();
                    this.clear();
                })
            },
            async updateListOrder() {
                var list = this.listing.cards.map(function(item, index){
                    item.order = index;
                    return item;
                })
                await axios.put(`/cards/${this.listing.slug}/order`, list)
                this.onUpdated();
            },
            resetListing() {
                this.listing = { ...this.listingBeforeEdit }
                this.clear();
            },
            updateStatus() {
                if (this.listing.status === 'd') {
                    this.listing.status = 'p'
                } else {
                    this.listing.status = 'd'
                }
                this.patchListing();
            },
            debounce() {
                if (this.timeout) 
                    clearTimeout(this.timeout); 
                this.timeout = setTimeout(() => {
                    this.updateListOrder();
                }, 500); // delay
            },
            addImage(image) {
                this.formData.append('image', image);
                this.patchListing();
            },
            addListingData() {
                this.formData.append('_method', 'PUT');
                this.formData.append('name', this.listing.name);
                this.formData.append('blurb', this.listing.blurb);
                this.formData.append('community_id', this.community.id);
                this.formData.append('section_id', this.listing.section_id);
                this.formData.append('status', this.listing.status);
            },
            updateListing(value) {
                this.clear()
                this.listing = value
                this.onUpdated();
            },
            onUpdated() {
                this.$v.$reset();
                this.updated = true;
                setTimeout(() => this.updated = false, 3000);
            },
            showAddButtonOptions() {
                this.clear();
                this.buttonOptions =! this.buttonOptions;
            },
            selectButton(val) {
                this.buttonOptions = false;
                this.blockType = val;
            },
            clear() {
                this.onEdit = false;
                this.blockType = null;
                this.nameEdit = false;
                this.tagEdit = false;
            },
            clearErrors() {
                this.serverErrors = null
            }
        },

        validations: {
            listing: {
                name: {
                    required,
                    maxLength: maxLength(80),
                },
                blurb: {
                    maxLength: maxLength(255)
                },
                section_id: {
                    required
                }
            },
        },

    }
</script>