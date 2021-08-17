<template>
    <div class="shelf">
        <div class="name">
            <h3>{{ section.name }}</h3>
        </div>
        <div 
            v-if="listings && listings.length"
            class="listings">
            <ListingAlbum
                :title="true"
                :text="true"
                :section="section"
                :community="community"
                v-model="listings" />
        </div>
    </div>
</template>

<script>
    import ListingAlbum from './vue-album-show.vue'
    export default {
        
        props: [ 'section', 'owner', 'community' ],

        components: { ListingAlbum },

        computed: {

        },

        data() {
            return {
                listings:this.section.public_featured,
            }
        },

        methods: {
            
        },
    }
</script>