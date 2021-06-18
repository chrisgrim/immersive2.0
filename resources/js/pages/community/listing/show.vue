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
                    <picture>
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
        <div class="listing-content">
            <template v-if="onEdit">
                <div class="field h2">
                    <input 
                        type="text" 
                        v-model="listing.name"
                        placeholder="Listing Name">
                </div>
                <div class="listing-blurb">
                    <tiptap 
                        @cancel="resetListing"
                        @save="submitListing"
                        v-model="listing.blurb" />
                </div>
            </template>
            <template v-else>
                <div class="Listing-name">
                    <h2>{{ listing.name }}</h2>
                </div>
                <div class="listing-blurb">
                    <div v-html="listing.blurb" />
                    <template v-if="owner">
                        <button
                            class="edit"
                            @click="onEdit=true">Edit</button>
                    </template>
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
            <div 
                v-if="owner"
                class="add-new-listing-card">
                <button 
                    @click="showAddButtonOptions"
                    class="main">
                    add new 
                    <svg class="remix">
                        <use v-if="!buttonOptions" :xlink:href="`/website-files/icons.svg#ri-add-circle-line`" />
                        <use v-else :xlink:href="`/website-files/icons.svg#ri-close-circle-line`" />
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
    </div>
</template>

<script>
    import Card  from './card.vue'
    import Tiptap from '../../../components/Tiptap.vue'
    import InternalCard from './internal-card.vue'
    import ExternalCard from './external-card.vue'
    import BlankCard from './blank-card.vue'
    import CardImage from '../../../components/Upload-Image.vue'
    export default {
        
        props: [ 'value', 'user', 'owner', 'community'],

        components: { Card, CardImage, Tiptap, InternalCard, ExternalCard, BlankCard },

        computed: {

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
                this.addListingData();
                await axios.post(`/listings/${this.listing.slug}`, this.formData)
                .then( res => {
                })
                location.reload();
            },
            async resetListing() {
                await axios.get(`/listings/${this.listing.slug}/fetch`)
                .then( res => { this.listing = res.data })
                this.onEdit = false;
            },
            addImage(image) {
                this.formData.append('image', image);
            },
            addListingData() {
                this.formData.append('_method', 'PUT');
                this.formData.append('name', this.listing.name);
                this.formData.append('blurb', this.listing.blurb);
                this.formData.append('community_id', this.community.id);
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
        }

    }
</script>