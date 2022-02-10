<template>
    <div class="fixed w-full inset-0 z-[2003] bg-white overflow-auto">
        <div class="flex justify-between w-full h-[7.5rem] items-center border-b text-center p-8">
            <button 
                @click="onClose"
                class="ml-[-1rem] w-16 h-16 flex items-center justify-center border-none">
                <svg class="border-none flex justify-center w-8 h-8">
                    <use :xlink:href="`/storage/website-files/icons.svg#ri-close-line`" />
                </svg>
            </button>
            <div class="title"> Search </div>
            <button 
                @click="onClear"
                class="w-16 h-16 flex items-center justify-center border-none">
                Clear
            </button>
        </div>
        <div class="px-8 pb-40">
            <div class="relative border-b my-4 pb-8 h-56">
                <h3>Price Range</h3>
                <VueFilterPrice
                    :mobile="true"
                    v-model="data.price" />
            </div>
            <div class="relative border-b my-4 pb-8">
                <h3>Categories</h3>
                <VueFilterCategory 
                    :mobile="true"
                    :categories="categories"
                    v-model="data.category"/>
            </div>
            <div class="relative border-b my-4 pb-8">
                <h3>Tags</h3>
                <VueFilterTag 
                    :mobile="true"
                    :tags="tags"
                    v-model="data.tag"/>
            </div>
        </div>
        <div class="fixed bottom-0 border-t h-32 flex justify-center items-center w-full bg-white">
            <button 
                class="w-11/12 h-20 p-0 rounded-2xl bg-gradient-to-r from-button-red-1 via-button-red-2 to-button-red-3 text-white" 
                @click="submit">Show Results</button>
        </div>
    </div>
</template>

<script>
    import VueFilterPrice from '../Filter/vue-filter-price.vue'
    import VueFilterCategory from '../Filter/vue-filter-category.vue'
    import VueFilterTag from '../Filter/vue-filter-tag.vue'
    export default {

        props: ['categories', 'tags'],

        components: { VueFilterPrice, VueFilterCategory, VueFilterTag },

        computed: {
        },

        data() {
            return {
                data: {
                    category: this.initilizeCategory(),
                    price: this.initilizePrice(),
                    tag: this.initilizeTag(),
                }
            }
        },

        methods: {
            async submit() {
                this.$emit('update', this.data)
            },
            onClose() {
                this.$emit('close')
            },
            onClear() {
                this.data.category = []
                this.data.price = [0, 100]
                this.data.tag = []
            },
            initilizeCategory() {
                let cat = new URL(window.location.href).searchParams.get("category")
                return cat ? cat.split(',').map(Number) : []
            },
            initilizeTag() {
                let tag = new URL(window.location.href).searchParams.get("tag")
                return tag ? tag.split(',') : []
            },
            initilizePrice() {
                return new URL(window.location.href).searchParams.get("price0") ? [Number(new URL(window.location.href).searchParams.get("price0")), Number(new URL(window.location.href).searchParams.get("price1"))] : [0,100]
            },
        },

    }
</script>