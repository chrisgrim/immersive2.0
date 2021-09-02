<template>
    <div class="event-search">
        <template v-if="mobile">
            <div class="event-search__container">
                <vue-search-map :events="eventList.data" />
                <section class="spacer--div" />
                <section 
                    class="event__filter"
                    @click="showEvents"
                    :style="shiftDown">
                    <div class="event-search-list">
                        <div class="event__results">
                            <div class="event__results--title">
                                <template v-if="eventList.data && eventList.data.length">
                                    <p>{{ eventList.total }} immersive events.</p>
                                    <h2>{{ url.city }}</h2>
                                </template>
                                <template v-else>
                                    <p>There are no location based events in {{ url.city }} with these filters.
                                    <h2>{{ url.city }}</h2>
                                </template>
                            </div>
                            <VueList :items="eventList.data" />
                            <pagination
                                :list="eventList"
                                :limit="2"
                                @selectpage="selectPage" />
                        </div>
                        <div>
                            <div class="event__results--online">
                                <div class="title">
                                    <h3>Online events</h3>
                                </div>
                                <VueEventIndex 
                                    :col="4"
                                    :events="onlineEventList.data" />
                            </div>
                        </div>
                    </div>
                </section>
                <SearchFilter
                    @locationevents="updateEvents"
                    @onlineevents="updateOnlineEvents"
                    :events="searchedevents" 
                    :tags="tags" 
                    :categories="categories" />
            </div>
        </template>


        <template v-if="!mobile">
            <div class="event-search__container">
                <section class="event__filter">
                    <div class="event__results--title">
                        <template v-if="eventList.data && eventList.data.length">
                            <p>{{ eventList.total }} immersive events.</p>
                            <h2>{{ url.city }}</h2>
                        </template>
                        <template v-else>
                            <p>There are no location based events in {{ url.city }} with these filters.
                            <h2>{{ url.city }}</h2>
                        </template>
                    </div>
                    <SearchFilter
                        @locationevents="updateEvents"
                        @onlineevents="updateOnlineEvents"
                        :events="searchedevents" 
                        :tags="tags" 
                        :categories="categories" />
                    <div class="event-search-list">     
                        <div class="event__results">
                            <div>
                                <template v-if="eventList.data.length">
                                    <VueList :items="eventList.data" />
                                </template>
                            </div>
                            <div>
                                <pagination
                                    :list="eventList"
                                    :limit="2"
                                    @selectpage="selectPage" />
                            </div>
                            <template v-if="!mobile">
                                <div class="event-search-list">
                                    <div class="title">
                                        <h3>Online Events</h3>
                                    </div>
                                    <template v-if="onlineEventList.data">
                                        <VueEventIndex 
                                            col="three"
                                            :events="onlineEventList.data" />
                                    </template>
                                    <div>
                                        <pagination 
                                            :list="onlineEventList"
                                            :limit="2"
                                            @selectpage="selectOnlinePage" />
                                    </div>
                                </div>
                            </template>
                        </div>
                    </div>
                </section>
                <vue-search-map :events="eventList.data" />
            </div>
        </template>
    </div>
</template>

<script>
    import SearchFilter  from './vue-search-filter-location.vue'
    import VueList from './components/album-location-search.vue'
    import Pagination  from '../events/components/pagination.vue'
    import searchBasicsMixin from '../../mixins/search-basics-mixin'
    import VueEventIndex from '../events/components/index-item.vue'
    import VueSearchMap from './components/vue-map.vue'

    export default {
        components: { SearchFilter, VueList, Pagination, VueEventIndex, VueSearchMap },

        mixins: [ searchBasicsMixin ],

        props:['searchedevents','onlineevents','categories','user', 'tags', 'maxprice'],

        computed: {
            fullMap() {
                return this.$store.state.map || this.$store.state.mobilelocation || this.$store.state.mobiledates || this.$store.state.filter;
            },
        }, 

        data() {
            return {
                eventList: this.searchedevents,
                onlineEventList: this.onlineevents,
                mobile: window.innerWidth < 768,
                shiftDown: `transform: translate3d(0px, 0px, 0px);`,
            }
        },

        methods: {

            updateEvents(value) {
                this.eventList = value;
                this.pagination = value.current_page;
            },

            updateOnlineEvents(value) {
                this.onlineEventList = value;
                this.onlinePagination = value.current_page;
            },

            selectPage (page) {
                this.$store.commit('filterPagination', page)
            },

            selectOnlinePage (page) {
                this.$store.commit('filterOnlinePagination', page)
            },

            showEvents() {
                this.$store.commit('showmap', false);
            },

        },

        watch: {
            fullMap() {
                this.fullMap ? this.shiftDown = `transform: translate3d(0, calc(var(--vh, 1vh) * 100 + -450px), 0);`  : this.shiftDown = `transform: translate3d(0px, 0px, 0px);` ;
            }
        },



    };
</script>