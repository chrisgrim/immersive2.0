<template>
    <div>
        <div
            :class="{open : open, scroll: scroll}"
            class="search-nav">
            <template v-if="!open">
                <div class="placeholder">
                    <button 
                        @click="onBack"
                        class="arrow svg">
                        <svg>
                            <use :xlink:href="`/storage/website-files/icons.svg#ri-arrow-left-s-line`" />
                        </svg>
                    </button>
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
                    <button
                        :class="{active : hasOptions}"
                        @click="showOptions"
                        class="options svg">
                        <svg>
                            <use :xlink:href="`/storage/website-files/icons.svg#ri-settings-line`" />
                        </svg>
                    </button>
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
                    <button 
                        :class="{active : hasOptions}"
                        class="svg options" 
                        @click="showOptions">
                        <svg>
                            <use :xlink:href="`/storage/website-files/icons.svg#ri-settings-line`" />
                        </svg>
                    </button>
                </div>
                <div class="wrapper">
                    <VueFilterDates
                        :mobile="isMobile()"
                        v-model="data.dates"
                        @submit="submit" />
                </div>
            </template>
        </div>
        <SearchOptions
            v-if="options"
            :categories="categories"
            :tags="tags"
            @update="updateOptions"
            @close="hideOptions" />
    </div>
</template>

<script>
    import searchBasicsMixin from '../../mixins/search-basics-mixin'
    import mobile from '../../mixins/mobile'
    import SearchOptions from './components/mobile-options.vue'
    import VueFilterDates from './filter/vue-filter-dates.vue'
    
    export default {

        props:['value', 'filter', 'categories', 'tags'],

        components: { VueFilterDates, SearchOptions },

        mixins: [ searchBasicsMixin, mobile ],

        computed: {
            inputVal: {
                get() { return this.value },
                set(val) { this.$emit('input', val) }
            },
            naturalDate() {
                if (this.data.dates && this.data.dates.length) {
                    return `${ this.$dayjs(this.data.dates[0]).format("MMM D") } - ${ this.$dayjs(this.data.dates[1]).format("MMM D") }`
                }
                return null
            },
            hasOptions() {
                if (this.data.category.length) { return true }
                if (this.data.tag.length) { return true }
                if (this.data.price[0] !== 0 ||this.data.price[1] !== 100) { return true }
                return false
            }
        },

        data() {
            return {
                open: false,
                options: false,
                scroll: false,
                mobile: window.innerWidth < 768,
                data: {
                    category: this.initilizeCategory(),
                    dates: this.initilizeDates(),
                    price: this.initilizePrice(),
                    tag: this.initilizeTag(),
                }
            }
        },

        methods: {
            async submit() {
                this.inputVal = 1
                await axios.post(`/api/search/online?page=${this.value}`, this.data)
                .then( res => {
                    this.$emit('update', res.data) 
                })
                this.open = false
                this.addPushState()
            },
            onBack() {
                document.referrer == "" ? window.location.href = '/' : window.history.back()
            },
            debounce() {
                if (this.timeout) 
                    clearTimeout(this.timeout); 
                this.timeout = setTimeout(() => {
                    this.submit()
                }, 500);
            },
            showSearch() {
                this.open = true
            },
            hideSearch() {
                this.open = false
            },
            handleScroll () {
                this.scroll = window.pageYOffset > 10
                if (this.open) { this.open = false }
            },
            showOptions() {
                this.options = true
                this.open = false
                document.body.classList.add('noscroll')
            },
            hideOptions() {
                this.options = false
                document.body.classList.remove('noscroll')
            },
            updateOptions(val) {
                this.data.category = val.category
                this.data.price = val.price
                this.data.tag = val.tag
                this.hideOptions()
                this.debounce()
            },
            initilizeCategory() {
                let cat = new URL(window.location.href).searchParams.get("category")
                return cat ? cat.split(',').map(Number) : []
            },
            initilizeTag() {
                let tag = new URL(window.location.href).searchParams.get("tag")
                return tag ? tag.split(',') : []
            },
            initilizePrice() {
                return new URL(window.location.href).searchParams.get("price0") ? [Number(new URL(window.location.href).searchParams.get("price0")), Number(new URL(window.location.href).searchParams.get("price1"))] : [0,100]
            },
            initilizeDates() {
                return new URL(window.location.href).searchParams.get("start") ? [new URL(window.location.href).searchParams.get("start"), new URL(window.location.href).searchParams.get("end")] : []
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