<template>
    <div class="shelf">
        <div class="name">
            <h3>{{ shelf.name }}</h3>
        </div>
        <div 
            v-if="listings && listings.length"
            class="listings">
            <ListingAlbum
                :title="true"
                :text="true"
                :shelf="shelf"
                :community="community"
                v-model="listings" />
        </div>
    </div>
</template>

<script>
    import ListingAlbum from './vue-album-show.vue'
    export default {
        
        props: [ 'shelf', 'owner', 'community' ],

        components: { ListingAlbum },

        computed: {

        },

        data() {
            return {
                listings:this.shelf.public_listings_with_cards,
            }
        },

        methods: {
            
        },
    }
</script>