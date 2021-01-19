<template>
    <div class="item">
        <div 
        class="image" 
        :style="{ backgroundImage: `url('${this.category ? /storage/ + this.category.imagePath : ''}')` }" />
        <div class="text">
            <h4>
                {{ category.name }}
            </h4>
        </div>
    </div>
</template>

<script>
    export default {

        name: "category-listing-item",

        props: {
            category: { type:Object },
        },

        data() {
            return {
                //
            }
        },

        methods: {

        }
    };
</script>