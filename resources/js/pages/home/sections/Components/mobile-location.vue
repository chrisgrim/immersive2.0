<template>
    <div>
        <!-- content -->
        <div class="relative">
            <div 
                v-if="hasLocation"
                class="m-8 p-8 rounded-3xl bg-white shadow-custom-1">
                <div 
                    @click="showLocation"
                    class="flex justify-between cursor-pointer">
                    <div class="text-2xl">
                        Location
                    </div>
                    <div class="font-semibold text-2xl">
                        <p v-if="inputVal.place_id">{{ inputVal.place_name}}</p>
                        <p v-else>Add Location</p>
                    </div>
                </div>
            </div>
            <div 
                v-else
                class="m-8 p-8 rounded-3xl bg-white shadow-custom-1">
                <div class="font-semibold text-4xl">
                    Location
                </div>
                <!-- search -->
                <div class="p4 flex justify-between w-full items-center border border-neutral-300 rounded-2xl mt-4">
                    <button class="border-none w-20 h-20 items-center justify-center flex py-4">
                        <div class="fill-default-red">
                            <svg class="w-8 h-8">
                                <use :xlink:href="`/storage/website-files/icons.svg#ri-search-line`" />
                            </svg>
                        </div>
                    </button>
                    <div class="relative w-full border-none hide-dropdown">
                        <input 
                            ref="location"
                            class="relative border-none focus:border-none p-8 w-full bg-transparent"
                            v-model="searchInput"
                            placeholder="Search by City"
                            @input="updateLocations"
                            @focus="dropdown=true"
                            autocomplete="false"
                            onfocus="value = ''" 
                            type="text">
                    </div>
                </div>
                <!-- list -->
                <div 
                    v-if="!inputVal.place_id"
                    class="mt-8 overflow-y-hidden overflow-x-auto whitespace-nowrap">
                    <div 
                        class="flex scroll-smooth overflow-x-auto" 
                        style="scroll-snap-type: x mandatory;">
                        <div 
                            v-for="place in defaultPlaces"
                            :key="place.place_id">
                            <div class="w-48 h-48 mr-4 border-neutral-300 border rounded-2xl">
                                <img 
                                    class=" w-6 m-auto" 
                                    src="/storage/images/vendor/leaflet/dist/marker-icon-2x.png">
                            </div>
                            <p class="text-xl mt-2">
                                {{place.description}}
                            </p>
                        </div>
                    </div>
                </div>
            </div>
            <div 
                v-if="dropdown"
                ref="location1"
                class="absolute w-full z-50 top-[13rem]">
                <ul class="bg-white mx-8 px-8 pb-8 rounded-b-2xl">
                    <li
                        @click="selectLocation(place)"
                        class="flex items-center border-none cursor-pointer mt-8"
                        :key="place.place_id"
                        v-for="place in places">
                        <span class="border rounded-xl mr-8 flex h-16 w-16 items-center justify-center">
                            <svg class="w-8 h-8">
                                <use :xlink:href="`/storage/website-files/icons.svg#ri-map-pin-line`" />
                            </svg>
                        </span>
                        <p>{{place.description}}</p>
                    </li>
                </ul>
            </div>
        </div>
    </div>
</template>

<script>
    
    export default {

        props: ['value'],

        components: {  },

        computed: {
            inputVal: {
                get() { return this.value },
                set(val) { this.$emit('input', val) }
            },
        },

        data() {
            return {
                searchInput: null,
                searchOptions: [],
                places:this.initializePlaces(),
                defaultPlaces:this.initializeDefaultPlaces(),
                dropdown: false,
                hasLocation:false,
            }
        },

        methods: {
            updateLocations() {
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
                this.dropdown=false
                this.inputVal.place_name=this.searchInput=place.address_components[0].long_name
                this.inputVal.place_id = place.place_id
                this.inputVal.lat = place.geometry.location.lat()
                this.inputVal.lng = place.geometry.location.lng()
                this.hasLocation=true
            },
            showLocation() {
                this.hasLocation=false
            },
            onClickOutside(event) {
                let arr =  this.$refs.location;
                let arr1 = this.$refs.location1;
                if (!arr || arr.contains(event.target) || !arr1 || arr1.contains(event.target)) return;
                this.dropdown = false;
            },
            initializePlaces() {
                return [
                    {place_id: 'ChIJOwg_06VPwokRYv534QaPC8g', description: 'New York, NY, USA'},
                    {place_id: 'ChIJE9on3F3HwoAR9AhGJW_fL-I', description: 'Los Angeles, CA, USA'},
                    {place_id: 'ChIJIQBpAG2ahYAR_6128GcTUEo', description: 'San Francisco, CA, USA'}
                ]
            },
            initializeDefaultPlaces() {
                return [
                    {place_id: 'ChIJOwg_06VPwokRYv534QaPC8g', description: 'New York'},
                    {place_id: 'ChIJE9on3F3HwoAR9AhGJW_fL-I', description: 'Los Angeles'},
                    {place_id: 'ChIJIQBpAG2ahYAR_6128GcTUEo', description: 'San Francisco'}
                ]
            },
        },
        mounted() {
            this.autoComplete = new google.maps.places.AutocompleteService();
            this.service = new google.maps.places.PlacesService(document.getElementById("places"));
            document.addEventListener("click", this.onClickOutside);
        },

        beforeUnmount() {
            document.removeEventListener("click", this.onClickOutside);
        },

}
    
</script>