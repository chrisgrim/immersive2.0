<template>
    <div style="width:100%">
        <div 
            ref="search"
            class="w-full z-[10000]">
            <div class="w-full m-auto">
                <svg class="absolute top-8 left-8 w-8 h-8 fill-black z-[1002]">
                    <use xlink:href="/storage/website-files/icons.svg#ri-search-line"></use>
                </svg>
                <input 
                    class="relative rounded-full p-7 pl-24 border border-neutral-300 w-full font-normal z-[1001] focus:border-none focus:rounded-full focus:shadow-custom-7"
                    v-model="searchInput"
                    placeholder="Category and Tag Search"
                    @input="debounce"
                    @focus="inputVal.dropdown=true"
                    autocomplete="false"
                    onfocus="value = ''" 
                    type="text">
            </div>
            <ul 
                class="bg-white relative w-full m-auto overflow-hidden mt-8 p-8 list-none rounded-5xl shadow-custom-7" 
                ref="dropdown"
                v-if="inputVal.dropdown">
                <li 
                    class="flex items-center gap-8 hover:bg-neutral-100" 
                    v-for="genre in searchOptions"
                    :key="genre.id + genre.type"
                    @click="selectGenre(genre)">
                    <div class="w-20 h-20 m-2 rounded-2xl overflow-hidden flex justify-center items-center">
                        <picture
                            v-if="genre.thumbImagePath">       
                            <source 
                                type="image/webp" 
                                :srcset="`${$envImageUrl}${genre.thumbImagePath}`"> 
                            <img :src="`${$envImageUrl}${genre.thumbImagePath.slice(0, -4)}jpg`">
                        </picture>
                        <p v-else>o</p>
                    </div>
                    <p class="text-xl leading-6 mt-2">
                        {{genre.name}}
                    </p>
                </li>
            </ul>
        </div>
    </div>
</template>

<script>

export default {

    props: ['value'],

    computed: {
        inputVal: {
            get() { return this.value },
            set(val) { this.$emit('input', val) }
        },
    },

    data() {
        return {
            searchInput: '',
            searchOptions: [ ],
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
            this.saveSearchData();
            if (genre.type === 'c') { 
                 window.location.href = `/index/search?category=${genre.id}&searchType=allEvents`
            }
            if (genre.type === 't') { 
                window.location.href = `/index/search?tag=${genre.id}&searchType=allEvents`}
        },
        saveSearchData() {
            axios.post('/search/storedata', {type: 'category', name: this.searchInput.name});
        },
        onClickOutside(event) {
            let arr = this.$refs.search;
            if (!arr || arr.contains(event.target)) return;
            this.inputVal.dropdown = false
        },
        debounce() {
            if (this.timeout) 
                clearTimeout(this.timeout); 
            this.timeout = setTimeout(() => {
                this.searchGenre();
            }, 200); // delay
        },
    },
    
    mounted() {
        document.addEventListener('click', this.onClickOutside)
        this.searchGenre()
    },

    unmounted() {
        document.removeEventListener('click',this.onClickOutside)
    }

};
</script>