<template>
    <div>
        <!-- content -->
        <div class="relative">
            <div 
                v-if="hasGenre"
                class="m-8 p-8 rounded-3xl bg-white shadow-custom-1">
                <div 
                    @click="toggleGenre"
                    class="flex justify-between cursor-pointer">
                    <div class="text-2xl">
                        Genre
                    </div>
                    <div class="font-semibold text-2xl text-right">
                        <p v-if="inputVal.genre && inputVal.genre.name">{{ inputVal.genre.name}}</p>
                        <p v-else>Add Genre</p>
                    </div>
                </div>
            </div>
            <div 
                v-else
                class="m-8 py-8 rounded-3xl bg-white shadow-custom-1">
                <div 
                    @click="toggleGenre"
                    class="font-semibold text-4xl flex justify-between items-center px-8">
                    <span>Event Genre</span>
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
                                placeholder="Search by Genre"
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
                    v-if="!inputVal.genre.name"
                    class="mt-8 pl-8 overflow-y-hidden overflow-x-auto">
                    <div 
                        class="flex scroll-smooth overflow-x-auto" 
                        style="scroll-snap-type: x mandatory;">
                        <div 
                            @click="selectGenre(genre.model)"
                            v-for="genre in defaultGenres"
                            class="w-36 mr-4"
                            :key="genre.model.id + genre.model.type">
                            <div class="w-36 h-36 rounded-2xl overflow-hidden">
                                <picture>       
                                    <source 
                                        type="image/webp" 
                                        :srcset="`${$envImageUrl}${genre.model.thumbImagePath}`"> 
                                    <img :src="`${$envImageUrl}${genre.model.thumbImagePath.slice(0, -4)}jpg`">
                                </picture>
                            </div>
                            <p class="text-lg leading-6 mt-2">
                                {{genre.model.name}}
                            </p>
                        </div>
                    </div>
                </div>
            </div>
            <div 
                v-if="dropdown"
                ref="genre1"
                class="absolute w-full z-50 top-[13rem]">
                <ul class="bg-white mx-8 px-8 pb-8 rounded-b-2xl min-h-[16rem]">
                    <li
                        @click="selectGenre(genre.model)"
                        class="flex items-center border-none cursor-pointer mt-4"
                        :key="genre.model.id + genre.model.type"
                        v-for="genre in searchOptions">
                        <span class="border rounded-xl mr-8 flex h-16 w-16 items-center justify-center overflow-hidden">
                            <picture
                                v-if="genre.model.thumbImagePath">       
                                <source 
                                    type="image/webp" 
                                    :srcset="`${$envImageUrl}${genre.model.thumbImagePath}`"> 
                                <img :src="`${$envImageUrl}${genre.model.thumbImagePath.slice(0, -4)}jpg`">
                            </picture>
                            <p v-else>o</p>
                        </span>
                        <p>{{genre.model.name}}</p>
                    </li>
                </ul>
            </div>
        </div>
    </div>
</template>

<script>
    
    export default {

        props: ['value'],

        components: {  },

        computed: {
            inputVal: {
                get() { return this.value },
                set(val) { this.$emit('input', val) }
            },
        },

        data() {
            return {
                searchInput: null,
                searchOptions: [],
                hasGenre: false,
                dropdown: false,
                defaultGenres: this.initializeGenres(),
            }
        },

        methods: {
            async searchGenre() {
                await axios.get('/api/search/navbar/tags', { params: { keywords: this.searchInput } })
                .then( res => {
                    this.searchOptions = res.data;
                })
            },
            selectGenre(genre) {
                this.inputVal.genre=genre
                this.searchInput=genre.name
                this.toggleGenre()
            },
            toggleGenre() {
                this.hasGenre=!this.hasGenre
                this.dropdown=false
                this.searchInput=this.inputVal.genre.name
            },
            clear() {
                this.searchInput=null
                this.inputVal.genre={}
            },
            onClickOutside(event) {
                let arr =  this.$refs.genre;
                let arr1 = this.$refs.genre1;
                if (!arr || arr.contains(event.target) || !arr1 || arr1.contains(event.target)) return;
                this.dropdown = false;
                this.searchInput=this.inputVal.genre.name
            },
            async initializeGenres() {
                await axios.get('/api/search/navbar/tags', { params: { keywords: this.searchInput } })
                .then( res => {
                    this.defaultGenres = this.searchOptions = res.data;
                })
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