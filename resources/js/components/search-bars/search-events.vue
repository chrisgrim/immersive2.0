<template>
    <div class="dropdown events">
        <div class="icon">
            <svg>
                <use :xlink:href="`/storage/website-files/icons.svg#ri-search-line`" />
            </svg>
        </div>
        <v-select
            v-model="searchInput"
            label="searchInput.model"
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
                    <svg><use :xlink:href="`/storage/website-files/icons.svg#ri-calendar-line`" /></svg>
                    <p>{{ model.name }} </p>
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
            searchOptions: [
                // {
                //     model: {
                //         name: 'Loading List...'
                //     }
                // }
            ],
            isLoading: false,
            search: this.initializeSearchObject(),
            type: '',
            test:'',
            placeholder: 'Search for events',
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
            await axios.get('/api/search/navbar', { params: { keywords: query } })
            .then( res => {
                this.searchOptions = res.data;
            })
        },

        initializeSearchObject() {
            return {
                name: '',
                latitude: '',
                longitude: '',
            }
        },

        onSelect() {
            this.saveSearchData();
            window.location.href = `/events/${this.searchInput.slug}`
        },

        saveSearchData() {
            axios.post('/search/storedata', {type: 'event', name: this.searchInput.name});
        },

    },

    mounted() {
        this.$store.dispatch("getContent"); 
    }

};
</script>