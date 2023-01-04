<template>
    <div 
        :class="{'grid-image' : !hasFeatured}"
        class="relative overflow-hidden grid rounded-xl">
        <template v-if="hasFeatured">
            <div class="w-full relative pt-[100%]">
                <picture>
                    <source 
                        type="image/webp" 
                        :srcset="`${$envImageUrl}${hasFeatured}`"> 
                    <img 
                        class="h-full w-full absolute object-cover align-bottom inset-0"
                        loading="lazy" 
                        :src="`${$envImageUrl}${hasFeatured.slice(0, -4)}jpg`" 
                        :alt="`${element.name}`">
                </picture>
            </div>
        </template>
        <template v-else>
            <div class="w-full relative pt-[100%]">
                <picture v-if="cardImages[2]">
                    <source 
                        type="image/webp" 
                        :srcset="`${$envImageUrl}${cardImages[2]}`"> 
                    <img 
                        class="h-full w-full absolute object-cover align-bottom inset-0 ml-0 rounded-xl"
                        style="object-fit:cover" 
                        loading="lazy" 
                        :src="`${$envImageUrl}${cardImages[2].slice(2, -4)}jpg`" 
                        :alt="`${element.name}`">
                </picture>
                <picture v-if="cardImages[1]">
                    <source 
                        type="image/webp" 
                        :srcset="`${$envImageUrl}${cardImages[1]}`"> 
                    <img 
                        :class="[ cardImages[2] ? 'ml-[-15%]' : '']"
                        class="h-full w-full absolute object-cover align-bottom inset-0 rounded-xl border-2 border-white"
                        style="object-fit:cover" 
                        loading="lazy" 
                        :src="`${$envImageUrl}${cardImages[1].slice(0, -4)}jpg`" 
                        :alt="`${element.name}`">
                </picture>
                <picture v-if="cardImages[0]">
                    <source 
                        type="image/webp" 
                        :srcset="`${$envImageUrl}${cardImages[0]}`"> 
                    <img
                        :class="[ cardImages[2] && cardImages[1] ? 'ml-[-30%]' : '', cardImages[1] ? 'ml-[-15%]' : '']"
                        class="h-full w-full absolute object-cover align-bottom inset-0 rounded-xl border-2 border-white"
                        style="object-fit:cover" 
                        loading="lazy" 
                        :src="`${$envImageUrl}${cardImages[0].slice(0, -4)}jpg`" 
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
        },

        computed: {
            hasFeatured() {
                if (this.element.event_id) { return this.element.featured_event_image.largeImagePath}
                return this.element.largeImagePath
            },
            cardImages() {
                return this.element.limited_cards.map( e => e.event && !e.thumbImagePath ? e.event.thumbImagePath : e.thumbImagePath ).filter(n => n)
            },
        },

        data() {
            return {
            };
        },
    }
</script>