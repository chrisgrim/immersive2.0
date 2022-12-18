<template>
    <div style="width:100%">
        <div ref="search" class="w-full z-[10000]">
            <div class="w-full m-auto">
                <img 
                    class="absolute z-[1002] w-6 mt-5 ml-8" 
                    src="/storage/images/vendor/leaflet/dist/marker-icon-2x.png">
                <input 
                    ref="loc"
                    class="relative rounded-full p-7 pl-24 border border-neutral-300 w-full font-normal z-[1001] focus:border-none focus:rounded-full focus:shadow-custom-7"
                    v-model="searchInput"
                    placeholder="Search by City"
                    @input="updateLocations"
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
                    class="py-4 px-8 flex items-center gap-8 hover:bg-neutral-100" 
                    v-for="place in places"
                    :key="place.place_id"
                    @click="selectLocation(place)">
                    <img 
                        class="w-8" 
                        src="/storage/images/vendor/leaflet/dist/marker-icon-2x.png">
                    {{place.description}}
                </li>
            </ul>
        </div>
        <div id="places" />
    </div>
</template>

<script>
export default {

    props: ['page', 'value'],

    computed: {
        inputVal: {
            get() { return this.value },
            set(val) { this.$emit('input', val) }
        },
    },

    data() {
        return {
            searchInput: null,
            searchOptions: [ ],
            places:this.initializePlaces(),
            newLoc: '',
            city: new URL(window.location.href).searchParams.get("city"),
        }
    },

    methods: {
        updateLocations() {
            this.inputVal.dropdown = this.searchInput.length ? true : false
            this.autoComplete.getPlacePredictions({input:this.searchInput, types: ['(cities)'] }, data => {
                this.places=data;
            });
        },
        selectLocation(location) {
            this.service.getDetails({placeId:location.place_id}, data => {
                this.setPlace(data)
            });
        },
        setPlace(place) {
            this.saveSearchData(place);
            window.location.href = `/index/search?city=${place.name}&lat=${place.geometry.location.lat()}&lng=${place.geometry.location.lng()}`;
        },
        close (e) {
            if (!this.$refs.search.contains(e.target)) {
                this.inputVal.dropdown = false
            }
        },
        saveSearchData(place) {
            axios.post('/search/storedata', {type: 'location', name: place.name.name});
        },
        initializePlaces() {
            return [
                {place_id: 'ChIJOwg_06VPwokRYv534QaPC8g', description: 'New York, NY, USA'},
                {place_id: 'ChIJE9on3F3HwoAR9AhGJW_fL-I', description: 'Los Angeles, CA, USA'},
                {place_id: 'ChIJIQBpAG2ahYAR_6128GcTUEo', description: 'San Francisco, CA, USA'}
            ]
        }
    },


    mounted() {
        this.autoComplete = new google.maps.places.AutocompleteService();
        this.service = new google.maps.places.PlacesService(document.getElementById("places"));
        this.$refs.loc.focus()
        document.addEventListener('click', this.close)
    },

    unmounted() {
        document.removeEventListener('click',this.close)
    }

};
</script>