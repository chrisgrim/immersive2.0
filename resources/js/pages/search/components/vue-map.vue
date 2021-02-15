<template>
    <section class="search__map--container">
        <div 
            v-if="desktop" 
            class="search__map"
            :style="pageHeight">
            <div>
                <div class="search__map--update-box">
                    <label>
                        <span class="search__map--checkbox">
                            <input 
                                @click="mapSearch = !mapSearch" 
                                type="checkbox" 
                                v-model="mapSearch">
                            <span class="check" />
                        </span> 
                    </label>
                    <p>Search as I move the map </p>
                </div>
                <div>
                    <l-map
                        :zoom="zoom"
                        :center="mapCenter"
                        :style="pageHeight"
                        @update:center="centerUpdate"
                        @update:bounds="boundsUpdate"
                        @update:zoom="zoomUpdate"
                        :maxZoom="max" 
                        :minZoom="min"
                        :options="{ scrollWheelZoom: false, zoomControl: true }">
                        <l-tile-layer 
                            :url="url" 
                            :attribution="attribution" />
                        <l-marker-cluster>
                            <l-marker 
                                v-for="event in events"
                                :key="event.id" 
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
        </div>

        <div 
            v-else 
            class="search__map fullmap" 
            :style="mobileMap" 
            @click="fullscreen">
            <button 
                class="search__map--update-box" 
                @click="onMapCenterChanged" 
                v-if="fullMap">
                <span>
                    <svg 
                        viewBox="0 0 16 16" 
                        height="16" 
                        width="16">
                        <path 
                            fill-rule="evenodd" 
                            clip-rule="evenodd" 
                            d="M10.227 3.152a5.552 5.552 0 0 0-3.595-.19 5.4 5.4 0 0 0-2.915 2.041 5.14 5.14 0 0 0-.955 3.352 5.177 5.177 0 0 0 1.413 3.197 5.455 5.455 0 0 0 3.175 1.64 5.537 5.537 0 0 0 3.535-.662 5.293 5.293 0 0 0 2.318-2.66.75.75 0 1 1 1.397.549 6.793 6.793 0 0 1-2.973 3.415 7.037 7.037 0 0 1-4.494.842 6.954 6.954 0 0 1-4.048-2.093 6.677 6.677 0 0 1-1.819-4.124 6.64 6.64 0 0 1 1.23-4.328A6.9 6.9 0 0 1 6.22 1.52a7.052 7.052 0 0 1 4.568.24 6.9 6.9 0 0 1 2.462 1.685V2.002a.75.75 0 0 1 1.5 0v3.6a.75.75 0 0 1-.75.75h-3.692a.75.75 0 1 1 0-1.5h2.171a5.386 5.386 0 0 0-2.252-1.7z" 
                            fill="currentColor" />
                    </svg>
                </span>
                <span>
                    <p>Search here</p>
                </span>
            </button>
            
            <div style="width:100%;">
                <l-map
                    :zoom="zoom"
                    ref="map"
                    :center="mapCenter"
                    :style="mobileMap"
                    :maxZoom="max" 
                    :minZoom="min"
                    @update:center="centerUpdate"
                    @update:bounds="boundsUpdate"
                    @update:zoom="zoomUpdate"
                    :options="{ scrollWheelZoom: false, zoomControl: true }">
                    <l-tile-layer 
                        :url="url" 
                        :attribution="attribution" />
                    <l-marker-cluster>
                        <l-marker 
                            v-for="event in events" 
                            :key="event.id" 
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

    import {LMap, LTileLayer, LMarker, LPopup, LIcon} from 'vue2-leaflet'
    import Vue2LeafletMarkerCluster from 'vue2-leaflet-markercluster'
    import { latLng } from "leaflet"
    import PopupContent from "../../read/popup-content"

    export default {

        components: { LPopup, LMap, LTileLayer, LMarker, 'l-marker-cluster': Vue2LeafletMarkerCluster, PopupContent, LIcon },

        props: [ 'events'],

        computed: {
            mapCenter() {
                return {
                    lat: new URL(window.location.href).searchParams.get("lat") ? new URL(window.location.href).searchParams.get("lat") : new URL(window.location.href).searchParams.get("Clat"),
                    lng: new URL(window.location.href).searchParams.get("lng") ? new URL(window.location.href).searchParams.get("lng") : new URL(window.location.href).searchParams.get("Clng"),
                }
            },
            eventsWithLoc() {
                return this.events.filter(function (event) {
                    return event.location.street;
                })
            },
            fixedprice() {
                return event => event.price_range.replace(/\d+(\.\d{1,2})?/g, dec => parseInt(dec));
            },

            fullMap() {
                // console.log(this.$store.state.map);
                // console.log(this.$store.state.mobilelocation);
                // console.log(this.$store.state.mobiledates);
                // console.log(this.$store.state.filter);
                return this.$store.state.map || this.$store.state.mobilelocation || this.$store.state.mobiledates || this.$store.state.filter;
            },
        }, 

        data() {
            return {
                zoom:new URL(window.location.href).searchParams.get("zoom") ? parseFloat(new URL(window.location.href).searchParams.get("zoom")) : 11,
                max: 18,
                min: 10,
                url: "https://{s}.basemaps.cartocdn.com/light_all/{z}/{x}/{y}{r}.png",
                attribution:
                '&copy; <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a> contributors &copy; <a href="https://carto.com/attributions">CARTO</a>',
                currentCenter: '',
                currentBounds: '',
                mapSearch: new URL(window.location.href).searchParams.get("mapsearch") ? new URL(window.location.href).searchParams.get("mapsearch") : false,
                desktop: window.innerWidth > 769,
                pageHeight: `height: 100vh;`,
                mobileMap : `height:${window.innerHeight/2}px;`,
            }
        },

        methods: {

            onMapCenterChanged() {
                this.$store.commit('storeMapBounds', {bounds: this.currentBounds, center: this.currentCenter, zoom: this.zoom});
            },

            zoomUpdate(newZoom) {
              this.zoom = newZoom;
            },

            centerUpdate(center) {
                this.currentCenter = center;
            },

            boundsUpdate(bounds) {
                this.currentBounds = bounds;
            },

            showFilter(value) {
                this.$store.commit(value, true);
            },

            fullscreen() {
                this.$store.commit('showmap', true);
                this.mapSearch = false;
            },

            tallMap() {
                this.mobileMap = `height:${window.innerHeight}px;`;
                this.$nextTick(() => this.$refs.map.mapObject.invalidateSize());
            },

            shortMap() {
                this.mobileMap = `height:${window.innerHeight/2}px;`;
                this.$nextTick(() => this.$refs.map.mapObject.invalidateSize());
            },

            getBounds() {
                this.currentBounds = {
                    _northEast: {
                        lat: parseFloat(new URL(window.location.href).searchParams.get("NElat")),
                        lng: parseFloat(new URL(window.location.href).searchParams.get("NElng"))
                    },
                    _southWest: {
                        lat: parseFloat(new URL(window.location.href).searchParams.get("SWlat")),
                        lng: parseFloat(new URL(window.location.href).searchParams.get("SWlng"))
                    }
                };
                this.currentCenter = {
                    lat: parseFloat(new URL(window.location.href).searchParams.get("Clat")),
                    lng: parseFloat(new URL(window.location.href).searchParams.get("Clng"))
                }
            },

            toggleBodyClass(addRemoveClass, className) {
                const el = document.body;

                if (addRemoveClass === 'addClass') {
                    el.classList.add(className);
                } else {
                    el.classList.remove(className);
                }
            },
        },

        watch: {
            currentCenter() {
                this.currentCenter.lat.toString().length > 10 && this.mapSearch ? this.onMapCenterChanged() : '' ;
            },

            fullMap() {
                window.scrollTo(0, 0);
                if (this.fullMap) {
                    this.tallMap();
                    this.toggleBodyClass('addClass', 'noscroll');
                } else {
                    this.shortMap();
                    this.toggleBodyClass('removeClass', 'noscroll');
                }

            }
        },

        mounted() {
            if (this.mapSearch) {this.getBounds()}
        },

    };
</script>