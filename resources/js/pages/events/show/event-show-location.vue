<template>
    <section
        id="location"
        class="element subtext">
        <template v-if="event.hasLocation">
            <div>
                <h2>Location</h2>
                <div v-if="event.location.hiddenLocationToggle">
                    <a 
                        rel="noreferrer" 
                        target="_blank" 
                        :href="`http://maps.google.com/maps?q=${location.city?location.city:''},+${location.region?location.region:''}`">
                        <b><p v-if="event.location.venue"> {{ event.location.venue }} </p></b>
                        <p><span v-if="event.location.city"> {{ event.location.city }},</span> <span v-if="event.location.region"> {{ event.location.region }} </span></p>
                        <br>
                        <p> {{ event.location.hiddenLocation }} </p>
                    </a>
                </div>
                <div v-else>
                    <a 
                        rel="noreferrer" 
                        target="_blank" 
                        :href="`http://maps.google.com/maps?q=${location.home?location.home:''}+${location.street?location.street:''},+${location.city?location.city:''},+${location.region?location.region:''}`">
                        <b><p v-if="event.location.venue"> {{ event.location.venue }} </p></b>
                        <p>{{ location.home }} {{ location.street }} {{ location.city }}</p>
                        <p>{{ location.region }} {{ location.country }} {{ location.postal_code }}</p>
                    </a>
                </div>
                <div class="es__map">
                    <template v-if="center">
                        <div style="width:100%;height:400px">
                            <l-map 
                                :zoom="zoom" 
                                :center="center" 
                                :options="{ scrollWheelZoom: false, zoomControl: true }">
                                <l-tile-layer :url="url" />
                                <l-marker :lat-lng="center">
                                    <l-icon
                                        :iconSize="[25, 40]"
                                        :iconAnchor="[0,40]">
                                        <a 
                                            rel="noreferrer" 
                                            target="_blank" 
                                            :href="`http://maps.google.com/maps?q=${location.home?location.home:''}+${location.street?location.street:''},+${location.city?location.city:''},+${location.region?location.region:''}`">
                                            <img src="/images/vendor/leaflet/dist/marker-icon-2x.png" alt="location marker">
                                        </a>
                                    </l-icon>
                                </l-marker>
                            </l-map>
                        </div>  
                    </template>
                </div>
            </div>
        </template>
        <template v-else>
            <h2>What you will need</h2>
            <div class="es__remote">
                <div 
                    class="es__remote--element" 
                    v-for="remote in event.remotelocations"
                    :key="remote.id">
                    <h3> {{ remote.name }} </h3>
                    <p> {{ remote.description }} </p>
                </div>
            </div>
            <div 
                class="es__remote--element user"
                v-if="event.remote_description">
                <h3>Additional Instructions</h3>
                <p style="white-space: pre-wrap;">{{ event.remote_description }}</p>
            </div>
        </template>
    </section>
</template>

<script>
    import {LMap, LTileLayer, LMarker, LPopup, LIcon} from 'vue2-leaflet'
    export default {

        props: [ 'event' ],

        components: { LMap, LTileLayer, LMarker, LPopup, LIcon },

        data() {
            return {
                zoom:13,
                center: this.event.location_latlon,
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