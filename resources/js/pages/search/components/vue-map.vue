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
                                    @click="onMapSearch" 
                                    type="checkbox" 
                                    v-model="toggle">
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