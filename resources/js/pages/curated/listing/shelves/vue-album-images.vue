<template>
    <div 
        :class="{'grid-image' : element.type === 'l'}"
        class="card-img">
        <template v-if="element.type === 'l'">
            <div class="single">
                <picture v-if="imageArray && imageArray[0]">
                    <source 
                        type="image/webp" 
                        :srcset="`/storage/${imageArray[0]}`"> 
                    <img 
                        style="object-fit:cover" 
                        loading="lazy" 
                        :src="`/storage/${imageArray[0].slice(0, -4)}jpg`" 
                        :alt="`${element.name}`">
                </picture>
            </div>
            <div class="double">
                <div class="img top">
                    <picture v-if="imageArray && imageArray[1]">
                        <source 
                            type="image/webp" 
                            :srcset="`/storage/${imageArray[1]}`"> 
                        <img 
                            style="object-fit:cover" 
                            loading="lazy" 
                            :src="`/storage/${imageArray[1].slice(0, -4)}jpg`" 
                            :alt="`${element.name}`">
                    </picture>
                </div>
                <div class="img">
                    <picture v-if="imageArray && imageArray[2]">
                        <source 
                            type="image/webp" 
                            :srcset="`/storage/${imageArray[2]}`"> 
                        <img 
                            style="object-fit:cover" 
                            loading="lazy" 
                            :src="`/storage/${imageArray[2].slice(0, -4)}jpg`" 
                            :alt="`${element.name}`">
                    </picture>
                </div>
            </div>
        </template>
        <template v-if="element.type === 'e'">
            <div class="single">
                <picture v-if="imageArray && imageArray[0]">
                    <source 
                        type="image/webp" 
                        :srcset="`/storage/${imageArray[0]}`"> 
                    <img 
                        style="object-fit:cover" 
                        loading="lazy" 
                        :src="`/storage/${imageArray[0].slice(0, -4)}jpg`" 
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
            imageArray() {
                if( this.element.type === 'l' ) {return [this.element.featureable.thumbImagePath].concat(this.element.featureable.limited_cards.map( e => e.thumbImagePath)).filter(e => e != null)}
                return [this.element.featureable.thumbImagePath]
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