<template>
    <div class="nav-search__content">
        <div
            class="icon" 
            :class="{index: page == 'homepage'}">
            <svg 
                aria-hidden="true" 
                role="presentation" 
                viewBox="0 0 32 32" 
                xmlns="http://www.w3.org/2000/svg">
                <g fill="none">
                    <path d="m13 24c6.0751322 0 11-4.9248678 11-11 0-6.07513225-4.9248678-11-11-11-6.07513225 0-11 4.92486775-11 11 0 6.0751322 4.92486775 11 11 11zm8-3 9 9" />
                </g>
            </svg>
        </div>
        <v-select
            v-model="searchBoxInput"
            label="searchBoxInput.model"
            :placeholder="placeholder"
            :options="searchBoxOptions"
            :filterable="false"
            :get-option-label="searchBoxInput => searchBoxInput.model.name"
            :reduce="searchBoxInput => searchBoxInput.model"
            @search="debounce" 
            @search:focus="debounce" 
            @input="searchInput">
            <template #option="{ model }">
                <div 
                    class="option__title" 
                    style="padding-bottom:1rem;height:2.4rem;">
                    <div style="display:inline-block;float: left;">
                        <svg 
                            v-if="model.type == 'c' || model.type == 'r' || model.type == 't'" 
                            viewBox="0 0 24 24" 
                            fill="currentColor" 
                            fill-opacity="0" 
                            stroke="currentColor" 
                            stroke-width="1.5" 
                            focusable="false" 
                            aria-hidden="true" 
                            role="presentation" 
                            stroke-linecap="round" 
                            stroke-linejoin="round" 
                            style="height: 24px; width: 24px; display: inline-block; overflow: visible;">
                            <path d="M7.6,19.4h8.9c2.2,0,4-1.8,4-4V6.5c0-2.2-1.8-4-4-4H7.6c-2.2,0-4,1.8-4,4v8.9C3.6,17.7,5.4,19.4,7.6,19.4z" />
                            <path d="M20.5,7.5" />
                            <line 
                                x1="16.3" 
                                y1="11" 
                                x2="6.8" 
                                y2="11" />
                            <line 
                                x1="16.4" 
                                y1="11.2" 
                                x2="11.2" 
                                y2="7.5" />
                            <line 
                                x1="16.4" 
                                y1="10.7" 
                                x2="11.3" 
                                y2="14.5" />
                        </svg>
                        <svg 
                            v-if="model.type == 'o'" 
                            viewBox="0 0 24 24" 
                            fill="currentColor" 
                            fill-opacity="0" 
                            stroke="currentColor" 
                            stroke-width="1.5" 
                            focusable="false" 
                            aria-hidden="true" 
                            role="presentation" 
                            stroke-linecap="round" 
                            stroke-linejoin="round" 
                            style="height: 24px; width: 24px; display: inline-block; overflow: visible;">
                            <path d="M20.5,11c0-5-4.3-9-9.5-8.4C7.3,3,4.2,6,3.7,9.8c-0.4,2.9,0.7,5.5,2.6,7.3c0.1,0.1,0.3,0,0.2-0.1 c-0.2-0.9-0.2-1.9,0.1-3c0.6-2,2.4-3.6,4.4-3.9c3.7-0.7,6.9,2.1,6.9,5.7c0,0.5-0.6,1.3-0.2,1.4C19.5,15.7,20.5,13.4,20.5,11z" />
                            <path 
                                class="st0" 
                                d="M12.2,10c-3.2,0-5.8,2.6-5.8,5.8c0,0.6,0.1,1.1,0.2,1.7c1.5,1.2,3.4,2,5.5,2c2.2,0,4.2-0.9,5.7-2.2 c0.1-0.5,0.2-0.9,0.2-1.4C18,12.6,15.4,10,12.2,10z" />
                            <path 
                                class="st0" 
                                d="M20.5,7.5" />
                            <circle 
                                class="st0" 
                                cx="12.1" 
                                cy="7.2" 
                                r="2.4" />
                        </svg>
                        <svg 
                            v-if="model.call_to_action" 
                            viewBox="0 0 24 24" 
                            fill="currentColor" 
                            fill-opacity="0" 
                            stroke="currentColor" 
                            stroke-width="1.5" 
                            focusable="false" 
                            aria-hidden="true" 
                            role="presentation" 
                            stroke-linecap="round" 
                            stroke-linejoin="round" 
                            style="height: 24px; width: 24px; display: inline-block; overflow: visible;">
                            <path d="M8.6,19.4h6.9c2.8,0,5-2.2,5-5V7.5c0-2.8-2.2-5-5-5H8.6c-2.8,0-5,2.2-5,5v6.9C3.6,17.2,5.8,19.4,8.6,19.4z" />
                            <line 
                                x1="3.6" 
                                y1="7.5" 
                                x2="20.5" 
                                y2="7.5" />
                            <line 
                                x1="8.6" 
                                y1="2.5" 
                                x2="8.6" 
                                y2="1.2" />
                            <line 
                                x1="15.3" 
                                y1="2.5" 
                                x2="15.3" 
                                y2="1.2" />
                        </svg>
                        <svg 
                            v-if="model.latitude" 
                            viewBox="0 0 24 24" 
                            fill="currentColor" 
                            fill-opacity="0" 
                            stroke="currentColor" 
                            stroke-width="1.5" 
                            focusable="false" 
                            aria-hidden="true" 
                            role="presentation" 
                            stroke-linecap="round" 
                            stroke-linejoin="round" 
                            style="height: 24px; width: 24px; display: inline-block; overflow: visible;">
                            <path 
                                d="M12.2,2.2c-3.9,0-7.1,3.2-7.1,7.1c0,5.7,6.4,10.9,6.4,10.9c0.4,0.3,1,0.3,1.4,0c0,0,6.4-5.2,6.4-10.9 C19.2,5.4,16.1,2.2,12.2,2.2z M12.1,11.6c-1.5,0-2.7-1.2-2.7-2.7s1.2-2.7,2.7-2.7s2.7,1.2,2.7,2.7S13.6,11.6,12.1,11.6z" />
                        </svg>
                    </div>
                    <div style="font-size: 1.4rem;display:inline-block;float: left;padding-left:1rem;width: 90%;white-space: nowrap;">
                        {{ model.name }}
                    </div>
                </div>
            </template>
        </v-select>
    </div>
</template>

<script>

export default {

    props: ['searchtype', 'page'],

    computed: {
        placeholder() {
            if (new URL(window.location.href).searchParams.get("city")) {
                return new URL(window.location.href).searchParams.get("city");
            }
            return 'Search for event, category or location';
        }
    },


    data() {
        return {
            searchBoxInput: null,
            searchBoxOptions: [
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
            // if ( query && query.length < 3 ) { return }
            if (this.searchtype === 'location') {
                await axios.get('/api/search/navbar/location', { params: { keywords: query } })
                .then( res => {
                    this.searchBoxOptions = res.data;
                })
            } else {
                await axios.get('/api/search/navbar', { params: { keywords: query } })
                .then( res => {
                    this.searchBoxOptions = res.data;
                })
            }
        },

        initializeSearchObject() {
            return {
                name: '',
                latitude: '',
                longitude: '',
            }
        },

        searchInput() {
            this.saveSearchData();
            if (this.searchBoxInput.type == 'c') window.location.href = `/index/search-online?category=${this.searchBoxInput.id}`
            if (this.searchBoxInput.type == 'r')  window.location.href = `/index/search-online?remote=${this.searchBoxInput.name}&id=${this.searchBoxInput.id}`
            if (this.searchBoxInput.type == 't') window.location.href = `/index/search-online?tag=${this.searchBoxInput.name}`
            if (this.searchBoxInput.type == 'o') window.location.href = `/organizer/${this.searchBoxInput.slug}`
            if (this.searchBoxInput.call_to_action) window.location.href = `/events/${this.searchBoxInput.slug}`
            if (this.searchBoxInput.latitude) this.globalSearch()
        },

        saveSearchData() {
            if (this.searchBoxInput.type) {var data = 'category'}
            if (this.searchBoxInput.type == 'o') { var data = 'organizer'}
            if (this.searchBoxInput.call_to_action) { var data = 'event'}
            if (this.searchBoxInput.latitude) { var data = 'location'}
            axios.post('/search/storedata', {type: data, name: this.searchBoxInput.name});
        },

        globalSearch() {
            window.location.href = `/index/search?city=${this.searchBoxInput.name}&lat=${this.searchBoxInput.latitude}&lng=${this.searchBoxInput.longitude}`;
        },

    },

    mounted() {
        this.$store.dispatch("getContent"); 
    }

};
</script>