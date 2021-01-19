<template>
    <div class="item">
        <div 
        class="image" 
        :style="categoryImage" />
        <div class="text">
            <h4>
                {{ category.name }}
            </h4>
        </div>
    </div>
</template>

<script>
    export default {

        name: "catitem",

        props: {
            category: { type:Object },
        },

        data() {
            return {
                categoryImage: '',
            }
        },

        methods: {
            canUseWebP() {
                let webp = (document.createElement('canvas').toDataURL('image/webp').indexOf('data:image/webp') == 0);
                if (this.category.thumbImagePath && webp) {
                    return this.categoryImage = `background-image: url('/storage/${this.category.thumbImagePath}')`
                };
                if (this.category.thumbImagePath) {
                    return this.categoryImage = `background-image: url('/storage/${this.category.thumbImagePath.slice(0, -4)}jpg')`
                }
            },
        },

        mounted() {
            this.canUseWebP();
        },
    };
</script>