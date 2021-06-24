<template>
    <div class="listing-show">
        <div class="listing-header">
            <div class="listing-image">
                <picture v-if="headerImage">
                    <source 
                        type="image/webp" 
                        :srcset="`/storage/${headerImage}`"> 
                    <img 
                        :src="`/storage/${headerImage.slice(0, -4)}`" 
                        :alt="`${listing.name} Listing`">
                </picture>
            </div>
        </div>
        <div class="listing-content">
            <div class="listing-name">
                <h2>{{ listing.name }}</h2>
            </div>
            <div class="listing-owner">
                <a :href="`/communities/${community.slug}`">{{community.name}}</a>
            </div>
            <div class="listing-blurb blurb">
                <div v-html="listing.blurb" />
            </div>
            <div 
                v-for="card in listing.cards"
                :key="card.id"
                class="listing-card">
                <Card 
                    @update="updateListing"
                    :parentCard="card" />
            </div>
        </div>
    </div>
</template>

<script>
    import Card  from './card.vue'
    export default {
        
        props: [ 'value', 'user', 'owner', 'community'],

        components: { Card },

        computed: {

        },

        data() {
            return {
                listing: this.value,
                headerImage: window.innerWidth < 768 ? this.value.thumbImagePath : this.value.largeImagePath,
            }
        },

        methods: {
        }

    }
</script>