<template>
    <div>
        <div class="name">
            <h2>{{ shelf.name }}</h2>
        </div>
        <div class="album three image container">
            <div class="row">
                <div 
                    v-for="listing in listings"
                    :key="listing.id"
                    class="col">
                    <div class="card">
                        <a 
                            :href="`/communities/${community.slug}/${listing.slug}`" 
                            class="card-url" />
                        <div class="card-img single">
                            <picture>
                                <source 
                                    type="image/webp" 
                                    :srcset="`/storage/${listing.thumbImagePath}`"> 
                                <img 
                                    style="object-fit:cover" 
                                    loading="lazy" 
                                    :src="`/storage/${listing.thumbImagePath.slice(0, -4)}jpg`" 
                                    :alt="`${listing.name}`">
                            </picture>
                        </div>
                        <div class="card-body">
                            <div class="name">
                                <p>{{ listing.name }}</p>
                            </div>
                            <div class="blurb">
                                <p>{{ listing.blurb }}</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
    export default {
        
        props: [ 'shelf', 'community'],

        components: {  },

        computed: {
        },

        data() {
            return {
                listings:this.shelf ? this.shelf.public_listings_with_cards : null,
            }
        },

        methods: {

        },
    }
</script>