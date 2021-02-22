<template>
    <div class="filter">
        <div class="content location">
            <div class="filter__block">
                <vue-nav-search :searchtype="searchType" />
                <VueFilterDates
                    :mobile="isMobile()"
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
    import vueNavSearch from '../layouts/nav-search.vue'
    import searchBasicsMixin from '../../mixins/search-basics-mixin'
    import mobile from '../../mixins/mobile'
    import VueFilterDates from './filter/vue-filter-dates.vue'
    import VueFilterPrice from './filter/vue-filter-price.vue'
    import VueFilterCategory from './filter/vue-filter-category.vue'
    import VueFilterTag from './filter/vue-filter-tag.vue'
    
    export default {

        props:['categories', 'events', 'onlineevents', 'tags'],

        components: { vueNavSearch, VueFilterDates, VueFilterPrice, VueFilterCategory, VueFilterTag },

        mixins: [ searchBasicsMixin, mobile ],

        computed: {
            data() {
                return {
                    mapboundary: this.boundaries,
                    category: [],
                    dates: [],
                    price: [],
                    tag: [],
                    lat: this.boundaries ? '' : new URL(window.location.href).searchParams.get("lat"),
                    lng: this.boundaries ? '' : new URL(window.location.href).searchParams.get("lng"),
                    hasLocation: this.url.lat ? true : false
                }
            },
            mapInfo() {
                return this.$store.state.bounds;
            },
            hasLocation() {
                return this.url.lat || this.url.mapSearch;
            },
        },

        data() {
            return {
                active:'',
                searchType: 'location',
                mobile: window.innerWidth < 768,
                boundaries: '',
                center: '',
                zoom:'',
            }
        },

        methods: {

            submit() {
                this.reset();
                axios.all([
                    axios.post(`/api/search/mapboundary?page=${this.$store.state.pagination}`, this.data),
                    axios.post(`/api/search/online?page=${this.$store.state.onlinePagination}`, this.data)
                ])
                .then(axios.spread((data1, data2) => {
                    this.$emit('locationevents', data1.data);
                    this.$emit('onlineevents', data2.data);
                    this.addPushState();
                }));
            },

            pushData() {
                this.addData();
                this.submit();
            },

            reset() {
                this.$store.commit('onfilter', false);
                this.$store.commit('showmap', false);
                this.active = null;
            },

            addData() {
                this.$store.commit('filterPagination', 1)
                this.$store.commit('filterOnlinePagination', 1)
                this.data.category = this.$store.state.filterCategory.map(cat => cat.id)
                this.data.dates = this.$store.state.filterDates;
                this.data.price = this.$store.state.filterPrice;
                this.data.tag = this.$store.state.filterTag.map(tag => tag.name);
            },

            conditionalBodyClass(bool, className) {
                if (bool) {
                    document.body.classList.add(className)
                    this.$store.commit('showmap', true);
                } else {
                    document.body.classList.remove(className)
                    this.$store.commit('showmap', false);
                }
            },
        },

        watch: {
            active() {
                return this.active == 'mobile' ? this.conditionalBodyClass(true, 'noscroll') : this.conditionalBodyClass(false, 'noscroll')
            },
            mapInfo() {
                this.boundaries = this.mapInfo.bounds;
                this.center = this.mapInfo.center;
                this.zoom = this.mapInfo.zoom;
                return this.submit();
            },

            '$store.state.pagination'() {
                this.submit()
            },

            '$store.state.onlinePagination'() {
                this.submit()
            },

            '$store.state.filter': function(n) {
                console.log(n);
                return n ? this.active='mobile' : this.active='';
              }

        },

        created() {
            this.getPushState();
            this.addData();
        },


}
    
</script>