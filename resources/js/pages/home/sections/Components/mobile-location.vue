<template>
    <div>
        <!-- content -->
        <div class="relative">
            <div 
                v-if="hasLocation"
                class="m-8 p-8 rounded-3xl bg-white shadow-custom-1">
                <div 
                    @click="toggleLocation"
                    class="flex justify-between cursor-pointer">
                    <div class="text-2xl">
                        Location
                    </div>
                    <div class="font-semibold text-2xl">
                        <p v-if="inputVal.location && inputVal.location.name">{{ inputVal.location.name}}</p>
                        <p v-else>Add Location</p>
                    </div>
                </div>
            </div>
            <div 
                v-else
                class="m-8 py-8 rounded-3xl bg-white shadow-custom-1">
                <div 
                    @click="toggleLocation"
                    class="font-semibold text-4xl flex justify-between items-center px-8">
                    <span>Location</span>
                    <svg class="w-8 h-8">
                        <use :xlink:href="`/storage/website-files/icons.svg#ri-arrow-up-s-line`" />
                    </svg>
                </div>
                <!-- search -->
                <div class="w-full mt-4 px-8">
                    <div class="flex justify-between w-full items-center border border-neutral-300 rounded-2xl">
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
                                class="relative border-none focus:border-none p-8 pl-6 w-full bg-transparent"
                                v-model="searchInput"
                                placeholder="Search by City"
                                @input="updateLocations"
                                @focus="dropdown=true"
                                autocomplete="false"
                                onfocus="value = ''" 
                                type="text">
                        </div>
                        <button 
                            v-if="searchInput"
                            class="border-none w-20 h-20 absolute right-16" 
                            @click="clearLocation">
                            <svg class="w-10 h-10">
                                <use :xlink:href="`/storage/website-files/icons.svg#ri-close-circle-line`" />
                            </svg>
                        </button>
                    </div>
                </div>
                <!-- list -->
                <div 
                    v-if="!inputVal.place_id"
                    class="mt-8 pl-8 overflow-y-hidden overflow-x-auto">
                    <div 
                        class="flex scroll-smooth overflow-x-auto" 
                        style="scroll-snap-type: x mandatory;">
                        <div 
                            @click="selectLocation(place)"
                            v-for="place in defaultPlaces"
                            :key="place.place_id">
                            <div class="w-48 h-48 mr-4 border-neutral-300 border rounded-2xl flex">
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
                <ul class="bg-white mx-8 px-8 pb-8 rounded-b-2xl min-h-[16rem]">
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
                defaultPlaces: this.initializePlaces(),
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
                this.service.getDetails({
                    placeId:location.place_id,
                    fields:['formatted_address', 'geometry', 'name', 'place_id']}, data => {
                        this.setPlace(data)
                    });
            },
            setPlace(place) {
                this.dropdown=false
                this.searchInput=place.name
                this.inputVal.location=place
                this.hasLocation=true
            },
            clearLocation() {
                this.searchInput=''
                this.inputVal.location={}
            },
            toggleLocation() {
                this.hasLocation=!this.hasLocation
                this.dropdown=false
                this.searchInput=this.inputVal.location.name
            },
            onClickOutside(event) {
                let arr =  this.$refs.location;
                let arr1 = this.$refs.location1;
                if (!arr || arr.contains(event.target) || !arr1 || arr1.contains(event.target)) return;
                this.dropdown = false;
                this.searchInput=this.inputVal.location.name
            },
            initializePlaces() {
                return [
                    {place_id: 'ChIJOwg_06VPwokRYv534QaPC8g', description: 'New York, NY'},
                    {place_id: 'ChIJE9on3F3HwoAR9AhGJW_fL-I', description: 'Los Angeles, CA'},
                    {place_id: 'ChIJdd4hrwug2EcRmSrV3Vo6llI', description: 'London, UK'},
                    {place_id: 'ChIJIQBpAG2ahYAR_6128GcTUEo', description: 'San Francisco, CA'}
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