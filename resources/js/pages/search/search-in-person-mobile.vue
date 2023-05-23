<template>
    <div class="event-search relative">
        <div class="w-full md:flex">
            <Nav
                ref="childNav"
                @search="onSearch"
                @clear="clear"
                v-model="searchData"
                :tags="tags" 
                :searched-categories="searchedCategories"
                :in-person-categories="inPersonCategories"
                :categories="categories" />
            <Map
                :key="mapKey"
                @submit="onSubmit"
                v-model="searchData"
                :events="events.data" />
            <div 
                :style="searchData.location.fullMap ? 'z-index: -1;' : 'z-index: 1000;'"
                @click="showFullMap"
                class="h-[40vh] w-full relative" />
            <Events 
                :style="searchData.location.fullMap ? 'transform:translate3d(0, 35vh, 0)' : 'transform:translate3d(0, 0px, 0)'"
                @toggleMap="hideFullMap"
                :user="user"
                :items="events" />
            <pagination
                :list="events"
                :limit="2"
                @selectpage="selectPage" />
        </div>
    </div>
</template>

<script>
    import Nav  from './InPerson/nav-in-person-mobile.vue'
    import Events  from './InPerson/album-in-person-mobile.vue'
    import Pagination  from './InPerson/Components/pagination.vue'
    import searchBasicsMixin from '../../mixins/search-basics-mixin'
    import Map from './InPerson/map-in-person-mobile.vue'

    export default {
        components: { Pagination , Map, Nav, Events },

        mixins: [ searchBasicsMixin ],

        props:['searchedEvents','docks','categories','user', 'tags', 'inPersonCategories', 'searchedCategories'],

        data() {
            return {
                events: this.searchedEvents,
                wideMap: false,
                shiftDown: `transform: translate3d(0px, 0px, 0px);`,
                mapKey: 0,
                searchData: this.initializeSearchData(),
            }
        },

        methods: {
            async onSearch() {
                this.searchData.paginate = 1
                this.searchData.loading = true
                await axios.post(`/api/search/fetch?page=${this.searchData.paginate}`, this.searchData)
                .then( res => { 
                    this.updateEvents(res.data)
                    this.timeout = setTimeout(() => {  this.searchData.loading = false }, 600)
                })
                window.scrollTo(0, 0);
                this.addPushState(this.searchData)
            },
            async onNext() {
                await axios.post(`/api/search/fetch?page=${this.searchData.paginate}`, this.searchData)
                .then( res => { 
                    this.updateEvents(res.data)
                })
                window.scrollTo(0, 0);
                this.addPushState(this.searchData)
            },
            //bandaid fix for the error where user can click through the nav and hit the map beneath which fires a search
            onSubmit() {
                if (!this.$refs.childNav.open) { this.onSearch() }
            },
            updateEvents(value) {
                this.events = value;
                this.searchData.pagination = value.current_page;
                if (!this.searchData.location.live) {this.mapKey += 1;}
            },
            showFullMap() {
                this.searchData.location.fullMap = true
                document.getElementById("header").classList.add("hidden")
                document.body.classList.add('noscroll')
                this.mapKey += 1;
            },
            hideFullMap() {
                this.searchData.location.fullMap = false
                document.getElementById("header").classList.remove("hidden")
                document.body.classList.remove('noscroll')
                this.mapKey += 1;
            },
            selectPage (page) {
                this.searchData.paginate = page
                this.onNext();
                window.scrollTo(0,0);
            },
            clear() {
                console.log('test');
                this.searchData = this.clearSearchData()
            },
            initializeSearchData() {
                return {
                    paginate: 1,
                    currentTab: 'location',
                    searchType:'inPerson',
                    loading: false,
                    location:{
                        name: new URL(window.location.href).searchParams.get("city") ? new URL(window.location.href).searchParams.get("city") : 'Search by City',
                        zoom: this.initializeZoom(),
                        center: this.initializeCenter(),
                        mapboundary: this.initializeBoundaries(),
                        fullMap:false,
                        live: new URL(window.location.href).searchParams.get("live") ? JSON.parse(new URL(window.location.href).searchParams.get("live")) : false
                    },
                    searchDates: this.initializeDates(),
                    naturalDate: this.initializeNaturalDates(),
                    tag: this.searchedTag ? this.searchedTag : [],
                    category: this.searchedCategories ? this.searchedCategories : [],
                    price:this.initializePrice(),
                }
            },
            clearSearchData() {
                return {
                    paginate: 1,
                    currentTab: 'location',
                    searchType:'inPerson',
                    loading: false,
                    location:{
                        name: new URL(window.location.href).searchParams.get("city") ? new URL(window.location.href).searchParams.get("city") : 'Search by City',
                        zoom: this.initializeZoom(),
                        center: this.initializeCenter(),
                        mapboundary: this.initializeBoundaries(),
                        fullMap:false,
                        live: new URL(window.location.href).searchParams.get("live") ? JSON.parse(new URL(window.location.href).searchParams.get("live")) : false
                    },
                    searchDates: [],
                    naturalDate: [],
                    tag: [],
                    category: [],
                    price: [0,100],
                }
            },
            initializePrice() {
                return new URL(window.location.href).searchParams.get("price0") ? [Number(new URL(window.location.href).searchParams.get("price0")), Number(new URL(window.location.href).searchParams.get("price1"))] : [0,100]
            },
            initializeDates() {
                return new URL(window.location.href).searchParams.get("start") ? [new URL(window.location.href).searchParams.get("start"), new URL(window.location.href).searchParams.get("end")] : []
            },
            initializeNaturalDates() {
                if (new URL(window.location.href).searchParams.get("start")) {
                    return [this.$dayjs(new URL(window.location.href).searchParams.get("start")).format("MMM D"), this.$dayjs(new URL(window.location.href).searchParams.get("end")).format("MMM D")] 
                }
                return []
            },
            initializeZoom() {
                return new URL(window.location.href).searchParams.get("zoom") ? Number(new URL(window.location.href).searchParams.get("zoom")) : 10
            },
            initializeCenter() {
                if (new URL(window.location.href).searchParams.get("Clat")) {
                    return {
                        lat: parseFloat(new URL(window.location.href).searchParams.get("Clat")),
                        lng: parseFloat(new URL(window.location.href).searchParams.get("Clng"))
                    }
                }
                return {
                    lat: parseFloat(new URL(window.location.href).searchParams.get("lat")),
                    lng: parseFloat(new URL(window.location.href).searchParams.get("lng"))
                }
            },
            initializeBoundaries() {
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
    };
</script>