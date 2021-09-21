<template>
    <div 
        :class="{'grid-image' : !hasFeatured}"
        class="card-img">
        <template v-if="hasFeatured">
            <div class="single">
                <picture>
                    <source 
                        type="image/webp" 
                        :srcset="`/storage/${element.thumbImagePath}`"> 
                    <img 
                        style="object-fit:cover" 
                        loading="lazy" 
                        :src="`/storage/${element.thumbImagePath.slice(0, -4)}jpg`" 
                        :alt="`${element.name}`">
                </picture>
            </div>
        </template>
        <template v-else>
            <div class="third">
                <picture v-if="cardImages && cardImages[2]">
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
            <div class="second">
                <picture v-if="cardImages && cardImages[1]">
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
            <div class="first">
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

        props: ['element', 'community'],

        computed: {
            hasFeatured() {
                return this.element.thumbImagePath
            },
            cardImages() {
                return this.element.limited_cards.map( e => e.event && !e.thumbImagePath ? e.event.thumbImagePath : e.thumbImagePath )
                .filter(e => e != null)
            },
        },

        data() {
            return {
                
            }
        },

        methods: {
        },

    }
</script>