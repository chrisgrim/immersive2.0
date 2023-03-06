<template>
    <div>
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
                            At Home
                        </div>
                    </div>

                    <!-- content -->
                    <div class="overflow-auto h-[79%]">
                        <Genre
                            :categories="atHomeCategories ? atHomeCategories : categories"
                            v-model="inputVal" 
                            :loadOpen="true"/>
                        <Dates v-model="inputVal" />
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
                                @click="onSearch"
                                class="bg-default-red text-white font-semibold text-2xl px-8 py-4 border-none cursor-not-allowed disabled:opacity-50">
                                Search
                            </button>
                        </div>
                    </div>
                </div>
            </template>
        </div>
    </div>
</template>

<script>
    import searchBasicsMixin from '../../../mixins/search-basics-mixin'
    import Dates from '../../Home/Sections/Components/mobile-dates.vue'
    import Genre from '../../Home/Sections/Components/mobile-genre.vue'
    import Price from '../../Home/Sections/Components/mobile-price.vue'
    
    export default {

        props:['value', 'categories', 'tags', 'searchedCategories', 'atHomeCategories'],

        components: { Dates, Genre, Price },

        mixins: [ searchBasicsMixin ],

        computed: {
            inputVal: {
                get() { return this.value },
                set(val) { this.$emit('input', val) }
            },
            hasOptions() {
                return false
            }
        },

        data() {
            return {
                open: false,
                options: false,
                scroll: false,
            }
        },

        methods: {
            async onSearch() {
                this.inputVal.paginate = 1
                await axios.post(`/api/search/fetch?page=${this.value.paginate}`, this.inputVal)
                .then( res => { 
                    this.$emit('update', res.data) 
                })
                this.hideSearch()
                this.addPushState()
            },
            async onNext() {
                await axios.post(`/api/search/fetch?page=${this.value.paginate}`, this.inputVal)
                .then( res => { 
                    this.$emit('update', res.data) 
                })
                this.hideSearch()
                this.addPushState()
            },
            onBack() {
                return window.location.href = '/'
                // document.referrer == "" ? window.location.href = '/' : window.history.back()
            },
            debounce() {
                if (this.timeout) 
                    clearTimeout(this.timeout); 
                this.timeout = setTimeout(() => {
                    this.submit()
                }, 500);
            },
            showSearch() {
                this.open=true
                document.body.classList.add('noscroll')
            },
            hideSearch() {
                this.open=false
                document.body.classList.remove('noscroll')
            },
            clear() {
                this.$emit('clear') 
            },
        },

}
    
</script>