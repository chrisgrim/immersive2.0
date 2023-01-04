<template>
    <div class="absolute inset-[-1px]">
        <picture>
            <source 
                type="image/webp" 
                :srcset="`${envImageUrl}${ hasFeatured }`"> 
            <img 
                class="h-full w-full absolute object-cover align-bottom"
                loading="lazy" 
                :src="`${envImageUrl}${hasFeatured.slice(0, -4)}jpg`" 
                :alt="`${element.name}`">
        </picture>
    </div>
</template>

<script>
    export default {
        props: [ 'element'],

        computed: {
            hasFeatured() {
                if (this.element.event_id) { return this.element.featured_event_image.thumbImagePath}
                return this.element.largeImagePath
            },
        },

        data() {
            return {
            };
        },
    }
</script>