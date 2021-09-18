<template>
    <div 
        :class="{ mobile : mobile }"
        class="event-search">
        <template v-if="mobile">
            <div class="event-search__container">
                <MobileSearchNav
                    :map="map"
                    ref="search"
                    @update="updateEvents"
                    v-model="paginate"
                    filter="location"
                    :tags="tags" 
                    :categories="categories" />
                <MapSearch
                    :mobile="true"
                    @update="updateMap"
                    :events="events.data" />
                <template v-if="!fullMap">
                    <div 
                        @click="showFullMap"
                        class="gap" />
                    <MobileSearchResults 
                        :events="events" />
                </template>
                <template v-else>
                    <div 
                        @click="hideFullMap"
                        class="search-results hidden">
                        <div class="wrapper">
                            <template v-if="events.data && events.data.length">
                                <p>{{ events.total }} events</p>
                            </template>
                            <template v-else>
                                <p>There are no events in {{ city }} for this search.</p>
                            </template>
                        </div>
                    </div>
                </template>
            </div>
        </template>
        <template v-else>
            <div 
                :class="{ fullwidth : wideMap }"
                class="event-search__container">
                <section class="search__results">
                    <div class="search__results--title">
                        <template v-if="events.data && events.data.length">
                            <p>{{ events.total }} immersive events.</p>
                            <h2>{{ city }}</h2>
                        </template>
                        <template v-else>
                            <p>There are no location based events in {{ city }} with these filters.
                            <h2>{{ city }}</h2>
                        </template>
                    </div>
                    <SearchFilter
                        :map="map"
                        ref="search"
                        @update="updateEvents"
                        v-model="paginate"
                        filter="location"
                        :tags="tags" 
                        :categories="categories" />
                    <div class="event-search-list">     
                        <div class="event__results">
                            <template v-if="events.data.length">
                                <VueList :items="events.data" />
                            </template>
                            <pagination
                                :list="events"
                                :limit="2"
                                @selectpage="selectPage" />
                            <template v-if="!mobile">
                                <div class="event-search-list">
                                    <div class="title">
                                        <h3>Online Events</h3>
                                    </div>
                                    <template v-if="onlineEvents.data">
                                        <VueEventIndex 
                                            col="three"
                                            :events="onlineEvents.data" />
                                    </template>
                                    <div>
                                        <pagination 
                                            :list="onlineEvents"
                                            :limit="2"
                                            @selectpage="selectOnlinePage" />
                                    </div>
                                </div>
                            </template>
                        </div>
                    </div>
                </section>
                <MapSearch
                    @update="updateMap"
                    @wideMap="widenMap"
                    :mobile="false"
                    :events="events.data" />
            </div>
        </template>
    </div>
</template>

<script>
    import SearchFilter  from './vue-search-filter-location.vue'
    import MobileSearchNav  from './mobile-search-nav.vue'
    import MobileSearchResults  from './components/mobile-search-results.vue'
    import VueList from './components/album-location-search.vue'
    import Pagination  from '../events/components/pagination.vue'
    import searchBasicsMixin from '../../mixins/search-basics-mixin'
    import VueEventIndex from '../events/components/index-item.vue'
    import MapSearch from './components/vue-map.vue'

    export default {
        components: { SearchFilter, VueList, Pagination, VueEventIndex, MapSearch, MobileSearchNav, MobileSearchResults },

        mixins: [ searchBasicsMixin ],

        props:['searchedevents','onlineevents','categories','user', 'tags'],

        data() {
            return {
                events: this.searchedevents,
                onlineEvents: this.onlineevents,
                mobile: window.innerWidth < 768,
                fullMap: false,
                wideMap: false,
                onlinePaginate: 1,
                paginate: 1,
                shiftDown: `transform: translate3d(0px, 0px, 0px);`,
                city: new URL(window.location.href).searchParams.get("city"),
                map: null,
                mapSearch: null,
            }
        },

        methods: {
            updateEvents(value) {
                this.events = value;
                this.pagination = value.current_page;
            },
            updateMap(value) {
                this.map = value
                this.$nextTick(() =>  this.$refs.search.mapUpdate() )
            },
            widenMap() {
                this.wideMap =! this.wideMap
            },
            showFullMap() {
                this.fullMap = true
                document.getElementById("header").classList.add("hidden");
            },
            hideFullMap() {
                this.fullMap = false
                document.getElementById("header").classList.remove("hidden");
            },
            // async nextOnline() {
            //     await axios.post(`/api/search/online?page=${this.onlinePaginate}`)
            //     .then(res => { 
            //         this.onlineEvents = res.data;
            //     })
            // },
            selectPage (page) {
                this.paginate = page
            },
            selectOnlinePage (page) {
                this.onlinePaginate = page
            },
        },

    };
</script>