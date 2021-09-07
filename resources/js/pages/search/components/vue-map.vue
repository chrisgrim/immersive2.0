<template>
    <section class="search__map--container">
        <div class="search__map">
            <div class="update-box">
                <template v-if="!mobile">
                    <div class="show-more">
                        <button 
                            v-if="wideMap"
                            @click="showWideMap">
                            <svg>
                                <use :xlink:href="`/storage/website-files/icons.svg#ri-arrow-right-s-line`" />
                            </svg>
                        </button>
                        <button 
                            v-else
                            @click="showWideMap">
                            <svg>
                                <use :xlink:href="`/storage/website-files/icons.svg#ri-arrow-left-s-line`" />
                            </svg>
                        </button>
                    </div>
                    <div class="wrapper">
                        <label>
                            <span class="checkbox">
                                <input 
                                    @click="mapSearch = !mapSearch" 
                                    type="checkbox" 
                                    v-model="mapSearch">
                                <span class="check" />
                            </span> 
                        </label>
                        <p>Search as I move the map </p>
                    </div>
                </template>
                <template v-else>
                    <div class="wrapper" @click="refresh">
                        <button>
                            <svg>
                                <use :xlink:href="`/storage/website-files/icons.svg#ri-restart-line`" />
                            </svg>
                        </button>
                        <p>Search here</p>
                    </div>
                </template>
            </div>
            <div>
                <l-map
                    :zoom="map.zoom"
                    :center="map.center"
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
    import PopupContent from "../../read/popup-content"

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
                wideMap: false,
                mapSearch: new URL(window.location.href).searchParams.get("NElat") ? true : false,
            }
        },

        methods: {
            update() {
                if (this.mapSearch && !this.mobile) { this.debounce() }
            },
            refresh() {
                console.log('test');
                this.debounce() 
            },
            debounce() {
                if (this.timeout) 
                    clearTimeout(this.timeout); 
                this.timeout = setTimeout(() => {
                    this.$emit('update', this.map)
                }, 500);
            },
            showWideMap() {
                this.$emit('wideMap');
                this.wideMap =! this.wideMap
                this.$nextTick(() => this.$refs.map.mapObject.invalidateSize());
            },
            zoomUpdate(newZoom) {
                this.map.zoom = newZoom;
                this.update();
            },
            centerUpdate(center) {
                this.map.currentCenter = center;
                this.update();
            },
            boundsUpdate(bounds) {
                this.map.currentBounds = bounds;
            },
            initializeMapObject() {
                return {
                    zoom:new URL(window.location.href).searchParams.get("zoom") ? parseFloat(new URL(window.location.href).searchParams.get("zoom")) : 11,
                    max: 18,
                    min: 10,
                    url: "https://{s}.basemaps.cartocdn.com/light_all/{z}/{x}/{y}{r}.png",
                    attribution:
                    '&copy; <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a> contributors &copy; <a href="https://carto.com/attributions">CARTO</a>',
                    currentCenter: new URL(window.location.href).searchParams.get("Clat") ? this.initializeCenterObject() : null,
                    currentBounds: new URL(window.location.href).searchParams.get("NElat") ? this.initializeBoundariesObject() : null,
                    center: {
                        lat: new URL(window.location.href).searchParams.get("lat") ? new URL(window.location.href).searchParams.get("lat") : new URL(window.location.href).searchParams.get("Clat"),
                        lng: new URL(window.location.href).searchParams.get("lng") ? new URL(window.location.href).searchParams.get("lng") : new URL(window.location.href).searchParams.get("Clng"),
                    }
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
                return {
                    lat: parseFloat(new URL(window.location.href).searchParams.get("Clat")),
                    lng: parseFloat(new URL(window.location.href).searchParams.get("Clng"))
                }
            }

        },

    };
</script>