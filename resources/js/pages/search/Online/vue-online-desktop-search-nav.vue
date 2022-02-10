<template>
    <div class="filter">
        <div class="w-full z-20 relative p-8 md:py-8 md:px-32">
            <div class="flex gap-2 items-center w-full">
                <VueFilterDates
                    :mobile="mobile"
                    v-model="data.dates"
                    @submit="submit" />
                <VueFilterPrice
                    :mobile="mobile"
                    v-model="data.price"
                    @submit="submit" />
                <VueFilterCategory 
                    :mobile="mobile"
                    :categories="categories"
                    v-model="data.category"
                    @submit="submit" />
                <VueFilterTag 
                    :mobile="mobile"
                    :tags="tags"
                    v-model="data.tag"
                    @submit="submit" />
            </div>
        </div>
    </div>
</template>

<script>
    import searchBasicsMixin from '../../../mixins/search-basics-mixin'
    import VueFilterDates from '../Filter/vue-filter-dates.vue'
    import VueFilterPrice from '../Filter/vue-filter-price.vue'
    import VueFilterCategory from '../Filter/vue-filter-category.vue'
    import VueFilterTag from '../Filter/vue-filter-tag.vue'
    
    export default {

        props:['value','categories', 'tags', 'filter', 'type', 'mobile'],

        components: { VueFilterDates, VueFilterPrice, VueFilterCategory, VueFilterTag },

        mixins: [ searchBasicsMixin ],
        
        computed: {
            inputVal: {
                get() { return this.value },
                set(val) { this.$emit('input', val) }
            },
        },

        data() {
            return {
                data: {
                    category: this.initilizeCategory(),
                    dates: this.initilizeDates(),
                    price: this.initilizePrice(),
                    tag: this.initilizeTag(),
                }
            }
        },

        methods: {
            async submit() {
                this.inputVal = 1
                await axios.post(`/api/search/${this.type}?page=${this.value}`, this.data)
                .then(data => { this.$emit('update', data) })
                this.addPushState();
            },
            async next() {
                await axios.post(`/api/search/${this.type}?page=${this.value}`, this.data)
                .then( data => { this.$emit('update', data) })
                this.addPushState();
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
            initilizeDates() {
                return new URL(window.location.href).searchParams.get("start") ? [new URL(window.location.href).searchParams.get("start"), new URL(window.location.href).searchParams.get("end")] : []
            },
        },

        watch: {
            value() {
                this.next()
            }
        },
    }

</script>