<template>
    <div class="nav-search__options">
        <div class="header">
            <button 
                class="svg close" 
                @click="onClose">
                <svg>
                    <use :xlink:href="`/storage/website-files/icons.svg#ri-close-line`" />
                </svg>
            </button>
            <div class="title">
                Search
            </div>
            <button 
                class="svg options btn underline" 
                @click="onClear">
                Clear
            </button>
        </div>
        <div class="body">
            <div class="item price">
                <h3>Price Range</h3>
                <VueFilterPrice
                    :mobile="true"
                    v-model="data.price" />
            </div>
            <div class="item category">
                <h3>Categories</h3>
                <VueFilterCategory 
                    :mobile="true"
                    :categories="categories"
                    v-model="data.category"/>
            </div>
            <div class="item tags">
                <h3>Tags</h3>
                <VueFilterTag 
                    :mobile="true"
                    :tags="tags"
                    v-model="data.tag"/>
            </div>
        </div>
        <div class="footer">
            <button @click="submit">Show Results</button>
        </div>
    </div>
</template>

<script>
    import VueFilterPrice from '../filter/vue-filter-price.vue'
    import VueFilterCategory from '../filter/vue-filter-category.vue'
    import VueFilterTag from '../filter/vue-filter-tag.vue'
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