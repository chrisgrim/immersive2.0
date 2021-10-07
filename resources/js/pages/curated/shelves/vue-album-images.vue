<template>
    <div 
        :class="{'grid-image' : !hasFeatured}"
        class="card-img">
        <template v-if="hasFeatured">
            <div class="single">
                <picture>
                    <source 
                        type="image/webp" 
                        :srcset="`/storage/${hasFeatured}`"> 
                    <img 
                        style="object-fit:cover" 
                        loading="lazy" 
                        :src="`/storage/${hasFeatured.slice(0, -4)}jpg`" 
                        :alt="`${element.name}`">
                </picture>
            </div>
        </template>
        <template v-else>
            <div 
                :class="imageTotal"
                v-if="imageTotal === 'three'"
                class="third">
                <picture>
                    <source 
                        type="image/webp" 
                        :srcset="`/storage/${cardImages[2]}`"> 
                    <img 
                        style="object-fit:cover" 
                        loading="lazy" 
                        :src="`/storage/${cardImages[2].slice(2, -4)}jpg`" 
                        :alt="`${element.name}`">
                </picture>
            </div>
            <div 
                :class="imageTotal"
                v-if="imageTotal === 'two' || imageTotal === 'three'"
                class="second">
                <picture>
                    <source 
                        type="image/webp" 
                        :srcset="`/storage/${cardImages[1]}`"> 
                    <img 
                        style="object-fit:cover" 
                        loading="lazy" 
                        :src="`/storage/${cardImages[1].slice(0, -4)}jpg`" 
                        :alt="`${element.name}`">
                </picture>
            </div>
            <div
                :class="imageTotal"
                v-if="imageTotal === 'one' || imageTotal === 'two' || imageTotal === 'three'"
                class="first">
                <picture v-if="cardImages && cardImages[0]">
                    <source 
                        type="image/webp" 
                        :srcset="`/storage/${cardImages[0]}`"> 
                    <img 
                        style="object-fit:cover" 
                        loading="lazy" 
                        :src="`/storage/${cardImages[0].slice(0, -4)}jpg`" 
                        :alt="`${element.name}`">
                </picture>
            </div>
        </template>
    </div>
</template>

<script>
    export default {

        props: {
            element: {
                type: Object,
                default: null
            },
            community: {
                type: Object,
                default: null
            },
            quality: {
                type: String,
                default: 'thumb'
            },
        },

        computed: {
            hasFeatured() {
                return this.quality === 'thumb' ? this.element.thumbImagePath : this.element.largeImagePath
            },
            cardImages() {
                if (this.element.limited_cards) { return this.element.limited_cards.map( e => e.event && !e.thumbImagePath ? e.event.thumbImagePath : e.thumbImagePath ) }
                return this.element.cards.map( e => e.event && !e.thumbImagePath ? e.event.thumbImagePath : e.thumbImagePath )
                .filter(e => e != null)
            },
            imageTotal() {
                if (this.cardImages && this.cardImages[2]) {return 'three'}
                if (this.cardImages && this.cardImages[1]) {return 'two'}
                if (this.cardImages && this.cardImages[0]) {return 'one'}
                return null
            }
        },

        data() {
            return {
                
            }
        },

        methods: {
        },

    }
</script>