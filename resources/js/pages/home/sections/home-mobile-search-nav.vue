<template>
    <div>
        <div
            :class="{open : open, scroll: scroll}"
            class="search-nav">
            <template v-if="!open">
                <div class="placeholder">
                    <div class="icon">
                        <svg>
                            <use :xlink:href="`/storage/website-files/icons.svg#ri-search-line`" />
                        </svg>
                    </div>
                    <div 
                        @click="showSearch"
                        class="location">
                        <p>Start your search</p>
                    </div>
                </div>
            </template>
            <template v-else>
                <div class="nav-search__options">
                    <div class="header">
                        <button 
                            class="svg close" 
                            @click="hideSearch">
                            <svg>
                                <use :xlink:href="`/storage/website-files/icons.svg#ri-arrow-left-s-line`" />
                            </svg>
                        </button>
                        <div class="search">
                            <v-select
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
                        <div class="search-list">
                            <ul class="list-box">
                                <li
                                    @click="onSelect(option)"
                                    class="city"
                                    :key="option.model.id"
                                    v-for="option in searchOptions">
                                    <span class="loc">
                                        <svg><use :xlink:href="`/storage/website-files/icons.svg#ri-map-pin-line`" /></svg>
                                    </span>
                                    <p>{{option.model.name}}</p>
                                </li>
                            </ul>
                        </div>
                    </template>
                    <template v-else>
                        <div class="body">
                            <div class="item online">
                                <h4>Online Events</h4>
                                <a href="/index/search-online">
                                    <div class="list-placeholder">
                                        <div class="location">
                                            <p>Search online events</p>
                                        </div>
                                        <div class="icon">
                                            <svg>
                                                <use :xlink:href="`/storage/website-files/icons.svg#ri-arrow-right-s-line`" />
                                            </svg>
                                        </div>
                                    </div>
                                </a>
                            </div>
                            <div class="item all">
                                <h4>All Events</h4>
                                <a href="/index/search-all">
                                    <div class="list-placeholder">
                                        <div class="location">
                                            <p>Search all events</p>
                                        </div>
                                        <div class="icon">
                                            <svg>
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