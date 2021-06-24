<template>
    <div class="listing-show">
        <div class="listing-header">
            <div class="listing-image">
                <template v-if="onEdit">
                    <CardImage
                        :image="`/storage/${headerImage}`"
                        @addImage="addImage" />
                </template>
                <template v-else>
                    <picture v-if="headerImage">
                        <source 
                            type="image/webp" 
                            :srcset="`/storage/${headerImage}`"> 
                        <img 
                            :src="`/storage/${headerImage.slice(0, -4)}`" 
                            :alt="`${listing.name} Listing`">
                    </picture>
                </template>
            </div>
        </div>
        <div class="edit-listing__body grid">
            <div class="edit-listing__body">
                <template v-if="onEdit">
                    <div class="field h2">
                        <input 
                            type="text" 
                            v-model="listing.name"
                            :class="{ 'error': $v.listing.name.$error }"
                            placeholder="Listing Name">
                        <div v-if="$v.listing.name.$error" class="validation-error">
                            <p class="error" v-if="!$v.listing.name.required">Please add a name.</p>
                            <p class="error" v-if="!$v.listing.name.maxLength">The name is too long.</p>
                        </div>
                    </div>
                    <div class="listing-blurb">
                        <tiptap 
                            @cancel="resetListing"
                            @save="submitListing"
                            :class="{ 'error': $v.listing.blurb.$error }"
                            v-model="listing.blurb" />
                        <div v-if="$v.listing.blurb.$error" class="validation-error">
                            <p class="error" v-if="!$v.listing.blurb.required">Please add a description.</p>
                            <p class="error" v-if="!$v.listing.blurb.maxLength">The description is too long.</p>
                        </div>
                    </div>
                </template>
                <template v-else>
                    <div class="listing-name">
                        <h2>{{ listing.name }}</h2>
                    </div>
                    <div class="listing-owner">
                        <a :href="`/communities/${community.slug}`">{{community.name}}</a>
                    </div>
                    <div class="listing-blurb blurb">
                        <div v-html="listing.blurb" />   
                        <button
                            class="edit"
                            @click="onEdit=true">Edit</button>
                    </div>
                </template>
                <div 
                    v-for="card in listing.cards"
                    :key="card.id"
                    class="listing-card">
                    <Card 
                        :owner="owner"
                        @update="updateListing"
                        :parentCard="card" />
                </div>
                <div v-if="cardType" class="listing-card">
                    <InternalCard 
                        @update="updateListing"
                        :listing="listing" 
                        v-if="cardType==='i'" />
                    <ExternalCard 
                        @update="updateListing"
                        :listing="listing" 
                        v-if="cardType==='e'" />
                    <BlankCard 
                        @update="updateListing"
                        :listing="listing" 
                        v-if="cardType==='b'" />
                </div>
                <div class="add-new-listing-card">
                    <button 
                        @click="showAddButtonOptions"
                        class="main">
                        add new 
                        <svg class="remix">
                            <use v-if="!buttonOptions" :xlink:href="`/storage/website-files/icons.svg#ri-add-circle-line`" />
                            <use v-else :xlink:href="`/storage/website-files/icons.svg#ri-close-circle-line`" />
                        </svg>
                    </button>
                    <template v-if="buttonOptions">
                        <button class="sub" @click="selectButton('i')">
                            Event from Ei
                        </button>
                        <button class="sub" @click="selectButton('e')">
                            Event outside EI
                        </button>
                        <button class="sub" @click="selectButton('b')">
                            Blank card
                        </button>
                    </template>
                </div>
            </div>
            <div class="edit-listing__nav">
                <div class="sticky">
                    <div class="status">
                        <h4>Status:</h4>
                        <button @click="updateStatus">{{ listingStatus }}</button>
                    </div>
                    <div class="div">
                        <h4>List Cards:</h4>
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
                </div>
            </div>
        </div>
    </div>
</template>

<script>
    import Card  from './card.vue'
    import Tiptap from '../../../components/Tiptap.vue'
    import InternalCard from './internal-card.vue'
    import ExternalCard from './external-card.vue'
    import BlankCard from './blank-card.vue'
    import CardImage from '../../../components/Upload-Image.vue'
    import Draggable from "vuedraggable";
    import formValidationMixin from '../../../mixins/form-validation-mixin'
    import { required, maxLength } from 'vuelidate/lib/validators';
    export default {
        
        props: [ 'value', 'user', 'owner', 'community'],

        mixins: [formValidationMixin],

        components: { Card, CardImage, Tiptap, InternalCard, ExternalCard, BlankCard, Draggable },

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
                cardType:null,
                formData: new FormData(),
            }
        },

        methods: {
            async submitListing() {
                if ( this.checkVuelidate()) { return }
                this.addListingData();
                await axios.post(`/listings/${this.listing.slug}`, this.formData)
                .then( res => {
                })
                location.reload();
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
            showAddButtonOptions() {
                this.clear();
                this.resetListing();
                this.buttonOptions =! this.buttonOptions;
            },
            selectButton(val) {
                this.buttonOptions = false;
                this.cardType = val;
            },
            createCard() {

            },
            clear() {
                this.onEdit = false;
                this.cardType = null;
            }
        },

        validations: {
            listing: {
                name: {
                    required,
                    maxLength: maxLength(50),
                },
                blurb: {
                    required,
                    maxLength: maxLength(50000)
                },
            },
        },

    }
</script>