<template>
    <div>
        <div
            :class="[ open ? 'open' : null, scroll ? 'scroll' : null ]"
            class="search-nav fixed w-full bg-transparent h-[7rem] m-auto z-[2002] top-0">
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
                <div class="fixed w-full inset-0 z-[2003] bg-white overflow-auto">
                    <div class="px-8 pt-8 pb-4 h-20 flex justify-between w-full items-center">
                        <button 
                            class="ml-[-1rem] border-none w-20 h-20 items-center justify-center flex py-4" 
                            @click="hideSearch">
                            <svg class="w-8 h-8">
                                <use :xlink:href="`/storage/website-files/icons.svg#ri-arrow-left-s-line`" />
                            </svg>
                        </button>
                        <div class="relative w-full border-none hide-dropdown">
                            <v-select
                                auto-focus="true"
                                v-model="searchInput"
                                label="searchInput.model"
                                placeholder="Enter Location"
                                :options="searchOptions"
                                :clear-search-on-select="false"
                                ref="theSearch"
                                :get-option-label="searchInput => searchInput.model.name"
                                :reduce="searchInput => searchInput.model"
                                @search="debounce" />
                        </div>
                    </div>
                    <template v-if="query">
                        <div class="w-full overflow-auto block border-t h-[calc(100%-5.5rem)]">
                            <ul class="m-0 p-0">
                                <li
                                    @click="onSelect(option)"
                                    class="flex items-center border-none cursor-pointer w-full px-8 py-4 mt-2"
                                    :key="option.model.id"
                                    v-for="option in searchOptions">
                                    <span class="border rounded-xl mr-8 flex h-16 w-16 items-center justify-center">
                                        <svg class="w-8 h-8">
                                            <use :xlink:href="`/storage/website-files/icons.svg#ri-map-pin-line`" />
                                        </svg>
                                    </span>
                                    <p>{{option.model.name}}</p>
                                </li>
                            </ul>
                        </div>
                    </template>
                    <template v-else>
                        <div class="flex flex-col p-12 h-[calc(100%-5.5rem)]">
                            <div class="relative my-4 pb-4">
                                <h4 class="uppercase font-semibold text-xl">Online Events</h4>
                                <a href="/index/search-online">
                                    <div class="flex my-8 mx-auto justify-around min-h-[5.5rem] w-11/12 rounded-full p-2 shadow-custom-1 items-center">
                                        <div>
                                            <p>Search online events</p>
                                        </div>
                                        <div class="flex mr-4 fill-default-red">
                                            <svg class="w-8 h-8">
                                                <use :xlink:href="`/storage/website-files/icons.svg#ri-arrow-right-s-line`" />
                                            </svg>
                                        </div>
                                    </div>
                                </a>
                            </div>
                            <div class="relative my-4 pb-4">
                                <h4 class="uppercase font-semibold text-xl">All Events</h4>
                                <a href="/index/search-all">
                                    <div class="flex my-8 mx-auto justify-around min-h-[5.5rem] w-11/12 rounded-full p-2 shadow-custom-1 items-center">
                                        <div>
                                            <p>Search all events</p>
                                        </div>
                                        <div class="icon">
                                            <svg class="w-8 h-8 fill-default-red">
                                                <use :xlink:href="`/storage/website-files/icons.svg#ri-arrow-right-s-line`" />
                                            </svg>
                                        </div>
                                    </div>
                                </a>
                            </div>
                        </div>
                    </template>
                </div>
            </template>
        </div>
    </div>
</template>

<script>
    import searchBasicsMixin from '../../../mixins/search-basics-mixin'
    
    export default {

        props:[ 'filter', 'categories', 'tags'],

        components: {  },

        mixins: [ searchBasicsMixin  ],

        computed: {
            naturalDate() {
                if (this.dates && this.dates.length) {
                    return `${ this.$dayjs(this.dates[0]).format("MMM D") } - ${ this.$dayjs(this.dates[1]).format("MMM D") }`
                }
                return null
            },
            list() {
                return this.$refs.search.searchOptions 
            },
        },

        data() {
            return {
                open: false,
                dates: [],
                scroll: false,
                searchInput: null,
                searchOptions: [],
                query: null,
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
            async generateSearchList (query) {
                this.query = query
                await axios.get('/api/search/navbar/location', { params: { keywords: query } })
                .then( res => {
                    // this.searchOptions = res.data
                    // console.log(res.data);
                    this.query.length > 0 ? this.searchOptions = res.data : this.searchOptions = []
                })
            },
            onSelect(val) {
                this.$refs.theSearch.onEscape()
                this.searchInput = val
                // this.saveSearchData();
                window.location.href = `/index/search?city=${val.model.name}&lat=${val.model.latitude}&lng=${val.model.longitude}`;
            },
            handleScroll () {
                this.scroll = window.pageYOffset > 10
            },
            debounce(query) {
                if (this.timeout) 
                    clearTimeout(this.timeout); 
                this.timeout = setTimeout(() => {
                    this.generateSearchList(query);
                }, 200); // delay
            },
            showSearch() {
                this.open = true
                document.body.classList.add('noscroll')
            },
            hideSearch() {
                this.open = false
                document.body.classList.remove('noscroll')
            },
        },
        created () {
            window.addEventListener('scroll', this.handleScroll);
        },
        destroyed () {
            window.removeEventListener('scroll', this.handleScroll);
        }

}
    
</script>