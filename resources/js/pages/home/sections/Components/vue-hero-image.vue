<template>
    <div class="absolute inset-[-1px]">
        <picture>
            <source 
                type="image/webp" 
                :srcset="`${envImageUrl}${element.largeImagePath }`"> 
            <img 
                class="h-full w-full absolute object-cover align-bottom"
                loading="lazy" 
                :src="`${envImageUrl}${element.largeImagePath.slice(0, -4)}jpg`" 
                :alt="`${element.name}`">
        </picture>
    </div>
</template>

<script>
    export default {
        props: [ 'element'],

        computed: {
            hasFeatured() {
                return this.element.thumbImagePath 
            },
        },

        data() {
            return {
                envImageUrl: process.env.MIX_IMAGE_URL,
            };
        },
    }
</script>