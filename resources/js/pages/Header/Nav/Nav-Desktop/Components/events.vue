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
                    placeholder="Event and Organizer Search"
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
                    v-for="item in searchOptions"
                    :key="item.model.id + item.index_name"
                    @click="onSelect(item)">
                    <div class="w-20 h-20 rounded-2xl overflow-hidden flex justify-center items-center">
                        <svg class="w-8 h-8 mr-4">
                            <use v-if="item.index_name === 'organizers'" :xlink:href="`/storage/website-files/icons.svg#ri-user-4-line`" />
                            <use v-if="item.index_name === 'events'" :xlink:href="`/storage/website-files/icons.svg#ri-calendar-line`" />
                        </svg>
                    </div>
                    <p class="text-xl leading-6 mt-2">
                        {{item.model.name}}
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
        async generateSearchList () {
            await axios.get('/api/search/navbar/eventorganizer', { params: { keywords:  this.searchInput } })
            .then( res => { 
                this.searchOptions = res.data })
        },
        onSelect(item) {
            this.saveSearchData(item);
            if (item.index_name == 'organizers') window.location.href = `/organizer/${item.model.slug}`
            if (item.index_name == 'events') window.location.href = `/events/${item.model.slug}`
        },
        saveSearchData(item) {
            if (item.index_name == 'organizers') { var data = 'organizer'}
            if (item.index_name == 'events') { var data = 'event'}
            axios.post('/search/storedata', {type: data, name: item.model.name});
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
                this.generateSearchList();
            }, 200); // delay
        },
    },

    mounted() {
        document.addEventListener('click', this.onClickOutside)
        this.generateSearchList()
    },

    unmounted() {
        document.removeEventListener('click',this.onClickOutside)
    }

};
</script>