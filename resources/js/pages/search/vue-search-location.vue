<template>
    <div class="event-search relative md:min-h-[calc(100vh-7rem)]">
        <div 
            v-if="mobile"
            class="w-full md:flex">
            <MobileSearchNav
                :map="map"
                ref="search"
                @update="updateEvents"
                v-model="paginate"
                filter="location"
                :mobile="mobile"
                :tags="tags" 
                :categories="categories" />
            <MapSearch
                :mobile="mobile"
                @update="updateMap"
                :events="events.data" />
            <template v-if="!fullMap">
                <div 
                    @click="showFullMap"
                    class="h-[36vh] w-full relative" />
                <MobileSearchResults 
                    :mobile="mobile"
                    :user="user"
                    :items="events" />
                <pagination
                    :list="events"
                    :limit="2"
                    @selectpage="selectPage" />
            </template>
            <template v-else>
                <div 
                    @click="hideFullMap"
                    class="h-32 fixed bottom-0 bg-white w-full shadow-custom-1 px-8 rounded-t-5xl z-[1002]">
                    <div class="flex justify-center items-center h-32">
                        <p v-if="events.data && events.data.length">{{ events.total }} events</p>
                        <p v-else>There are no events in {{ city }} for this search.</p>
                    </div>
                </div>
            </template>
        </div>
        <div 
            v-else
            class="w-full flex">
            <section 
                :class="{ 'w-0 hidden' : wideMap }"
                class="z-10 relative inline-block w-[59%] mt-32 min-h-[calc(100vh-8rem)]">
                <div class="inline-block text-left pt-16 pb-4 px-8">
                    <p v-if="events.data && events.data.length">{{ events.total }} immersive events.</p>
                    <p v-else>There are no location based events in {{ city }} with these filters.</p>
                    <h2>{{ city }}</h2>
                </div>
                <SearchNav
                    @update="updateEvents"
                    :map="map"
                    ref="search"
                    v-model="paginate"
                    filter="location"
                    :mobile="mobile"
                    :tags="tags" 
                    :categories="categories" />
                <div class="w-full">     
                    <div class="px-8">
                        <VueList 
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
            <MapSearch
                @update="updateMap"
                @wideMap="widenMap"
                :mobile="mobile"
                :events="events.data" />
        </div>
    </div>
</template>

<script>
    import SearchNav  from './Location/vue-location-desktop-nav.vue'
    import MobileSearchNav  from './Location/vue-location-mobile-nav.vue'
    import MobileSearchResults  from './Location/vue-mobile-location-album.vue'
    import VueList from './Location/vue-desktop-location-album.vue'
    import Pagination  from './Location/Components/pagination.vue'
    import searchBasicsMixin from '../../mixins/search-basics-mixin'
    import MapSearch from './Location/vue-location-map.vue'
    import Hero from '../Home/Sections/hero-section.vue'
    import Icons from '../Home/Sections/icons-section.vue'
    import Album from '../Home/Sections/album-section.vue'
    import Spotlight from '../Home/Sections/spotlight-section.vue'

    export default {
        components: { Hero, Icons, Album, Spotlight, SearchNav, VueList, Pagination , MapSearch, MobileSearchNav, MobileSearchResults },

        mixins: [ searchBasicsMixin ],

        props:['searchedevents','docks','categories','user', 'tags', 'mobile'],

        data() {
            return {
                events: this.searchedevents,
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
            selectPage (page) {
                this.paginate = page
                window.scrollTo(0,0);
            },
        },
    };
</script>