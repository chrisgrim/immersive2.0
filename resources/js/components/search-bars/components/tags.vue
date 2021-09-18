<template>
    <div style="width:100%">
        <v-select
            v-model="searchInput"
            label="searchInput.model"
            :data-hide-options="!searchInput && !searchOptions.length"
            :placeholder="placeholder"
            :options="searchOptions"
            :filterable="false"
            :get-option-label="searchInput => searchInput.model.name"
            :reduce="searchInput => searchInput.model"
            @search="debounce" 
            @search:focus="debounce" 
            @input="onSelect">
            <template #option="{ model }">
                <div class="option__title">
                    <svg><use :xlink:href="`/storage/website-files/icons.svg#ri-price-tag-3-line`" /></svg>
                    <p> {{ model.name }} </p>
                </div>
            </template>
        </v-select>
    </div>
</template>

<script>

export default {

    props: ['page'],

    computed: {
    },

    data() {
        return {
            searchInput: null,
            searchOptions: [ ],
            isLoading: false,
            placeholder: 'Category and Tag Search',
        }
    },

    methods: {

        debounce(query) {
            if (this.timeout) 
                clearTimeout(this.timeout); 
            this.timeout = setTimeout(() => {
                this.generateSearchList(query);
            }, 200); // delay
        },

        async generateSearchList (query) {
            await axios.get('/api/search/navbar/tags', { params: { keywords: query } })
            .then( res => {
                this.searchOptions = res.data;
            })
        },

        onSelect() {
            this.saveSearchData();
            if (this.searchInput.type == 'c') window.location.href = `/index/search-all?category=${this.searchInput.id}`
            // if (this.searchInput.type == 'r')  window.location.href = `/index/search-all?remote=${this.searchInput.name}&id=${this.searchInput.id}`
            if (this.searchInput.type == 't') window.location.href = `/index/search-all?tag=${this.searchInput.name}`
        },

        saveSearchData() {
            axios.post('/search/storedata', {type: 'category', name: this.searchInput.name});
        },
    },

};
</script>