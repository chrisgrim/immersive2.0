<template>
    <div>
        <div class="name">
            <h2>{{ section.name }}</h2>
        </div>
        <div class="album four image container">
            <div class="row">
                <div 
                    v-for="(listing) in listings"
                    :key="listing.featureable.id"
                    class="col">
                    <div class="card">
                        <a 
                            :href="url(listing)" 
                            class="card-url" />
                        <div class="card-img single">
                            <picture v-if="imageArray(listing) && imageArray(listing)[0]">
                                <source 
                                    type="image/webp" 
                                    :srcset="`/storage/${imageArray(listing)[0]}`"> 
                                <img 
                                    style="object-fit:cover" 
                                    loading="lazy" 
                                    :src="`/storage/${imageArray(listing)[0].slice(0, -4)}jpg`" 
                                    :alt="`${listing.featureable.name}`">
                            </picture>
                        </div>
                        <div class="card-body">
                            <div class="name">
                                <p>{{ listing.featureable.name }}</p>
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
        
        props: [ 'section', 'community'],

        components: {  },

        computed: {

        },

        data() {
            return {
                listings:this.section.public_featured,
            }
        },

        methods: {
            url(element) {
                if (element.type === 'e') { return `/events/${element.featureable.slug}` }
                if (element.type === 'l') { return `/communities/${this.community.slug}/${element.featureable.slug}` }
            },
            imageArray(element) {
                if( element.type === 'l' && !element.featureable.thumbImagePath ) {return [element.featureable.thumbImagePath].concat(element.featureable.limited_cards.map( e => e.thumbImagePath)).filter(e => e != null)}
                return [element.featureable.thumbImagePath]
            }
        },
    }
</script>