<template>
    <div style="width:100%">
        <input 
            ref="autocomplete" 
            class="my-4"
            placeholder="locationPlaceholder"
            autocomplete="false"
            onfocus="value = ''" 
            type="text">
    </div>
</template>

<script>

export default {

    props: ['page'],

    data() {
        return {
            searchInput: null,
            searchOptions: [ ],
            city: new URL(window.location.href).searchParams.get("city"),
            location: '',
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

    mounted() {
        this.autocomplete = new google.maps.places.Autocomplete(
            (this.$refs.autocomplete),
            { types: ['geocode'] },
        );
        this.autocomplete.addListener('place_changed', this.setPlace);
    },


};
</script>