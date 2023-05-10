<template>
    <div class="event-search relative min-h-[calc(100vh-7rem)]">
        <div class="w-full flex">
            <section 
                :class="{ 'w-0 hidden' : searchData.location.fullMap }"
                class="z-10 relative inline-block w-[59%] min-h-[calc(100vh-8rem)]">
                <div class="inline-block text-left pt-16 pb-4 px-8">
                    <p v-if="events.data && events.data.length">{{ events.total }} immersive events.</p>
                    <p v-else>There are no location based events in {{ searchData.location.name }} with these filters.</p>
                    <h2>{{ searchData.location.name }}</h2>
                </div>
                <Nav
                    @update="updateEvents"
                    @clear="clear"
                    ref="childNav"
                    v-model="searchData"
                    filter="location"
                    :tags="tags" 
                    :searched-categories="searchedCategories"
                    :in-person-categories="inPersonCategories"
                    :categories="categories" />
                <div class="w-full">     
                    <div class="px-8">
                        <EventList 
                            v-if="events.data.length"
                            :user="user"
                            :items="events.data" />
                        <pagination
                            :list="events"
                            :limit="2"
                            @selectpage="selectPage" />
                        <div class="w-full">
                            <template v-if="docks && docks.length">
                                <div 
                                    :key="dock.id"
                                    v-for="dock in docks">
                                    <template v-if="dock.type === 'h'">
                                        <Hero :dock="dock" />
                                    </template>
                                    <template v-if="dock.type === 'i'">
                                        <Icons :dock="dock" />
                                    </template>
                                    <template v-if="dock.type === 'f'">
                                        <Album 
                                            number="four"
                                            :dock="dock" />
                                    </template>
                                    <template v-if="dock.type === 't'">
                                        <Album 
                                            number="three"
                                            :dock="dock" />
                                    </template>
                                    <template v-if="dock.type === 's'">
                                        <Spotlight :dock="dock" />
                                    </template>
                                </div>
                            </template>
                        </div>
                    </div>
                </div>
            </section>
            <Map
                @submit="onSubmit"
                @fullMap="fullMap"
                v-model="searchData"
                :key="mapKey"
                :events="events.data" />
        </div>
    </div>
</template>

<script>
    import Nav  from './InPerson/nav-in-person.vue'
    import EventList from './InPerson/album-in-person.vue'
    import Pagination  from './InPerson/Components/pagination.vue'
    import searchBasicsMixin from '../../mixins/search-basics-mixin'
    import Map from './InPerson/map-in-person.vue'
    import Hero from '../Home/Sections/hero-section.vue'
    import Icons from '../Home/Sections/icons-section.vue'
    import Album from '../Home/Sections/album-section.vue'
    import Spotlight from '../Home/Sections/spotlight-section.vue'

    export default {
        components: { Hero, Icons, Album, Spotlight, Nav, EventList, Pagination , Map },

        mixins: [ searchBasicsMixin ],

        props:['searchedEvents','docks','categories','user', 'tags', 'mobile', 'searchedCategories', 'searchedTags', 'inPersonCategories'],

        data() {
            return {
                events: this.searchedEvents,
                mapKey:0,
                searchData: this.initializeSearchData(),
            }
        },

        methods: {
            onSubmit() {
                this.$refs.childNav.onSearch();
            },
            updateEvents(value) {
                this.events = value;
                this.searchData.pagination = value.current_page;
            },
            fullMap() {
                this.mapKey += 1;
            },
            selectPage (page) {
                this.searchData.paginate = page
                this.$refs.childNav.onNext();
                window.scrollTo(0,0);
            },
            clear() {
                this.searchData = this.initializeSearchData()
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
                    dates: this.initializeDates(),
                    naturalDate: this.initializeNaturalDates(),
                    tag: this.searchedTags ? this.searchedTags : [],
                    category: this.searchedCategories ? this.searchedCategories : [],
                    price:this.initializePrice(),
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
                return new URL(window.location.href).searchParams.get("zoom") ? Number(new URL(window.location.href).searchParams.get("zoom")) : 11
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