<template>
    <section
        id="location"
        class="border-b border-slate-200 py-12 px-8 md:py-16 md:px-0">
        <template v-if="event.hasLocation">
            <h2 class="text-4xl mb-4">Location</h2>
            <div v-if="event.location.hiddenLocationToggle">
                <a 
                    rel="noreferrer" 
                    target="_blank" 
                    class="text-2xl text-gray-500"
                    :href="`http://maps.google.com/maps?q=${location.city?location.city:''},+${location.region?location.region:''}`">
                    <b><span v-if="event.location.venue"> {{ event.location.venue }} </span></b><br>
                    <span><span v-if="event.location.city"> {{ event.location.city }},</span> <span v-if="event.location.region"> {{ event.location.region }} </span></span>
                    <br>
                    <span> {{ event.location.hiddenLocation }} </span>
                </a>
            </div>
            <div v-else>
                <a 
                    rel="noreferrer" 
                    target="_blank" 
                    class="text-2xl text-gray-500"
                    :href="`http://maps.google.com/maps?q=${location.home?location.home:''}+${location.street?location.street:''},+${location.city?location.city:''},+${location.region?location.region:''}`">
                    <b><span v-if="event.location.venue"> {{ event.location.venue }} </span></b><br>
                    <span>{{ location.home }} {{ location.street }} {{ location.city }}</span><br>
                    <span>{{ location.region }} {{ location.country }} {{ location.postal_code }}</span>
                </a>
            </div>
            <div class="mt-8">
                <template v-if="center">
                    <div class="w-full h-[400px]">
                        <l-map 
                            :zoom="zoom" 
                            :center="center" 
                            :options="{ scrollWheelZoom: false, zoomControl: true }">
                            <l-tile-layer :url="url" />
                            <l-marker 
                                :icon="icon"
                                :lat-lng="center">
                                <l-icon
                                    icon-url="'leaflet/dist/images/marker-icon.png'"
                                    icon-retina-url="'leaflet/dist/images/marker-icon-2x.png'"
                                    shadow-url="'leaflet/dist/images/marker-shadow.png'"
                                    shadow-retina-url="'leaflet/dist/images/marker-shadow.png'"
                                    :iconSize="[25, 40]"
                                    :iconAnchor="[0,40]">
                                    <a 
                                        rel="noreferrer" 
                                        target="_blank" 
                                        :href="`http://maps.google.com/maps?q=${location.home?location.home:''}+${location.street?location.street:''},+${location.city?location.city:''},+${location.region?location.region:''}`">
                                        <img src="/storage/images/vendor/leaflet/dist/marker-icon-2x.png" alt="location marker">
                                    </a>
                                </l-icon>
                            </l-marker>
                        </l-map>
                    </div>  
                </template>
            </div>
        </template>
        <template v-else>
            <h2 class="text-4xl mb-4">What you will need</h2>
            <div class="flex gap-8 mb-8 flex-wrap">
                <div 
                    class="w-80 border border-slate-200 rounded-xl p-8" 
                    v-for="remote in event.remotelocations"
                    :key="remote.id">
                    <h3> {{ remote.name }} </h3>
                    <p> {{ remote.description }} </p>
                </div>
            </div>
            <div 
                class="w-full border border-slate-200 rounded-xl bg-[#f5f5f5] p-8 max-w-[45rem]"
                v-if="event.remote_description">
                <h3>Additional Instructions</h3>
                <p style="white-space: pre-wrap;overflow: auto;">{{ event.remote_description }}</p>
            </div>
        </template>
    </section>
</template>

<script>
    import 'leaflet/dist/leaflet.css';
    import L from 'leaflet';
    import {LMap, LTileLayer, LMarker, LIcon} from 'vue2-leaflet'
    export default {

        props: [ 'event' ],

        components: { LMap, LTileLayer, LMarker, LIcon },

        data() {
            return {
                zoom:13,
                center: this.event.location_latlon,
                icon: L.icon({
                    iconUrl: '/storage/images/vendor/leaflet/dist/marker-icon.png',
                    iconSize: [32, 37],
                    iconAnchor: [16, 37]
                }),
                url: "https://{s}.tile.jawg.io/jawg-sunny/{z}/{x}/{y}{r}.png?access-token=5Pwt4rF8iefMU4hIcRqZJ0GXPqWi5l4NVjEn4owEBKOdGyuJVARXbYTBDO2or3cU",
                attribution:
                '<a href="http://jawg.io" title="Tiles Courtesy of Jawg Maps" target="_blank">&copy; <b>Jawg</b>Maps</a> &copy; <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a> contributors',
                location: this.initializeLocationObject(),
            }
        },

        methods: {
            initializeLocationObject() { 
                return {
                    home: '',
                    street: '',
                    city: '',
                    region: '',
                    country: '',
                    postal_code: '',
                    another_field: ''
                }
            },
        },

        mounted() {
            Object.assign(this.location, this.event.location);
        }
    }
</script>