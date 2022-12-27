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
                    <div @click="showSearch">
                        <p class="font-medium text-2xl">Start your search</p>
                    </div>
                </div>
            </template>

            <template v-else>
                <div class="fixed w-full inset-0 z-[2003] bg-slate-100 overflow-auto">
                    <!-- nav section -->
                    <div class="px-8 pt-8 pb-4 h-20 flex justify-center w-full items-center">
                        <button 
                            class="ml-[-1rem] border-none w-20 h-20 items-center justify-center flex py-4 absolute left-8" 
                            @click="hideSearch">
                            <svg class="w-10 h-10">
                                <use :xlink:href="`/storage/website-files/icons.svg#ri-close-circle-line`" />
                            </svg>
                        </button>
                        <div class="mx-4 font-semibold border-b-2 border-black">
                            Location
                        </div>
                        <div class="mx-4 font-semibold border-black text-slate-400">
                            Listings
                        </div>
                    </div>

                    <!-- content -->
                    <Location v-model="searchData" />
                    <Dates v-model="searchData" />

                    <!-- footer -->
                    <div class="w-full bg-white h-28 fixed bottom-0">
                        <div class="flex justify-between items-center h-full px-8">
                            <button 
                                @click="clear"
                                class="border-none font-semibold text-2xl">
                                Clear All
                            </button>
                            <button 
                                :disabled="!searchData.lat"
                                @click="search"
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
    export default {

        props:[ 'filter', 'categories', 'tags'],

        components: { Location, Dates },


        data() {
            return {
                searchData: this.initializeSearchData(),
                open: false,
                scroll: false,
            }
        },

        methods: {
            search() {
                window.location.href = `/index/search?city=${this.searchData.place_name}&lat=${this.searchData.lat}&lng=${this.searchData.lng}&start=${this.searchData.searchDates.length ? this.searchData.searchDates[0] : ''}&end=${this.searchData.searchDates.length ? this.searchData.searchDates[1] : ''}`;
            },
            clear() {
                this.searchData = this.initializeSearchData()
            },
            handleScroll () {
                this.scroll = window.pageYOffset > 10
            },
            showSearch() {
                this.open = true
                document.body.classList.add('noscroll')
            },
            hideSearch() {
                this.open = false
                document.body.classList.remove('noscroll')
            },
            initializeSearchData() {
                return {
                    place_id:'',
                    lat:null,
                    lng:null,
                    place_name: '',
                    dates: [],
                    searchDates: [],
                    naturalDate: [],
                }
            }
        },
        mounted() {
            document.addEventListener('click', this.close)
            window.addEventListener('scroll', this.handleScroll);
        },

        unmounted() {
            document.removeEventListener('click',this.close)
            window.removeEventListener('scroll', this.handleScroll);
        },

}
    
</script>