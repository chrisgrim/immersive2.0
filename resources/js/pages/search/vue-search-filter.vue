<template>
    <div class="filter">
        <div class="content">
            <div class="filter__block">
                <VueFilterDates
                    :mobile="isMobile()"
                    v-model="data.dates"
                    @submit="submit" />
                <VueFilterPrice
                    :mobile="isMobile()"
                    v-model="data.price"
                    @submit="submit" />
                <VueFilterCategory 
                    :mobile="isMobile()"
                    :categories="categories"
                    v-model="data.category"
                    @submit="submit" />
                <VueFilterTag 
                    :mobile="isMobile()"
                    :tags="tags"
                    v-model="data.tag"
                    @submit="submit" />
            </div>
        </div>
    </div>
</template>

<script>
    import searchBasicsMixin from '../../mixins/search-basics-mixin'
    import mobile from '../../mixins/mobile'
    import VueFilterDates from './filter/vue-filter-dates.vue'
    import VueFilterPrice from './filter/vue-filter-price.vue'
    import VueFilterCategory from './filter/vue-filter-category.vue'
    import VueFilterTag from './filter/vue-filter-tag.vue'
    
    export default {

        props:['value','categories', 'tags', 'filter'],

        components: { VueFilterDates, VueFilterPrice, VueFilterCategory, VueFilterTag },

        mixins: [ searchBasicsMixin, mobile ],
        
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
                await axios.post(`/api/search/all?page=${this.value}`, this.data)
                .then(data => { this.$emit('update', data) })
                this.addPushState();
            },
            async next() {
                await axios.post(`/api/search/all?page=${this.value}`, this.data)
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