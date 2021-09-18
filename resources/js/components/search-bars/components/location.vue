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
                    <svg><use :xlink:href="`/storage/website-files/icons.svg#ri-map-pin-line`" /></svg>
                    <p>{{ model.name }}</p>{
                </div>
            </template>
        </v-select>
    </div>
</template>

<script>

export default {

    props: ['page'],

    computed: {
        placeholder() {
            if (new URL(window.location.href).searchParams.get("city")) {
                return new URL(window.location.href).searchParams.get("city");
            }
            return 'Location Search';
        }
    },

    data() {
        return {
            searchInput: null,
            searchOptions: [ ],
            isLoading: false,
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
            await axios.get('/api/search/navbar/location', { params: { keywords: query } })
            .then( res => {
                this.searchOptions = res.data;
            })
        },

        onSelect() {
            this.saveSearchData();
            window.location.href = `/index/search?city=${this.searchInput.name}&lat=${this.searchInput.latitude}&lng=${this.searchInput.longitude}`;
        },

        saveSearchData() {
            axios.post('/search/storedata', {type: 'location', name: this.searchInput.name});
        },
    },

};
</script>