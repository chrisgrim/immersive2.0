<template>
    <div>
        <div class="search-nav fixed w-full bg-transparent h-[7rem] m-auto z-[2002] top-0">
            <template v-if="!open">
                <div 
                    @click="toggleSearch"
                    :class="[ scroll ? 'my-4' : 'shadow-custom-1 my-8' ]"
                    class="rounded-full w-11/12 h-[5.5rem] mx-auto g-white flex justify-center items-center transition-all">
                    <div class="flex mr-4 fill-default-red">
                        <svg class="w-8 h-8">
                            <use :xlink:href="`/storage/website-files/icons.svg#ri-search-line`" />
                        </svg>
                    </div>
                    <div>
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
                            @click="showPage('inPerson')"
                            :class="{ 'border-b-2 text-black' : searchData.searchType ==='inPerson' }"
                            class="mx-4 font-semibold border-black text-slate-400">
                            In Person
                        </div>
                        <div 
                            @click="showPage('atHome')"
                            :class="{ 'border-b-2 text-black' : searchData.searchType ==='atHome' }"
                            class="mx-4 font-semibold border-black text-slate-400">
                            At Home
                        </div>
                    </div>

                    <!-- content -->
                    <div class="overflow-auto h-[76%]">
                        <template v-if="searchData.searchType==='inPerson'">
                            <Location v-model="searchData" />
                            <Dates v-model="searchData" />
                            <Genre 
                                :key="pageKey"
                                :categories="inPersonCategories"
                                v-model="searchData" 
                                :loadOpen="true"/>
                            <Price v-model="searchData" />
                        </template>
                        <template v-if="searchData.searchType==='atHome'">
                            <Genre 
                                :key="pageKey"
                                v-model="searchData" 
                                :categories="atHomeCategories"
                                :loadOpen="false"/>
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
                                v-if="searchData.searchType==='inPerson'"
                                :disabled="!canSearchInPerson"
                                @click="search('inPerson')"
                                class="bg-default-red text-white font-semibold text-2xl px-8 py-4 border-none cursor-not-allowed disabled:opacity-50">
                                Search
                            </button>
                            <button 
                                v-if="searchData.searchType==='atHome'"
                                @click="search('atHome')"
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

        props:[ 'filter', 'atHomeCategories', 'inPersonCategories', 'tags'],

        components: { Location, Dates, NameSearch, Genre, Price },

        computed: {
            canSearchInPerson() {
                return this.searchData.location.name 
            },
        },

        data() {
            return {
                searchData: this.initializeSearchData(),
                open: false,
                scroll: false,
                pageKey: 0,
            }
        },

        methods: {
            search(type) {
                let content = `searchType=${type}&live=false`;
                if (this.searchData.location.name) {
                    content = content.concat(`&city=${this.searchData.location.name}&lat=${this.searchData.location.center.lat}&lng=${this.searchData.location.center.lng}`)
                }
                if (this.searchData.category.length) {
                    content = content.concat(`&category=${this.searchData.category[0].id}`)
                }
                if (this.searchData.tag.length) {
                    content = content.concat(`&tag=${this.searchData.tag[0].id}`)
                }
                if (this.hasPrice) {
                    content = content.concat(`&price0=${this.searchData.price[0]}&price1=${this.searchData.price[1]}`)
                }
                if (this.searchData.searchDates.length) {
                    content = content.concat(`&start=${this.searchData.searchDates[0]}&end=${this.searchData.searchDates[1]}`)
                }
                window.location.href = `/index/search?${content}`
            },
            showPage(type) {
                this.clear()
                this.searchData.searchType = type
                this.pageKey += 1;
            },
            clear() {
                this.searchData = this.initializeSearchData()
            },
            toggleSearch() {
                this.open=!this.open
                this.open ? document.body.classList.add('noscroll') : document.body.classList.remove('noscroll')
            },
            initializeSearchData() {
                return {
                    location:{
                        center: {
                            lat: null,
                            lng: null
                        }
                    },
                    currentTab:'location',
                    searchType: 'inPerson',
                    searchDates: [],
                    naturalDate: [],
                    tag: [],
                    category: [],
                    price:[0,100],
                }
            }
        },

}
    
</script>