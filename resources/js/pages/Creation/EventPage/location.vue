<template>
    <main class="transition ease-in duration-300">
        <div class="fixed w-[calc(100vw-26rem)] overflow-auto h-full left-[26rem]">
            <div class="p-12 h-28 w-full">
                <p class="font-semibold">Location</p>
            </div>
            <div class="flex justify-center w-full p-8">
                <div class="max-w-3xl">
                    <h2>Location of Event</h2>
                </div>
            </div>
            <div class="fixed top-64 right-0 bottom-0 w-[calc(100vw-26rem)]">
                <div 
                    v-if="locationSearch"
                    class="absolute w-full top-12 z-[10000]">
                    <div class="max-w-3xl w-full m-auto">
                        <img 
                            class="absolute z-[1002] w-8 mt-7 ml-8" 
                            src="/storage/images/vendor/leaflet/dist/marker-icon-2x.png">
                        <input 
                            class="relative rounded-full p-10 pl-24 shadow-custom-6 w-full font-medium z-[1001] focus:rounded-3xl focus:shadow-none"
                            v-model="userInput"
                            placeholder="Enter Address"
                            @input="updateLocations"
                            @focus="dropdown=true"
                            autocomplete="false"
                            onfocus="value = ''" 
                            type="text">
                    </div>
                    <ul 
                        class="bg-white relative max-w-3xl w-full m-auto mt-[-3rem] pt-[3rem] list-none rounded-b-3xl shadow-custom-6" 
                        v-if="dropdown">
                        <li 
                            class="py-6 px-8 flex items-center gap-8 hover:bg-gray-300" 
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
                <div 
                    v-else
                    class="absolute top-0 right-0 z-[10000] min-w-[35rem] max-w-3xl h-full overflow-auto">
                    <div class="bg-white rounded-3xl shadow-custom-6 p-8 m-8">
                        <h4 class="mb-8 text-2xl font-semibold">Everything look right?</h4>
                        <div 
                            class="font-light" 
                            @click="locationSearch=true">
                            <div class="px-8 py-6 border-black border border-b-0 rounded-t-3xl">
                                <p>{{inputVal.event.location.home}} {{inputVal.event.location.street}}</p>
                            </div>
                            <div class="px-8 py-6 border-black border-b-0 border">
                                <p>{{inputVal.event.location.city}}</p>
                            </div>
                            <div class="border-black border border-b-0 grid grid-cols-2 divide-x divide-solid items-center">
                                <p class="px-8 py-6">{{inputVal.event.location.region}}</p>
                                <p class="px-8 py-6 border-black border-l-1">{{inputVal.event.location.postal_code}}</p>
                            </div>
                            <div class="px-8 py-6 border-black border rounded-b-3xl mb-8">
                                <p>{{inputVal.event.location.country}}</p>
                            </div>
                        </div>
                        <div>
                            <h4 class="mb-8 text-2xl font-semibold">Venue Name (optional)</h4>
                            <input 
                                class="p-8 border border-black rounded-3xl mb-8" 
                                placeholder="Venue" 
                                v-model="inputVal.event.location.venue"
                                type="text">
                        </div>
                        <div class="flex justify-between items-center">
                            <p class="text-xl">Hide specific location from users </p>
                            <input 
                                class="w-12 h-12 border border-black rounded-lg" 
                                v-model="inputVal.event.location.hiddenLocation"
                                type="checkbox">
                        </div>
                    </div>
                </div>
                <l-map 
                    :zoom="map.zoom" 
                    :center="map.center" 
                    style="height:100%;"
                    :options="{ scrollWheelZoom: false, zoomControl: true }">
                    <l-tile-layer :url="map.url" />
                    <l-marker 
                        :icon="icon"
                        :lat-lng="map.center">
                        <l-icon
                            :iconSize="[25, 40]"
                            :iconAnchor="[0,40]">
                            <img src="/storage/images/vendor/leaflet/dist/marker-icon-2x.png" alt="">
                        </l-icon>
                    </l-marker>
                </l-map> 
            </div>
        </div>
        <div id="places" />
    </main>
</template>

<script>
    import 'leaflet/dist/leaflet.css';
    import L from 'leaflet';
    import {LMap, LTileLayer, LMarker, LIcon} from 'vue2-leaflet'
    import locationInput from './Components/locationInput'
    import _ from 'lodash'
    export default {

        props: ['value'],

        mixins: [ locationInput ],
        
        components: { LMap, LTileLayer, LMarker, LIcon },

        computed: {
            inputVal: {
                get() { return this.value },
                set(val) { this.$emit('input', val) }
            },
        },

        data() {
            return {
                map: this.initializeMapObject(),
                icon: L.icon({
                    iconUrl: '/storage/images/vendor/leaflet/dist/marker-icon.png',
                    iconSize: [32, 37],
                    iconAnchor: [16, 37]
                }),
                userInput: '',
                places:[],
                newLoc: '',
                dropdown: false,
                locationSearch: this.value.event.location.latitude ? false : true,
            };
        },

        methods: {
            updateLocations() {
                this.autoComplete.getPlacePredictions({input:this.userInput}, data => {
                    this.places=data;
                });
            },
            selectLocation(location) {
                this.service.getDetails({placeId:location.place_id}, data => {
                    this.setPlace(data)
                });
                this.userInput=location.description
                this.dropdown=false
                this.locationSearch=false
                this.$nextTick(() => { this.$emit('submit') });
                this.inputVal.submit=true
            },
            initializeMapObject() {
                return {
                    zoom:14,
                    center: this.value.event.location.latitude ? { lat:this.value.event.location.latitude, lng:this.value.event.location.longitude } : { lat:40.7127753, lng:-74.0059728 },
                    url:'https://{s}.tile.jawg.io/jawg-sunny/{z}/{x}/{y}{r}.png?access-token=5Pwt4rF8iefMU4hIcRqZJ0GXPqWi5l4NVjEn4owEBKOdGyuJVARXbYTBDO2or3cU',
                    attribution:'<a href="http://jawg.io" title="Tiles Courtesy of Jawg Maps" target="_blank">&copy; <b>Jawg</b>Maps</a> &copy; <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a> contributors',
                }
            },
        },

        mounted() {
            this.autoComplete = new google.maps.places.AutocompleteService(
                    {types: ['geocode']}
                );
            this.service = new google.maps.places.PlacesService(document.getElementById("places"));
        },

    }
</script>