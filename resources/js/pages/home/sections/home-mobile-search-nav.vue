<template>
    <div>
        <div
            :class="[ open ? 'open' : null, scroll ? 'scroll' : null ]"
            class="search-nav fixed w-full bg-transparent h-[7rem] m-auto z-[2002] top-0 mt-2">
            <template v-if="!open">
                <div 
                    :class="[ scroll ? 'my-4' : 'shadow-custom-1 my-8' ]"
                    class="rounded-full w-11/12 h-[5.5rem] mx-auto g-white flex justify-center items-center transition-all">
                    <div class="flex mr-4 fill-default-red">
                        <svg class="w-8 h-8">
                            <use :xlink:href="`/storage/website-files/icons.svg#ri-search-line`" />
                        </svg>
                    </div>
                    <div @click="toggleSearch">
                        <p class="font-medium text-2xl">Start your search</p>
                    </div>
                </div>
            </template>

            <template v-else>
                <div class="fixed w-full inset-0 z-[2003] bg-slate-100">
                    <!-- nav section -->
                    <div class="px-8 my-8 h-20 flex justify-center w-full items-center">
                        <button 
                            class="ml-[-1rem] border-none w-20 h-20 items-center justify-center flex py-4 absolute left-8" 
                            @click="toggleSearch">
                            <svg class="w-10 h-10">
                                <use :xlink:href="`/storage/website-files/icons.svg#ri-close-circle-line`" />
                            </svg>
                        </button>
                        <div 
                            @click="showPage('location')"
                            :class="{ 'border-b-2 text-black' : page ==='location' }"
                            class="mx-4 font-semibold border-black text-slate-400">
                            Location
                        </div>
                        <div 
                            @click="showPage('listing')"
                            :class="{ 'border-b-2 text-black' : page ==='listing' }"
                            class="mx-4 font-semibold border-black text-slate-400">
                            Listings
                        </div>
                    </div>

                    <!-- content -->
                    <div class="overflow-auto h-full">
                        <template v-if="page==='location'">
                            <Location v-model="searchData" />
                            <Dates v-model="searchData" />
                        </template>
                        <template v-if="page==='listing'">
                            <Genre v-model="searchData" />
                            <Price v-model="searchData" />
                            <Dates v-model="searchData" />
                            <NameSearch v-model="searchData" />
                        </template>
                    </div>

                    <!-- footer -->
                    <div class="w-full bg-white h-28 fixed bottom-0">
                        <div class="flex justify-between items-center h-full px-8">
                            <button 
                                @click="clear"
                                class="border-none font-semibold text-2xl">
                                Clear All
                            </button>
                            <button 
                                v-if="this.page==='location'"
                                :disabled="!searchData.location.name"
                                @click="search('location')"
                                class="bg-default-red text-white font-semibold text-2xl px-8 py-4 border-none cursor-not-allowed disabled:opacity-50">
                                Search
                            </button>
                            <button 
                                v-if="this.page==='listing'"
                                :disabled="!canSearchListing"
                                @click="search('listing')"
                                class="bg-default-red text-white font-semibold text-2xl px-8 py-4 border-none cursor-not-allowed disabled:opacity-50">
                                Search
                            </button>
                        </div>
                    </div>
                </div>
            </template>
        </div>
        <div id="places" />
    </div>
</template>

<script>
    import Location from './Components/mobile-location.vue'
    import Dates from './Components/mobile-dates.vue'
    import Genre from './Components/mobile-genre.vue'
    import Price from './Components/mobile-price.vue'
    import NameSearch from './Components/mobile-name-search.vue'
    export default {

        props:[ 'filter', 'categories', 'tags'],

        components: { Location, Dates, NameSearch, Genre, Price },

        computed: {
            canSearchListing() {
                return this.searchData.searchDates.length || this.searchData.genre.id || this.hasPrice 
            },
            hasPrice() {
                return this.searchData.price[0] === 0 && this.searchData.price[1] === 100 ? false : true;
            },
        },

        data() {
            return {
                searchData: this.initializeSearchData(),
                open: false,
                scroll: false,
                page:'listing',
            }
        },

        methods: {
            searchLocation() {
                window.location.href = `/index/search?city=${this.searchData.location.name}&lat=${this.searchData.location.geometry.location.lat()}&lng=${this.searchData.location.geometry.location.lng()}&start=${this.searchData.searchDates.length ? this.searchData.searchDates[0] : ''}&end=${this.searchData.searchDates.length ? this.searchData.searchDates[1] : ''}`;
            },
            search(type) {
                let content = '';
                if (this.searchData.location.name) {
                    content = content.concat(`&city=${this.searchData.location.name}&lat=${this.searchData.location.geometry.location.lat()}&lng=${this.searchData.location.geometry.location.lng()}`)
                }
                if (this.searchData.genre && this.searchData.genre.type === 't') {
                    content = content.concat(`&tag=${this.searchData.genre.slug}`)
                }
                if (this.hasPrice) {
                    content = content.concat(`&price0=${this.searchData.price[0]}&price1=${this.searchData.price[1]}`)
                }
                if (this.searchData.genre && this.searchData.genre.type === 'c') {
                    content = content.concat(`&category=${this.searchData.genre.id}`)
                }
                if (this.searchData.searchDates.length) {
                    content = content.concat(`&start=${this.searchData.searchDates[0]}&end=${this.searchData.searchDates[1]}`)
                }
                if (type === 'listing') {
                    window.location.href = `/index/search-online?${content}`
                } else {
                    window.location.href = `/index/search?${content}`
                }
            },
            showPage(type) {
                this.page=type
                this.clear()
            },
            clear() {
                this.searchData = this.initializeSearchData()
            },
            handleScroll () {
                this.scroll = window.pageYOffset > 10
            },
            toggleSearch() {
                this.open=!this.open
                this.open ? document.body.classList.add('noscroll') : document.body.classList.remove('noscroll')
            },
            initializeSearchData() {
                return {
                    location:{},
                    searchDates: [],
                    naturalDate: [],
                    genre: {},
                    price:[0,100],
                }
            }
        },
        mounted() {
            window.addEventListener('scroll', this.handleScroll);
        },

        unmounted() {
            window.removeEventListener('scroll', this.handleScroll);
        },

}
    
</script>