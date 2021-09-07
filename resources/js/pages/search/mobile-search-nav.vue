<template>
    <div>
        <div
            :class="{open : open, scroll: scroll}"
            class="search-nav">
            <template v-if="!open">
                <div class="placeholder">
                    <button 
                        @click="onBack"
                        class="arrow svg">
                        <svg>
                            <use :xlink:href="`/storage/website-files/icons.svg#ri-arrow-left-s-line`" />
                        </svg>
                    </button>
                    <div 
                        @click="open=true"
                        class="location">
                        <p>{{ city }}</p>
                    </div>
                    <div 
                        @click="open=true"
                        class="dates">
                        <template v-if="naturalDate">
                            <p>{{ naturalDate }}</p>
                        </template>
                        <template v-else>
                            <p>Add dates</p>
                        </template>
                    </div>
                    <button 
                        @click="options=true"
                        class="options svg">
                        <svg>
                            <use :xlink:href="`/storage/website-files/icons.svg#ri-settings-line`" />
                        </svg>
                    </button>
                </div>
            </template>
            <template v-else>
                <div class="actual">
                    <button 
                        class="svg close" 
                        @click="open=false">
                        <svg>
                            <use :xlink:href="`/storage/website-files/icons.svg#ri-close-line`" />
                        </svg>
                    </button>
                    <div class="title">
                        Search
                    </div>
                    <button 
                        class="svg options" 
                        @click="options=true">
                        <svg>
                            <use :xlink:href="`/storage/website-files/icons.svg#ri-settings-line`" />
                        </svg>
                    </button>
                </div>
                <div class="wrapper">
                    <SearchBar />
                    <VueFilterDates
                        :mobile="isMobile()"
                        v-model="data.dates"
                        @submit="submit" />
                </div>
            </template>
        </div>
        <SearchOptions
            v-if="options"
            @close="options=false" />
    </div>
</template>

<script>
    import SearchBar from '../../components/search-bars/search-location.vue'
    import searchBasicsMixin from '../../mixins/search-basics-mixin'
    import mobile from '../../mixins/mobile'
    import SearchOptions from './components/mobile-options.vue'
    import VueFilterDates from './filter/vue-filter-dates.vue'
    import VueFilterPrice from './filter/vue-filter-price.vue'
    import VueFilterCategory from './filter/vue-filter-category.vue'
    import VueFilterTag from './filter/vue-filter-tag.vue'

    
    export default {

        props:['value', 'filter', 'categories', 'tags', 'map'],

        components: { SearchBar, VueFilterDates, VueFilterPrice, VueFilterCategory, VueFilterTag, SearchOptions },

        mixins: [ searchBasicsMixin, mobile ],

        computed: {
            inputVal: {
                get() { return this.value },
                set(val) { this.$emit('input', val) }
            },
            boundary() {
                return this.map && this.map.currentBounds._northEast.lat ? this.map.currentBounds._northEast.lat : null;
            },
            naturalDate() {
                if (this.data.dates && this.data.dates.length) {
                    return `${ this.$dayjs(this.data.dates[0]).format("MMM D") } - ${ this.$dayjs(this.data.dates[1]).format("MMM D") }`
                }
                return null
            }
        },

        data() {
            return {
                active:'',
                open: false,
                options: false,
                scroll: false,
                mobile: window.innerWidth < 768,
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
                }
            }
        },

        methods: {
            async submit() {
                this.inputVal = 1
                await axios.post(`/api/search/mapboundary?page=${this.value}`, this.data)
                .then( res => { this.$emit('update', res.data) })
                this.open = false
                this.addPushState()
            },
            onBack() {

            },
            handleScroll (event) {
                console.log(window.pageYOffset);
                this.scroll = window.pageYOffset > 10 ? true : false
            },
            mapUpdate() {
                this.data.mapboundary = this.map.currentBounds;
                this.data.zoom = this.map.zoom;
                this.data.center = this.map.center;
                this.data.lat = null;
                this.data.lng = null;
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
            boundary() {
                if (!this.boundary) { return }
                this.mapUpdate();
                return this.submit();
            },
        },

        created () {
            window.addEventListener('scroll', this.handleScroll);
        },
        destroyed () {
            window.removeEventListener('scroll', this.handleScroll);
        }


}
    
</script>