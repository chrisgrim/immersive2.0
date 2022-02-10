<template>
    <div>
        <div
            :class="[ open ? 'open' : null, scroll ? 'scroll' : null ]"
            class="search-nav fixed w-full bg-transparent h-[7.5rem] m-auto z-[2002] top-0">
            <template v-if="!open">
                <div 
                    :class="[ scroll ? 'my-6 w-full px-4' : 'shadow-custom-1 my-8 w-11/12' ]"
                    class="bg-white flex justify-between items-center rounded-full min-h-[4.5rem] mx-auto">
                    <button 
                        @click="onBack"
                        class="w-16 h-16 mr-4 flex items-center justify-center border-none">
                        <svg class="border-none flex justify-center w-8 h-8">
                            <use :xlink:href="`/storage/website-files/icons.svg#ri-arrow-left-s-line`" />
                        </svg>
                    </button>
                    <div 
                        @click="onOpen"
                        class="location">
                        <p class="text-xl font-bold whitespace-nowrap overflow-hidden text-ellipsis">
                            {{ city }}
                        </p>
                    </div>
                    <div @click="onOpen">
                        <p class="text-xl whitespace-nowrap overflow-hidden text-ellipsis">
                            {{ naturalDate ? naturalDate : 'Add dates' }}
                        </p>
                    </div>
                    <button
                        :class="{ 'fill-default-red' : hasOptions }"
                        @click="showOptions"
                        class="border-y-0 border-r-0 rounded-none border-l flex py-4 justify-center w-16">
                        <svg class="w-8 h-8">
                            <use :xlink:href="`/storage/website-files/icons.svg#ri-settings-line`" />
                        </svg>
                    </button>
                </div>
            </template>
            <template v-else>
                <div class="flex justify-between w-full h-24 items-center text-center p-8">
                    <button 
                        @click="close"
                        class="ml-[-1rem] w-16 h-16 flex items-center justify-center border-none">
                        <svg class="border-none flex justify-center w-8 h-8">
                            <use :xlink:href="`/storage/website-files/icons.svg#ri-close-line`" />
                        </svg>
                    </button>
                    <div class="title">Search</div>
                    <button 
                        :class="{active : hasOptions}"
                        @click="showOptions"
                        class="mr-[-1rem] border-y-0 border-r-0 rounded-none border-l fill-default-red flex py-4 justify-center w-16">
                        <svg class="w-8 h-8">
                            <use :xlink:href="`/storage/website-files/icons.svg#ri-settings-line`" />
                        </svg>
                    </button>
                </div>
                <div class="rounded-2xl mx-8 shadow-custom-1 border">
                    <SearchBar :icon="true" />
                    <VueFilterDates
                        type="location-mobile"
                        :mobile="mobile"
                        v-model="data.dates"
                        @submit="submit" />
                </div>
            </template>
        </div>
        <SearchOptions
            v-if="options"
            :categories="categories"
            :tags="tags"
            @update="updateOptions"
            @close="hideOptions" />
    </div>
</template>

<script>
    import SearchBar from './Components/location-search.vue'
    import searchBasicsMixin from '../../../mixins/search-basics-mixin'
    import SearchOptions from './vue-mobile-search-options.vue'
    import VueFilterDates from '../Filter/vue-filter-dates.vue'
    
    export default {

        props:['value', 'filter', 'categories', 'tags', 'map', 'mapSearch', 'mobile'],

        components: { SearchBar, VueFilterDates, SearchOptions },

        mixins: [ searchBasicsMixin ],

        computed: {
            inputVal: {
                get() { return this.value },
                set(val) { this.$emit('input', val) }
            },
            naturalDate() {
                if (this.data.dates && this.data.dates.length) {
                    return `${ this.$dayjs(this.data.dates[0]).format("MMM D") } - ${ this.$dayjs(this.data.dates[1]).format("MMM D") }`
                }
                return null
            },
            hasOptions() {
                if (this.data.category.length) { return true }
                if (this.data.tag.length) { return true }
                if (this.data.price[0] !== 0 ||this.data.price[1] !== 100) { return true }
                return false
            }
        },

        data() {
            return {
                open: false,
                options: false,
                scroll: false,
                city: new URL(window.location.href).searchParams.get("city") ? new URL(window.location.href).searchParams.get("city") : 'Start your search',
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
                this.open = false
                this.addPushState()
            },
            async next() {
                await axios.post(`/api/search/mapboundary?page=${this.value}`, this.data)
                .then( res => { this.$emit('update', res.data) })
                this.open = false
                this.addPushState()
            },
            onBack() {
                document.referrer == "" ? window.location.href = '/' : window.history.back()
            },
            handleScroll () {
                this.scroll = window.pageYOffset > 10
                if (this.open) { this.open = false }
            },
            mapUpdate() {
                this.data.mapboundary = this.map.currentBounds;
                this.data.center = this.map.currentCenter;
                this.data.zoom = this.map.zoom;
                this.data.live = this.map.live;
                this.debounce()
            },
            debounce() {
                if (this.timeout) 
                    clearTimeout(this.timeout); 
                this.timeout = setTimeout(() => {
                    this.submit()
                }, 500);
            },
            onOpen() {
                this.open = true
            },
            close() {
                this.open = false
            },
            showOptions() {
                this.options = true
                this.open = false
                document.body.classList.add('noscroll')
            },
            hideOptions() {
                this.options = false
                document.body.classList.remove('noscroll')
            },
            updateOptions(val) {
                this.data.category = val.category
                this.data.price = val.price
                this.data.tag = val.tag
                this.hideOptions()
                this.debounce()
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
            mapSearch() {
                if (!this.map.live) { return }
                this.mapUpdate();
            },
            value() {
                this.next()
            }
        },

        created () {
            window.addEventListener('scroll', this.handleScroll);
        },
        destroyed () {
            window.removeEventListener('scroll', this.handleScroll);
        }


}
    
</script>