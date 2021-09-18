<template>
    <div>
        <div
            :class="{open : open, scroll: scroll}"
            class="search-nav">
            <template v-if="!open">
                <div class="placeholder">
                    <div />
                    <div 
                        @click="showSearch"
                        class="location">
                        <p>{{ city }}</p>
                    </div>
                    <div 
                        @click="showSearch"
                        class="dates">
                        <template v-if="naturalDate">
                            <p>{{ naturalDate }}</p>
                        </template>
                        <template v-else>
                            <p>Add dates</p>
                        </template>
                    </div>
                </div>
            </template>
            <template v-else>
                <div class="actual">
                    <button 
                        class="svg close" 
                        @click="hideSearch">
                        <svg>
                            <use :xlink:href="`/storage/website-files/icons.svg#ri-close-line`" />
                        </svg>
                    </button>
                    <div class="title">
                        Search
                    </div>
                </div>
                <div class="wrapper">
                    <SearchBar />
                    <VueFilterDates
                        :mobile="true"
                        v-model="dates"
                        @submit="submit" />
                </div>
            </template>
        </div>
    </div>
</template>

<script>
    import SearchBar from '../../../components/search-bars/search-location.vue'
    import searchBasicsMixin from '../../../mixins/search-basics-mixin'
    import VueFilterDates from '../../search/filter/vue-filter-dates.vue'
    
    export default {

        props:[ 'filter', 'categories', 'tags'],

        components: { SearchBar, VueFilterDates },

        mixins: [ searchBasicsMixin  ],

        computed: {
            naturalDate() {
                if (this.dates && this.dates.length) {
                    return `${ this.$dayjs(this.dates[0]).format("MMM D") } - ${ this.$dayjs(this.dates[1]).format("MMM D") }`
                }
                return null
            },
        },

        data() {
            return {
                open: false,
                scroll: false,
                city: 'Start your search',
                dates: [],
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
            handleScroll () {
                this.scroll = window.pageYOffset > 10
            },
            showSearch() {
                this.open = true
            },
            hideSearch() {
                this.open = false
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