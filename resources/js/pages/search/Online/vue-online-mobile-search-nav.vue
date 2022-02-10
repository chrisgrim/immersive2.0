<template>
    <div>
        <div
            :class="[ open ? 'open' : null, scroll ? 'scroll' : null ]"
            class="mobile-search-nav fixed w-full bg-transparent h-[7.5rem] m-auto z-[2002] top-0">
            <template v-if="!open">
                <div class="bg-white w-full m-0 flex justify-between items-center p-8 h-30">
                    <div class="flex gap-4 items-center">
                        <button 
                            @click="onBack"
                            class="ml-[-1rem] w-16 h-16 mr-4 flex items-center justify-center border-none">
                            <svg class="border-none flex justify-center w-8 h-8">
                                <use :xlink:href="`/storage/website-files/icons.svg#ri-arrow-left-s-line`" />
                            </svg>
                        </button>
                        <div @click="showSearch">
                            <p class="underline text-xl font-bold whitespace-nowrap overflow-hidden text-ellipsis">
                                {{ naturalDate ? naturalDate : 'Add dates' }}
                            </p>
                        </div>
                    </div>
                    <button
                        :class="{ 'fill-default-red' : hasOptions}"
                        @click="showOptions"
                        class="mr-[-1rem] border-y-0 border-r-0 rounded-none border-l flex py-4 justify-center w-16">
                        <svg class="w-8 h-8">
                            <use :xlink:href="`/storage/website-files/icons.svg#ri-settings-line`" />
                        </svg>
                    </button>
                </div>
            </template>
            <template v-else>
                <div class="bg-white w-full m-0 flex justify-between items-center p-8 h-30">
                    <div class="flex gap-4 items-center">
                        <button 
                            class="ml-[-1rem] w-16 h-16 mr-4 flex items-center justify-center border-none"
                            @click="hideSearch">
                            <svg class="border-none flex justify-center w-8 h-8">
                                <use :xlink:href="`/storage/website-files/icons.svg#ri-close-line`" />
                            </svg>
                        </button>
                        <div class="title">
                            Search
                        </div>
                    </div>
                    <button 
                        :class="{ 'fill-default-red' : hasOptions}"
                        @click="showOptions"
                        class="mr-[-1rem] border-y-0 border-r-0 rounded-none border-l flex py-4 justify-center w-16">
                        <svg class="w-8 h-8">
                            <use :xlink:href="`/storage/website-files/icons.svg#ri-settings-line`" />
                        </svg>
                    </button>
                </div>
                <div class="wrapper bg-white border-b h-32">
                    <div class="mt-[-2rem]">
                        <VueFilterDates
                            :mobile="mobile"
                            v-model="data.dates"
                            @submit="submit" />
                    </div>
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
    import searchBasicsMixin from '../../../mixins/search-basics-mixin'
    import SearchOptions from './vue-mobile-search-options.vue'
    import VueFilterDates from '../Filter/vue-filter-dates.vue'
    
    export default {

        props:['value', 'filter', 'categories', 'tags', 'type', 'mobile'],

        components: { VueFilterDates, SearchOptions },

        mixins: [ searchBasicsMixin ],

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
                await axios.post(`/api/search/${this.type}?page=${this.value}`, this.data)
                .then( res => {
                    this.$emit('update', res) 
                })
                this.open = false
                this.addPushState()
            },
            async next() {
                await axios.post(`/api/search/${this.type}?page=${this.value}`, this.data)
                .then( res => {
                    this.$emit('update', res) 
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
        watch: {
            value() {
                this.next()
            }
        },
        created () {
            window.addEventListener('scroll', this.handleScroll);
        },
        destroyed () {
            window.removeEventListener('scroll', this.handleScroll);
        }
}
    
</script>