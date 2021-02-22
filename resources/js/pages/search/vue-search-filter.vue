<template>
    <div class="filter">
        <div class="content">
            <div class="filter__block">
                <VueFilterDates
                    :mobile="isMobile()"
                    :url="url"
                    @submit="pushData" />
                <VueFilterPrice
                    :mobile="isMobile()"
                    @submit="pushData" />
                <VueFilterCategory 
                    :mobile="isMobile()"
                    :categories="categories"
                    @submit="pushData" />
                <VueFilterTag 
                    :mobile="isMobile()"
                    :tags="tags"
                    @submit="pushData" />
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

        props:['categories', 'events', 'onlineevents', 'tags'],

        components: { VueFilterDates, VueFilterPrice, VueFilterCategory, VueFilterTag },

        mixins: [ searchBasicsMixin, mobile ],

        data() {
            return {
                searchType: 'location',
                filter: 'online',
                data: {
                    category: [],
                    dates: [],
                    price: [],
                    tag: [],
                }
            }
        },

        methods: {

            submit() {
                axios.post(`/api/search/online?page=${this.$store.state.pagination}`, this.data)
                .then(data => {
                    this.$emit('onlineevents', data);
                    this.addPushState();
                })
                .catch(err => {this.onErrors(err);});
            },

            pushData() {
                this.addData();
                this.submit();
            },

            addData() {
                this.$store.commit('filterPagination', 1)
                this.data.category = this.$store.state.filterCategory.map(cat => cat.id)
                this.data.dates = this.$store.state.filterDates;
                this.data.price = this.$store.state.filterPrice;
                this.data.tag = this.$store.state.filterTag.map(tag => tag.name);
            },
        },

        watch: {
            '$store.state.pagination'() {
                this.submit()
            }
        },

        created() {
            this.getPushState();
            this.addData();
        },


}
    
</script>