<template>
    <div class="community-show">
        <div class="com-header">
            <div v-if="owner" class="com-edit">
                <a :href="`/communities/${community.slug}/edit`">
                    <button>Edit Community</button>
                </a>
            </div>
            <div class="com-header__content">
                <div class="com-header__wrapper">
                    <div class="com-name">
                        <h2>{{ community.name }}</h2>
                    </div>
                    <div class="com-blurb">
                        <p>{{ community.blurb }}</p>
                    </div>
                </div>
            </div>
            <div class="com-image">
                <picture>
                    <source 
                        type="image/webp" 
                        :srcset="`/storage/${headerImage}`"> 
                    <img 
                        :src="`/storage/${headerImage.slice(0, -4)}jpg`" 
                        :alt="`${community.name} Community`">
                </picture>
            </div>
        </div>
        <div class="com-intro">
            <div class="li-listing">
                <div 
                    class="shelves" 
                    v-for="(shelf, index) in shelvesWithListings"
                    :key="shelf.id">
                    <Shelf 
                        :community="community"
                        :shelf="shelf" />
                </div>
            </div>
            <div class="com-curators">
                <h4>Meet your curators</h4>
                <div 
                    v-for="curator in community.curators"
                    :key="curator.id"
                    class="curator">
                    <template v-if="curator && curator.thumbImagePath">
                        <picture>
                            <source 
                                type="image/webp" 
                                :srcset="`/storage/${curator.thumbImagePath}`"> 
                            <img 
                                :src="`/storage/${curator.thumbImagePath.slice(0, -4)}`" 
                                :alt="`${curator.name} Community`">
                        </picture>
                    </template>
                    {{ curator.name }}
                </div>
            </div>
        </div>
    </div>
</template>

<script>
    import Shelf from './listing/shelves/shelf-show.vue'
    export default {
        
        props: [ 'value', 'shelves', 'owner' ],

        components: { Shelf },

        computed: {
            shelvesWithListings() {
                return this.shelves.filter( shelf => shelf.public_listings_with_cards.length)
            }
        },

        data() {
            return {
                community: this.value,
                headerImage: window.innerWidth < 768 ? this.value.thumbImagePath : this.value.largeImagePath,
            }
        },

        methods: {
        },

    }
</script>