<template>
    <section 
        :class="[wideMap ? 'w-full overflow-hidden mt-32' : 'md:w-[41%] md:h-[inherit]' ]"
        class="flex left-0 right-0 top-0 bottom-auto fixed h-[calc(100vh-8rem)] md:inline-block md:relative">
        <div 
            :class="[wideMap ? 'relative' : 'sticky md:top-32' ]"
            class="search__map overflow-hidden w-full top-0 h-[calc(100vh-8rem)] md:w-full">
            <div 
                :class="[ mobile ? 'h-48 items-end' : 'items-center h-40']"
                class="w-full absolute z-[500] flex justify-center">
                <template v-if="!mobile">
                    <div class="absolute left-20 top-12">
                        <button 
                            @click="showWideMap"
                            class="bg-white flex border-none h-16 w-16 rounded-2xl items-center justify-center shadow-custom-1">
                            <svg class="h-12 w-12">
                                <use v-if="wideMap" :xlink:href="`/storage/website-files/icons.svg#ri-arrow-right-s-line`" />
                                <use v-else :xlink:href="`/storage/website-files/icons.svg#ri-arrow-left-s-line`" />
                            </svg>
                        </button>
                    </div>
                    <div class="flex justify-between items-center w-96 h-16 bg-white shadow-custom-1 rounded-2xl">
                        <label>
                            <span class="map-checkbox">
                                <input 
                                    @click="onMapSearch"
                                    type="checkbox"
                                    v-model="toggle">
                                <span class="check" />
                            </span> 
                        </label>
                        <p class="text-xl font-bold w-full">Search as I move the map </p>
                    </div>
                </template>
                <template v-else>
                    <div 
                        class="flex justify-between items-center w-52 rounded-full px-4 bg-white h-16 shadow-custom-1" 
                        @click="refresh">
                        <button class="items-center justify-center flex border-none p-0">
                            <svg class="w-8 h-8">
                                <use :xlink:href="`/storage/website-files/icons.svg#ri-restart-line`" />
                            </svg>
                        </button>
                        <p class="w-full text-xl font-bold">Search here</p>
                    </div>
                </template>
            </div>
            <div :class="[ mobile ? 'mobile search__map' : '' ]">
                <l-map
                    :zoom="map.zoom"
                    :center="map.currentCenter"
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
                    <l-marker-cluster>
                        <l-marker 
                            v-for="event in events"
                            :key="event.id" 
                            :icon="icon"
                            :lat-lng="event.location_latlon">
                            <l-icon
                                :iconSize="[100, 30]"
                                :iconAnchor="[50,4]"
                                class-name="icons">
                                <p>
                                    {{ fixedprice(event) }}
                                </p>
                            </l-icon>
                            <l-popup>
                                <popup-content :data="event" />
                            </l-popup>
                        </l-marker>
                    </l-marker-cluster>
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

        props: [ 'events', 'mobile'],

        computed: {
            eventsWithLoc() {
                return this.events.filter( event=> { return event.location.street })
            },
            fixedprice() {
                return event => event.price_range.replace(/\d+(\.\d{1,2})?/g, dec => parseInt(dec));
            },
        }, 

        data() {
            return {
                map: this.initializeMapObject(),
                icon: L.icon({
                    iconUrl: '/images/vendor/leaflet/dist/marker-icon.png',
                    iconSize: [32, 37],
                    iconAnchor: [16, 37]
                }),
                toggle: new URL(window.location.href).searchParams.get("live") ? new URL(window.location.href).searchParams.get("live") : false,
                wideMap: false,
            }
        },

        methods: {
            update() {
                if (this.map.live && !this.mobile) { 
                    this.$emit('update', this.map) 
                }
            },
            onMapSearch() {
                this.map.live =! this.map.live
                this.$emit('update', this.map)
            },
            refresh() {
                this.map.live = true
                this.$emit('update', this.map) 
            },
            showWideMap() {
                this.$emit('wideMap');
                this.wideMap =! this.wideMap
                this.$nextTick(() => this.$refs.map.mapObject.invalidateSize());
            },
            zoomUpdate(newZoom) {
                this.map.zoom = newZoom 
            },
            centerUpdate(center) {
                this.map.currentCenter = center
            },
            boundsUpdate(bounds) {
                this.map.currentBounds = bounds
                this.update()
            },
            initializeMapObject() {
                return {
                    zoom:new URL(window.location.href).searchParams.get("zoom") ? parseFloat(new URL(window.location.href).searchParams.get("zoom")) : 11,
                    max: 20,
                    min: 10,
                    url: "https://{s}.tile.jawg.io/jawg-sunny/{z}/{x}/{y}{r}.png?access-token=5Pwt4rF8iefMU4hIcRqZJ0GXPqWi5l4NVjEn4owEBKOdGyuJVARXbYTBDO2or3cU",
                    attribution:
                    '<a href="http://jawg.io" title="Tiles Courtesy of Jawg Maps" target="_blank">&copy; <b>Jawg</b>Maps</a> &copy; <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a> contributors',
                    currentCenter: this.initializeCenterObject(),
                    currentBounds: new URL(window.location.href).searchParams.get("NElat") ? this.initializeBoundariesObject() : null,
                    live: new URL(window.location.href).searchParams.get("live") ? new URL(window.location.href).searchParams.get("live") : false,
                }
            },
            initializeBoundariesObject() {
                return {
                    _northEast: {
                        lat: parseFloat(new URL(window.location.href).searchParams.get("NElat")),
                        lng: parseFloat(new URL(window.location.href).searchParams.get("NElng"))
                    },
                    _southWest: {
                        lat: parseFloat(new URL(window.location.href).searchParams.get("SWlat")),
                        lng: parseFloat(new URL(window.location.href).searchParams.get("SWlng"))
                    }
                }
            },
            initializeCenterObject() {
                if (new URL(window.location.href).searchParams.get("Clat")) {
                    return {
                        lat: parseFloat(new URL(window.location.href).searchParams.get("Clat")),
                        lng: parseFloat(new URL(window.location.href).searchParams.get("Clng"))
                    }
                }
                return {
                    lat: parseFloat(new URL(window.location.href).searchParams.get("lat")),
                    lng: parseFloat(new URL(window.location.href).searchParams.get("lng"))
                }
            }

        },

    };
</script>