<template>
    <div class="card-img">
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
    </div>
</template>

<script>
    export default {

        props: ['element', 'community'],

        computed: {

        },

        data() {
            return {
                imageArray: [this.element.thumbImagePath].concat(this.element.limited_cards.map( e => e.thumbImagePath)).filter(e => e != null),
            }
        },

        methods: {
        },

    }
</script>