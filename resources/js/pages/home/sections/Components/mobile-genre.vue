<template>
    <div>
        <!-- content -->
        <div class="relative">
            <div 
                v-if="inputVal.currentTab==='genre'"
                class="mx-8 mb-8 py-8 rounded-3xl bg-white shadow-custom-1">
                <div 
                    @click="hideGenre"
                    class="font-semibold text-4xl flex justify-between items-center px-8">
                    <span>Event Type</span>
                    <svg class="w-8 h-8">
                        <use :xlink:href="`/storage/website-files/icons.svg#ri-arrow-up-s-line`" />
                    </svg>
                </div>
                <!-- search -->
                <div class="w-full mt-4 px-8">
                    <div class="flex justify-between w-full items-center border border-neutral-300 rounded-2xl">
                        <button class="border-none w-20 h-20 items-center justify-center flex py-4">
                            <div class="fill-default-red">
                                <svg class="w-8 h-8">
                                    <use :xlink:href="`/storage/website-files/icons.svg#ri-search-line`" />
                                </svg>
                            </div>
                        </button>
                        <div class="relative w-full border-none hide-dropdown">
                            <input 
                                ref="genre"
                                class="relative border-none focus:border-none p-8 pl-6 w-full bg-transparent"
                                v-model="searchInput"
                                placeholder="Search by Type"
                                @input="searchGenre"
                                @focus="dropdown=true"
                                autocomplete="false"
                                onfocus="value = ''" 
                                type="text">
                        </div>
                        <button 
                            v-if="searchInput"
                            class="border-none w-20 h-20" 
                            @click="clear">
                            <svg class="w-10 h-10">
                                <use :xlink:href="`/storage/website-files/icons.svg#ri-close-circle-line`" />
                            </svg>
                        </button>
                    </div>
                </div>
                <!-- list -->
                <div 
                    v-if="!hasGenre"
                    class="mt-8 pl-8 overflow-y-hidden overflow-x-auto">
                    <div 
                        class="flex scroll-smooth overflow-x-auto" 
                        style="scroll-snap-type: x mandatory;">
                        <div 
                            @click="selectGenre(genre)"
                            v-for="genre in defaultGenres"
                            class="w-36 mr-4"
                            :key="genre.id + genre.type">
                            <div class="w-36 h-36 rounded-2xl overflow-hidden">
                                <picture>       
                                    <source 
                                        type="image/webp" 
                                        :srcset="`${$envImageUrl}${genre.thumbImagePath}`"> 
                                    <img :src="`${$envImageUrl}${genre.thumbImagePath.slice(0, -4)}jpg`">
                                </picture>
                            </div>
                            <p class="text-lg leading-6 mt-2">
                                {{genre.name}}
                            </p>
                        </div>
                    </div>
                </div>
            </div>
            <div 
                v-else
                class="mx-8 mb-8 p-8 rounded-3xl bg-white shadow-custom-1">
                <div 
                    @click="showGenre"
                    class="flex justify-between cursor-pointer">
                    <div class="text-2xl">
                        Type
                    </div>
                    <div class="font-semibold text-2xl text-right">
                        <p v-if="hasGenre && hasGenre.name">{{ hasGenre.name}}</p>
                        <p v-else>Search by Event Type</p>
                    </div>
                </div>
            </div>
            <div 
                v-if="dropdown"
                ref="genre1"
                class="absolute w-full z-50 top-[13rem]">
                <ul class="bg-white mx-8 px-8 pb-8 rounded-b-2xl min-h-[16rem]">
                    <li
                        @click="selectGenre(genre)"
                        class="flex items-center border-none cursor-pointer mt-4"
                        :key="genre.id + genre.type"
                        v-for="genre in limitedSearchOptions">
                        <span class="border rounded-xl mr-8 flex h-16 w-16 items-center justify-center overflow-hidden">
                            <picture
                                v-if="genre.thumbImagePath">       
                                <source 
                                    type="image/webp" 
                                    :srcset="`${$envImageUrl}${genre.thumbImagePath}`"> 
                                <img :src="`${$envImageUrl}${genre.thumbImagePath.slice(0, -4)}jpg`">
                            </picture>
                            <p v-else>o</p>
                        </span>
                        <p>{{genre.name}}</p>
                    </li>
                </ul>
            </div>
        </div>
    </div>
</template>

<script>
    export default {

        props: ['value', 'categories'],

        components: {  },

        computed: {
            inputVal: {
                get() { return this.value },
                set(val) { this.$emit('input', val) }
            },
            limitedSearchOptions() {
                return this.searchOptions.slice(0,5)
            },
            hasGenre() {
                if (this.inputVal.category && this.inputVal.category.length) {
                    return this.inputVal.category[0]
                }
                if (this.inputVal.tag && this.inputVal.tag.length) {
                    return this.inputVal.tag[0]
                }
                return null;
            }
        },

        data() {
            return {
                searchInput: this.assignType(),
                searchOptions: this.categories ? this.categories : [],
                dropdown: false,
                defaultGenres: this.categories ? this.categories : [],
            }
        },

        methods: {
            async searchGenre() {
                await axios.get('/api/search/navbar/tags', { params: { keywords: this.searchInput, searchType:this.value.searchType } })
                .then( res => {
                    this.searchOptions = res.data;
                })
            },
            selectGenre(genre) {
                if (genre.type === 'c') { this.inputVal.category=[genre]}
                if (genre.type === 't') { this.inputVal.tag=[genre]}
                this.searchInput=genre.name
                this.hideGenre()
            },
            showGenre() {
                this.inputVal.currentTab='genre'
                this.dropdown=false
                this.assignType();
            },
            assignType() {
                if (this.value.category && this.value.category.length) {
                    this.searchInput=this.value.category[0].name
                }
                if (this.value.tag && this.value.tag.length) {
                    this.searchInput=this.value.tag[0].name
                }
            },
            hideGenre() {
                this.inputVal.currentTab=''
                this.dropdown=false
                this.assignType();
            },
            clear() {
                this.inputVal.category=this.inputVal.tag=[]
                this.searchInput=null
            },
            onClickOutside(event) {
                let arr =  this.$refs.genre;
                let arr1 = this.$refs.genre1;
                if (!arr || arr.contains(event.target) || !arr1 || arr1.contains(event.target)) return;
                this.dropdown = false;
                this.assignType();
            },
        },
        mounted() {
            document.addEventListener("click", this.onClickOutside);
        },

        beforeUnmount() {
            document.removeEventListener("click", this.onClickOutside);
        },

}
    
</script>