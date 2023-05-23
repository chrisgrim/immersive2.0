<template>
    <div class="z-[2002]">
        <div class="search-nav fixed w-full bg-white m-auto z-[2002] top-0">
            <template v-if="!open">
                <div class="bg-white flex mx-8 my-6 p-4 justify-between items-center rounded-full bg-gray-100">
                    <button 
                        @click="onBack"
                        class="w-20 h-16 mr-4 flex items-center justify-center border-none">
                        <svg class="border-none flex justify-center w-8 h-8">
                            <use :xlink:href="`/storage/website-files/icons.svg#ri-arrow-left-s-line`" />
                        </svg>
                    </button>
                    <div 
                        @click="showSearch"
                        class="w-full">
                        <div>
                            <p class="text-xl font-bold whitespace-nowrap overflow-hidden text-ellipsis leading-5">
                                {{ inputVal.location.name }}
                            </p>
                        </div>
                        <div>
                            <p class="text-lg whitespace-nowrap overflow-hidden text-ellipsis">
                                <span v-if="inputVal.naturalDate.length">{{ inputVal.naturalDate[0] }}{{ inputVal.naturalDate[1] ? ' — ' + inputVal.naturalDate[1] : '' }}</span>
                                <span v-else>Add Dates</span>
                            </p>
                        </div>
                    </div>
                    <div
                        @click="showSearch"
                        :class="{ 'fill-default-red' : hasOptions }"
                        class="justify-center w-20 h-16 flex items-center">
                        <div class="border border-gray-300 p-4 rounded-full">
                            <svg class="w-8 h-8">
                                <use :xlink:href="`/storage/website-files/icons.svg#ri-settings-line`" />
                            </svg>
                        </div>
                    </div>
                </div>
            </template>

            <template v-else>
                <div class="fixed w-full inset-0 z-[2003] bg-slate-100">
                    <!-- nav section -->
                    <div class="px-8 my-8 flex justify-center w-full items-center">
                        <button 
                            class="ml-[-1rem] border-none w-20 h-20 items-center justify-center flex py-4 absolute left-8" 
                            @click="hideSearch">
                            <svg class="w-10 h-10">
                                <use :xlink:href="`/storage/website-files/icons.svg#ri-close-circle-line`" />
                            </svg>
                        </button>
                        <div class="mx-4 font-semibold border-black text-slate-400">
                            In Person
                        </div>
                    </div>

                    <!-- content -->
                    <div class="overflow-auto h-[79%]">
                        <Location 
                            clear="clear"
                            v-model="locData" />
                        <Dates v-model="inputVal" />
                        <Genre
                            :categories="inPersonCategories"
                            v-model="inputVal" 
                            :loadOpen="true"/>
                        <Price v-model="inputVal" />
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
                                :disabled="!canSearchLocation"
                                @click="onSearch"
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
    import searchBasicsMixin from '../../../mixins/search-basics-mixin'
    import Location from '../../Home/Sections/Components/mobile-location.vue'
    import Dates from '../../Home/Sections/Components/mobile-dates.vue'
    import Genre from '../../Home/Sections/Components/mobile-genre.vue'
    import Price from '../../Home/Sections/Components/mobile-price.vue'
    
    export default {

        props:['value', 'categories', 'tags', 'searchedCategories', 'inPersonCategories', 'loadedSearch'],

        components: { Location, Dates, Genre, Price },

        mixins: [ searchBasicsMixin ],

        computed: {
            inputVal: {
                get() { return this.value },
                set(val) { this.$emit('input', val) }
            },
            canSearchLocation() {
                return this.inputVal.location.name
            },
            hasOptions() {
                return false
            }
        },

        data() {
            return {
                locData:{
                    currentTab:'',
                    location: {
                        name: this.value.location.name,
                        center: { 
                            lat: this.value.location.center.lat,
                            lng: this.value.location.center.lng,
                        },
                    }
                },
                originalData: { ...this.value },
                open: false,
                scroll: false,
            }
        },

        methods: {
            onSearch() {
                if (this.inputVal.location.name !== this.locData.location.name) {
                    return this.locationSearch();
                }
                this.$emit('search') 
                this.open=false
            },
            onBack() {
                return window.location.href = '/'
                // document.referrer == "" ? window.location.href = '/' : window.history.back()
            },
            showSearch() {
                this.open=true
                this.originalData = { ...this.value }
                document.body.classList.add('noscroll')
            },
            hideSearch() {
                this.inputVal = this.originalData
                this.open=false
                document.body.classList.remove('noscroll')
            },
            clear() {
                this.$emit('clear') 
            },
            locationSearch() {
                let content = `searchType=inPerson&live=false`;
                content = content.concat(`&city=${this.locData.location.name}&lat=${this.locData.location.center.lat}&lng=${this.locData.location.center.lng}`)
                if (this.inputVal.category.length) {
                    content = content.concat(`&category=${this.inputVal.category[0].id}`)
                }
                if (this.inputVal.tag.length) {
                    content = content.concat(`&tag=${this.inputVal.tag[0].id}`)
                }
                if (this.hasPrice) {
                    content = content.concat(`&price0=${this.inputVal.price[0]}&price1=${this.inputVal.price[1]}`)
                }
                if (this.inputVal.searchDates.length) {
                    content = content.concat(`&start=${this.inputVal.searchDates[0]}&end=${this.inputVal.searchDates[1]}`)
                }
                window.location.href = `/index/search?${content}`
            },
        },
}
    
</script>