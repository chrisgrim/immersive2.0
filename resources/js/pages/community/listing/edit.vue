<template>
    <div class="listing-edit lay-a">
        <div class="breadcrumbs">
            <p v-if="owner">
                <a :href="`/index/${community.slug}/listing`">{{community.name}}</a> > {{listing.name}}
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
                                @blur="submitListing"
                                v-model="listing.name"
                                :class="{ 'error': $v.listing.name.$error }"
                                placeholder="Collection Name">
                            <div v-if="$v.listing.name.$error" class="validation-error">
                                <p class="error" v-if="!$v.listing.name.required">Please add a name.</p>
                                <p class="error" v-if="!$v.listing.name.maxLength">The name is too long.</p>
                            </div>
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
                                @blur="submitListing"
                                v-model="listing.blurb"
                                :class="{ 'error': $v.listing.blurb.$error }"
                                placeholder="Collection tag line">
                            <div v-if="$v.listing.blurb.$error" class="validation-error">
                                <p class="error" v-if="!$v.listing.blurb.maxLength">The name is too long.</p>
                            </div>
                        </div>
                    </template>
                    <template v-else>
                        <div 
                            @click="tagEdit=true"
                            class="listing-blurb blurb">
                            <p>{{ listing.blurb }}</p>
                        </div>
                    </template>
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
                    <EventBlock 
                        v-if="blockType==='e'"
                        @cancel="clear"
                        @update="updateListing"
                        :listing="listing" />
                    <ImageBlock 
                        v-if="blockType==='i'"
                        @update="updateListing"
                        :listing="listing" />
                    <TextBlock 
                        v-if="blockType==='t'"
                        @cancel="clear"
                        @update="updateListing"
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
                            @click="showFeatured=!showFeatured"
                            class="component-title">
                            <p>Featured Image</p>
                            <svg v-if="showFeatured"><use :xlink:href="`/storage/website-files/icons.svg#ri-arrow-up-s-line`" /></svg>
                            <svg v-else><use :xlink:href="`/storage/website-files/icons.svg#ri-arrow-down-s-line`" /></svg>
                        </button>
                        <template v-if="showFeatured">
                            <div class="component-body">
                                <div class="listing-image">
                                    <CardImage
                                        :image="`/storage/${headerImage}`"
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
                                        {{ card.name ? card.name : `card${card.id}` }}
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
    </div>
</template>

<script>
    import Card  from './card.vue'
    import EditCard  from './card-edit.vue'
    import EventBlock from './block-event.vue'
    import ImageBlock from './block-image.vue'
    import TextBlock from './block-text.vue'
    import CardImage from '../../../components/Upload-Image.vue'
    import Draggable from "vuedraggable";
    import formValidationMixin from '../../../mixins/form-validation-mixin'
    import { required, maxLength } from 'vuelidate/lib/validators';
    export default {
        
        props: [ 'value', 'user', 'owner', 'community'],

        mixins: [formValidationMixin],

        components: { Card, CardImage, EventBlock, Draggable, EditCard, ImageBlock, TextBlock },

        computed: {
            listingStatus() {
                if (this.listing.status === 'p') { return 'live'}
                return 'Draft'
            }
        },

        data() {
            return {
                listing: this.value,
                headerImage: window.innerWidth < 768 ? this.value.thumbImagePath : this.value.largeImagePath,
                onEdit: false,
                buttonOptions:false,
                blockType:null,
                formData: new FormData(),
                showStatus: true,
                showFeatured: true,
                showOrder: false,
                updated: false,
                nameEdit: false,
                tagEdit: false,
            }
        },

        methods: {
            async submitListing() {
                if ( this.checkVuelidate()) { return }
                this.addListingData();
                await axios.post(`/listings/${this.listing.slug}`, this.formData)
                .then( res => {
                    this.onUpdated();
                    this.clear();
                })
            },
            async updateListOrder() {
                var list = this.listing.cards.map(function(item, index){
                    item.order = index;
                    return item;
                })
                await axios.patch(`/listings/${this.listing.slug}/order`, list)
                .then( res => {
                    console.log(res.data);
                })
            },
            async resetListing() {
                await axios.get(`/listings/${this.listing.slug}/fetch`)
                .then( res => { this.listing = res.data })
                this.onEdit = false;
            },
            updateStatus() {
                if (this.listing.status === 'd') {
                    this.listing.status = 'p'
                } else {
                    this.listing.status = 'd'
                }
                this.submitListing();
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
            },
            addListingData() {
                this.formData.append('_method', 'PUT');
                this.formData.append('name', this.listing.name);
                this.formData.append('blurb', this.listing.blurb);
                this.formData.append('community_id', this.community.id);
                this.formData.append('status', this.listing.status);
            },
            updateListing(value) {
                this.clear()
                this.listing = value
            },
            onUpdated() {
                this.$v.$reset();
                this.updated = true;
                setTimeout(() => this.updated = false, 3000);
            },
            showAddButtonOptions() {
                this.clear();
                this.resetListing();
                this.buttonOptions =! this.buttonOptions;
            },
            selectButton(val) {
                this.buttonOptions = false;
                this.blockType = val;
            },
            createCard() {

            },
            clear() {
                this.onEdit = false;
                this.blockType = null;
                this.nameEdit = false;
                this.tagEdit = false;
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
            },
        },

    }
</script>