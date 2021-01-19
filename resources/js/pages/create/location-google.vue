<template>
    <div 
        :class="{ remote: !hasLocation, showmap: location.latitude && hasLocation}" 
        class="event-create__location grid">
        <section 
            :class="{ showmap: location.latitude && hasLocation}" 
            class="event-enter-location">
            <div class="title">
                <h2>Location</h2>
            </div>
            <div class="field">
                <label> Does your event have a physical location? </label>
                <div id="cover">
                    <input  
                        @input="$v.select.$touch" 
                        v-model="hasLocation" 
                        type="checkbox" 
                        id="checkbox">
                    <div id="bar" />
                    <div id="knob">
                        <p v-if="hasLocation">
                            Yes
                        </p>
                        <p v-else>
                            No
                        </p>
                    </div>
                </div>
            </div>
            <div v-show="hasLocation">
                <div class="field">
                    <label> Is your location hidden? </label>
                    <div id="cover">
                        <input 
                            @input="$v.location.hiddenLocationToggle.$touch" 
                            v-model="location.hiddenLocationToggle" 
                            type="checkbox" 
                            id="checkbox">
                        <div id="bar" />
                        <div id="knob">
                            <p v-if="location.hiddenLocationToggle">
                                Yes
                            </p>
                            <p v-else>
                                No
                            </p>
                        </div>
                    </div>
                </div>
                <div 
                    class="field" 
                    v-if="location.hiddenLocationToggle">
                    <label> Please enter how participants will be notified of the location. (Required) </label>
                    <textarea 
                        v-model.trim="location.hiddenLocation" 
                        rows="4" 
                        :class="{ active: active == 'hidden', 'error': $v.location.hiddenLocation.$error }"
                        required 
                        autofocus
                        placeholder="...the night before you will receieve an email containing the location..."
                        @click="active = 'hidden'"
                        @blur="active = null"
                        @input="$v.location.hiddenLocation.$touch" />
                    <div v-if="$v.location.hiddenLocation.$error" class="validation-error">
                        <p class="error" v-if="!$v.location.hiddenLocation.ifHidden">Please let our readers know how they will be informed</p>
                    </div>
                </div>
                <div class="field">
                    <label> Venue name (optional) </label>
                    <input 
                        v-model="location.venue" 
                        placeholder="Venue name"
                        :class="{ active: active == 'venue', 'error': $v.location.venue.$error }"
                        @click="active = 'venue'"
                        @blur="active = null"
                        @input="$v.location.venue.$touch"
                        type="text">
                    <div v-if="$v.location.$error" class="validation-error">
                        <p class="error" v-if="$v.location.venue.$error">No longer than 40 characters</p>
                    </div>
                </div> 
                <div class="field">
                    <label> Event Location </label>
                    <label v-if="location.hiddenLocationToggle"> Please enter a full address. This will NOT appear in your event description, but will aid users in finding events near them. If you do not have an exact address, please enter the closest zip code. <br> <b>Note:</b> If you <i>just</i> enter a major city, your event will overlap with others on map view. Be as specific as possible.</label>
                    <input 
                        ref="autocomplete" 
                        :placeholder="locationPlaceholder"
                        :class="{ active: active == 'location', 'error': $v.location.latitude.$error }"
                        autocomplete="false"
                        onfocus="value = ''" 
                        @click="active = 'location'"
                        @blur="active = null"
                        @input="$v.location.latitude.$touch"
                        type="text">
                    <div v-if="$v.location.$error" class="validation-error">
                        <p class="error" v-if="!$v.location.latitude.ifLocation">Please select from the list of locations</p>
                        <p class="error" v-if="!$v.location.city.ifLocation">We couldn't determine the city. Please try again.</p>
                    </div>
                </div>   
            </div>
            <div v-show="!hasLocation">
                <div class="field">
                    <label> What mediums will your remote event be using? </label>
                    <v-select 
                        v-model="remoteLocations"
                        taggable
                        multiple
                        label="name"
                        :options="remoteLocationOptions.filter(o => remoteLocations.indexOf(o) < 0)"
                        placeholder="Type here to create your own"
                        @search:blur="active = null"
                        @search:focus="active = 'remote'"
                        @input="$v.remoteLocations.$touch"
                        :class="{ active: active == 'remote', error: $v.remoteLocations.$error}" />
                    <div v-if="$v.remoteLocations.$error" class="validation-error">
                        <p class="error" v-if="!$v.remoteLocations.ifNoLocation">Please choose at least one Mobile Location</p>
                        <p class="error" v-if="!$v.remoteLocations.maxChar">Mobile Locations may not be longer than 25 characters.</p>
                    </div>
                </div>
                <div class="field">
                    <label class="area"> Additional instructions or suggestions (optional) </label>
                    <textarea 
                        type="text"
                        name="description" 
                        v-model="description" 
                        placeholder="eg. Sign into Zoom 10 minutes early... "
                        :class="{ active: active == 'description'}"
                        @click="active = 'description'"
                        @input="$v.description.$touch"
                        @blur="active = null" 
                        rows="8" />
                </div>
            </div>
            <CubeSpinner :loading="loading" />
        </section>

        <section style="width:100%">
            <div 
                :class="{ showmap: location.latitude && hasLocation}" 
                class="event-show-location" 
                :style="`height:calc(${window/pageHeight}px - 7rem)`">
                <div 
                    v-if="map.center && hasLocation" 
                    class="event-create-map">
                    <div
                        class="event-show-map"
                        :style="`height:calc(${window/pageHeight}px - 7rem)`" 
                        style="width:100%;">
                        <l-map 
                            :zoom="map.zoom" 
                            :center="map.center" 
                            :options="{ scrollWheelZoom: false, zoomControl: true }">
                            <l-tile-layer :url="map.url" />
                            <l-marker :lat-lng="map.center" />
                        </l-map>
                    </div>  
                </div>
            </div>
        </section>
        <Submit 
            @submit="onSubmit"
            :disabled="disabled" 
            previous="title"
            next="category" 
            :event="event" />
        <transition name="slide-fade">
            <div v-if="updated" class="updated-notifcation">
                <p>Your event has been updated.</p>
            </div>
        </transition>
    </div>
</template>

<script>
    import CubeSpinner  from '../layouts/loading.vue'
    import googleLocationMixin from './components/google-location-mixin'
    import formValidationMixin from '../../mixins/form-validation-mixin'
    import { maxLength } from 'vuelidate/lib/validators'
    import {LMap, LTileLayer, LMarker} from 'vue2-leaflet'
    import _ from 'lodash'
    import Submit  from './components/submit-buttons.vue'

    export default {
        props: ['event', 'remote'],

        mixins: [ googleLocationMixin, formValidationMixin ],

        components: { LMap, LTileLayer, LMarker, CubeSpinner, Submit },

        computed: {
            locationPlaceholder() {
                return this.location.postal_code || this.location.city ? (this.location.home ? this.location.home + ' ' : '') 
                + (this.location.street ? this.location.street + ', ' : '') 
                + (this.location.city ? this.location.city + ', ' : '') 
                + (this.location.country ? this.location.country : '') 
                : 'Enter full address ';
            },

            remoteLocationArray() {
                return {
                    remote: this.remoteLocations.map(a => a.name), 
                    description: this.description
                };
            },

            endpoint() {
                return `/create/${this.event.slug}/location`
            },

            corsEndpoint() {
                return `https://maps.googleapis.com/maps/api/geocode/json?address=${this.location.postal_code ? this.location.postal_code : this.location.city}&key=AIzaSyBxpUKfSJMC4_3xwLU73AmH-jszjexoriw`
            },
        },
        data() {
            return {
                location: this.initializeLocationObject(),
                map: this.initializeMapObject(),
                active: null,
                disabled: false,
                window: window.innerHeight,
                pageHeight: window.innerWidth > 1050 ? 1 : 1.5,
                hasLocation: this.event.hasLocation,
                remoteLocationOptions: this.remote ? this.remote : '',
                remoteLocations: this.event.remotelocations ? this.event.remotelocations : '',
                description: this.event.remote_description ? this.event.remote_description : '',
                loading: false,
                updated: false,
                creationPage: 2,
            }
        },
        methods: {
            onLoad() {
                axios.get(this.onFetch('location'))
                .then( res => { this.updateEventFields(res.data.location) });
            },
            
            async onSubmit(value) {
                if ( this.checkForChanges(value) ) { return this.onForward(value) }
                if ( this.checkVuelidate() ) { return }
                await axios.patch( this.endpoint, this.hasLocation ? this.location : this.remoteLocationArray )
                value == 'save' ? this.save() : this.onForward(value);
            },

            updateEventFields(input) {
                if ((input !== null) && (typeof input === "object") && (input.id !== null)) {
                    this.location = _.pick(input, _.intersection( _.keys(this.location), _.keys(input) ));
                }
                this.location.latitude ? this.map.center = L.latLng(this.location.latitude, this.location.longitude) : '';
            },

            initializeLocationObject() {
                return {
                    street:  '',
                    city:  '',
                    region: '',
                    country: '',
                    postal_code: '',
                    hiddenLocation: '',
                    hiddenLocationToggle: 0,
                    latitude: '',
                    longitude: '',
                    home: '',
                    venue: '',
                }
            },

            initializeMapObject() {
                return {
                    zoom:14,
                    center: '',
                    url:'http://{s}.tile.osm.org/{z}/{x}/{y}.png',
                    attribution:'&copy; <a href="http://osm.org/copyright">OpenStreetMap</a> contributors',
                }
            },

        },

        mounted() {
            this.onLoad();
            this.autocomplete = new google.maps.places.Autocomplete(
                (this.$refs.autocomplete),
                {types: ['geocode']}
            );
            this.autocomplete.addListener('place_changed', this.setPlace);
            this.updateEventFields(this.event.location);
            this.disabled = false;
        },

        watch: {
            '$store.state.navurl'() {
                this.checkForChanges('') ? this.onBack(this.$store.state.navurl.page) : this.onSubmit(this.$store.state.navurl.page)
            }
        },

        validations: {
            description: {},
            location: {
                latitude: {
                    ifLocation() { 
                        return this.hasLocation ? this.location.latitude ? true : false : true
                    },
                },
                city: {
                    ifLocation() { 
                        return this.hasLocation ? this.location.city || this.location.country ? true : false : true
                    },
                },
                hiddenLocationToggle: {},
                venue: {
                    maxLength: maxLength(40)
                },
                hiddenLocation: {
                    ifHidden() {
                        return this.location.hiddenLocationToggle ? this.location.hiddenLocation ? true : false : true
                    }
                }
            },
            remoteLocations: {
                ifNoLocation() {
                    return !this.hasLocation ? this.remoteLocations.length ? true : false : true
                },
                maxChar() {
                    return this.remoteLocations.filter( remote => remote.name.length > 30 ).length ? false : true
                }
            },
            select: {}
        },
    };
</script>