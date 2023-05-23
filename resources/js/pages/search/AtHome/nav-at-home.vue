<template>
    <div class="filter">
        <div class="w-full z-20 relative p-8 md:py-8 md:px-32">
            <div class="flex gap-2 items-center w-full">
                <VueFilterDates
                    v-model="inputVal"
                    @submit="onSearch" />
                <VueFilterPrice
                    v-model="inputVal"
                    @submit="onSearch" />
                <VueFilterCategory 
                    :categories="atHomeCategories"
                    v-model="inputVal"
                    @submit="onSearch" />
                <VueFilterTag 
                    :tags="tags"
                    v-model="inputVal"
                    @submit="onSearch" />
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

        props:['value', 'filter', 'atHomeCategories', 'tags'],

        components: { VueFilterDates, VueFilterPrice, VueFilterCategory, VueFilterTag },

        mixins: [ searchBasicsMixin ],
        
        computed: {
            inputVal: {
                get() { return this.value },
                set(val) { this.$emit('input', val) }
            },
        },

        methods: {
            async onSearch() {
                this.inputVal.paginate = 1
                await axios.post(`/api/search/fetch?page=${this.value.paginate}`, this.inputVal)
                .then( res => { 
                    this.$emit('update', res.data) 
                })
                this.addPushState(this.inputVal)
            },
            async onNext() {
                await axios.post(`/api/search/fetch?page=${this.value.paginate}`, this.inputVal)
                .then( res => { 
                    this.$emit('update', res.data) 
                })
                this.addPushState(this.inputVal)
            },
        },
    }

</script>