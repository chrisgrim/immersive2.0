<template>
    <div class="w-full border-b">
        <v-select
            v-model="searchInput"
            label="searchInput.model"
            :data-hide-options="!searchInput && !searchOptions.length"
            :placeholder="city ? city : 'Location Search'"
            :options="searchOptions"
            :filterable="false"
            :get-option-label="searchInput => searchInput.model.name"
            :reduce="searchInput => searchInput.model"
            class="border-none"
            @search="debounce" 
            @search:focus="debounce" 
            @input="onSelect">
            <template #option="{ model }">
                <div class="flex p-3">
                    <svg class="w-8 h-8 mr-4">
                        <use :xlink:href="`/storage/website-files/icons.svg#ri-map-pin-line`" />
                    </svg>
                    <p>{{ model.name }}</p>
                </div>
            </template>
        </v-select>
    </div>
</template>

<script>

export default {

    props: ['page'],

    data() {
        return {
            searchInput: null,
            searchOptions: [ ],
            city: new URL(window.location.href).searchParams.get("city")
        }
    },

    methods: {
        async generateSearchList (query) {
            await axios.get('/api/search/navbar/location', { params: { keywords: query } })
            .then( res => { this.searchOptions = res.data })
        },
        onSelect() {
            this.saveSearchData();
            window.location.href = `/index/search?city=${this.searchInput.name}&lat=${this.searchInput.latitude}&lng=${this.searchInput.longitude}`;
        },
        saveSearchData() {
            axios.post('/search/storedata', {type: 'location', name: this.searchInput.name});
        },
        debounce(query) {
            if (this.timeout) 
                clearTimeout(this.timeout); 
            this.timeout = setTimeout(() => {
                this.generateSearchList(query);
            }, 200); // delay
        },
    },

};
</script>