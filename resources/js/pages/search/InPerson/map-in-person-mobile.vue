<template>
    <section class="mt-36 flex left-0 right-0 top-0 bottom-auto fixed h-screen">
        <div class="search__map overflow-hidden w-full h-screen top-0 ">
            <div 
                v-show="value.loading">
                <div class="bg-white shadow-custom-1 w-16 h-16 rounded-full flex items-center justify-center z-[1001] absolute top-[8%] left-1/2 ml-[-2rem]">
                    <div
                        class="inline-block h-8 w-8 animate-spin rounded-full border-4 border-solid border-current border-r-transparent align-[-0.125em] motion-reduce:animate-[spin_1.5s_linear_infinite]"
                        role="status">
                        <span
                            class="!absolute !-m-px !h-px !w-px !overflow-hidden !whitespace-nowrap !border-0 !p-0 ![clip:rect(0,0,0,0)]">Loading...</span>
                    </div>
                </div>
            </div>
            <div class="mobile search__map">
                <l-map
                    :style="inputVal.location.fullMap ? 'height:70vh' : 'height:35vh;'"
                    :zoom="inputVal.location.zoom"
                    :center="inputVal.location.center"
                    @update:center="centerUpdate"
                    @update:bounds="boundsUpdate"
                    @update:zoom="zoomUpdate"
                    ref="map"
                    :maxZoom="map.max" 
                    :minZoom="map.min"
                    :options="{ scrollWheelZoom: false, zoomControl: true }">
                    <l-tile-layer 
                        :url="map.url" 
                        :attribution="map.attribution" />
                    <!-- <l-marker-cluster> -->
                    <l-marker 
                        v-for="event in events"
                        :key="event.id" 
                        :icon="icon"
                        :lat-lng="event.location_latlon">
                        <l-icon
                            :iconSize="[auto, 30]"
                            :iconAnchor="[50,4]"
                            class-name="icons">
                            <p class="m-4">
                                {{ fixedprice(event) }}
                            </p>
                        </l-icon>
                        <l-popup>
                            <popup-content :data="event" />
                        </l-popup>
                    </l-marker>
                    <!--  </l-marker-cluster> -->
                </l-map>
            </div>  
        </div>        
    </section>
</template>

<script>

    import 'leaflet/dist/leaflet.css';
    import L from 'leaflet';
    import {LMap, LTileLayer, LMarker, LPopup, LIcon} from 'vue2-leaflet'
    import Vue2LeafletMarkerCluster from 'vue2-leaflet-markercluster'
    import PopupContent from "./Components/map-popup-content"

    export default {

        components: { LPopup, LMap, LTileLayer, LMarker, 'l-marker-cluster': Vue2LeafletMarkerCluster, PopupContent, LIcon },

        props: [ 'value', 'events',],

        computed: {
            inputVal: {
                get() { return this.value },
                set(val) { this.$emit('input', val) }
            },
            fixedprice() {
                return event => event.price_range.replace(/\d+(\.\d{1,2})?/g, dec => parseInt(dec));
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
            }
        },

        methods: {
            update() {
                this.$emit('submit', true) 
            },
            debounce() {
                if (this.timeout) clearTimeout(this.timeout)
                this.timeout = setTimeout(() => {  this.update() }, 400)
            },
            refresh() {
                this.inputVal.location.live = true
            },
            zoomUpdate(newZoom) {
                this.inputVal.location.zoom = newZoom 
            },
            centerUpdate(center) {
                this.inputVal.location.center = center
            },
            boundsUpdate(bounds) {
                this.inputVal.location.mapboundary = bounds
                this.inputVal.location.live=true
                this.debounce()
            },
            initializeMapObject() {
                return {
                    max: 20,
                    min: 7,
                    url: "https://{s}.tile.jawg.io/jawg-sunny/{z}/{x}/{y}{r}.png?access-token=5Pwt4rF8iefMU4hIcRqZJ0GXPqWi5l4NVjEn4owEBKOdGyuJVARXbYTBDO2or3cU",
                    attribution:
                    '<a href="http://jawg.io" title="Tiles Courtesy of Jawg Maps" target="_blank">&copy; <b>Jawg</b>Maps</a> &copy; <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a> contributors',
                
                }
            },
        },
    };
</script>