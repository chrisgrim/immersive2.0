<template>
    <div class="filter">
        <div class="w-full z-30 relative p-8 md:py-8 md:px-8">
            <div class="flex gap-2 items-center w-full">
                <VueFilterDates
                    v-model="data.dates"
                    @submit="submit" />
                <VueFilterPrice
                    v-model="data.price"
                    @submit="submit" />
                <VueFilterCategory 
                    :categories="categories"
                    v-model="data.category"
                    @submit="submit" />
                <VueFilterTag 
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

        props:['value', 'filter', 'categories', 'tags', 'map'],

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
                active:'',
                city: new URL(window.location.href).searchParams.get("city"),
                data: {
                    mapboundary: this.initilizeBoundaries(),
                    category: this.initilizeCategory(),
                    dates: this.initilizeDates(),
                    price: this.initilizePrice(),
                    tag: this.initilizeTag(),
                    lat: this.initilizeLatitude(),
                    lng: this.initilizeLongitude(),
                    zoom: this.initilizeZoom(),
                    center: this.initilizeCenter(),
                    live: new URL(window.location.href).searchParams.get("live")
                }
            }
        },

        methods: {
            async submit() {
                this.inputVal = 1
                await axios.post(`/api/search/mapboundary?page=${this.value}`, this.data)
                .then( res => { this.$emit('update', res.data) })
                this.addPushState();
            },
            async next() {
                await axios.post(`/api/search/mapboundary?page=${this.value}`, this.data)
                .then( res => { this.$emit('update', res.data) })
                this.addPushState();
            },
            mapUpdate() {
                this.data.mapboundary = this.map.currentBounds;
                this.data.zoom = this.map.zoom
                this.data.center = this.map.currentCenter
                this.data.live = this.map.live
                return this.debounce();
            },
            debounce() {
                if (this.timeout) 
                    clearTimeout(this.timeout); 
                this.timeout = setTimeout(() => {
                    this.submit()
                }, 500);
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
            initilizeLatitude() {
                return new URL(window.location.href).searchParams.get("lat") ? new URL(window.location.href).searchParams.get("lat") : null
            },
            initilizeLongitude() {
                return new URL(window.location.href).searchParams.get("lng") ? new URL(window.location.href).searchParams.get("lng") : null
            },
            initilizeZoom() {
                return new URL(window.location.href).searchParams.get("zoom") ? new URL(window.location.href).searchParams.get("zoom") : null
            },
            initilizeCenter() {
                if (!new URL(window.location.href).searchParams.get("Clat")) { return null }
                return {
                    lat: parseFloat(new URL(window.location.href).searchParams.get("Clat")),
                    lng: parseFloat(new URL(window.location.href).searchParams.get("Clng"))
                }
            },
            initilizeBoundaries() {
                if (!new URL(window.location.href).searchParams.get("NElat")) { return null }
                return {
                    _northEast: {
                        lat: parseFloat(new URL(window.location.href).searchParams.get("NElat")),
                        lng: parseFloat(new URL(window.location.href).searchParams.get("NElng"))
                    },
                    _southWest: {
                        lat: parseFloat(new URL(window.location.href).searchParams.get("SWlat")),
                        lng: parseFloat(new URL(window.location.href).searchParams.get("SWlng"))
                    }
                }
            },
        },

        watch: {
            value() {
                this.next()
            }
        },
    }
    
</script>