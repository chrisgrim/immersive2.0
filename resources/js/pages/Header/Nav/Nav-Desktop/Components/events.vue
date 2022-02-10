<template>
    <div class="" style="width:100%">
        <v-select
            v-model="searchInput"
            label="searchInput.model"
            :data-hide-options="!searchInput && !searchOptions.length"
            placeholder="Event and Organizer Search"
            :options="searchOptions"
            :filterable="false"
            :get-option-label="searchInput => searchInput.model.name"
            :reduce="searchInput => searchInput.model"
            @search="debounce" 
            @search:focus="debounce" 
            @input="onSelect">
            <template #option="{ model }">
                <div class="flex p-3">
                    <svg class="w-8 h-8 mr-4">
                        <use v-if="model.type == 'o'" :xlink:href="`/storage/website-files/icons.svg#ri-user-4-line`" />
                        <use v-if="model.call_to_action" :xlink:href="`/storage/website-files/icons.svg#ri-calendar-line`" />
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

    computed: {
    },

    data() {
        return {
            searchInput: null,
            searchOptions: [ ],
        }
    },

    methods: {
        async generateSearchList (query) {
            await axios.get('/api/search/navbar/events', { params: { keywords: query } })
            .then( res => { this.searchOptions = res.data })
        },
        debounce(query) {
            if (this.timeout) 
                clearTimeout(this.timeout); 
            this.timeout = setTimeout(() => {
                this.generateSearchList(query);
            }, 200); // delay
        },
        onSelect() {
            this.saveSearchData();
            if (this.searchInput.type == 'o') window.location.href = `/organizer/${this.searchInput.slug}`
            if (this.searchInput.call_to_action) window.location.href = `/events/${this.searchInput.slug}`
        },
        saveSearchData() {
            if (this.searchInput.type == 'o') { var data = 'organizer'}
            if (this.searchInput.call_to_action) { var data = 'event'}
            axios.post('/search/storedata', {type: data, name: this.searchInput.name});
        },
    },

};
</script>