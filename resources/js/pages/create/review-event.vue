<template>
    <div class="event-create__review">
        <EventHeader 
            :user="user"
            :event="event" />
        <div class="lay-b">
            <div class="lay-b__wrapper">
                <div class="lay-b__left">
                    <EventAbout :event="event" />
                    <EventDates 
                        v-if="isMobile"
                        :event="event" />
                    <EventDetails :event="event" />
                </div>
                <div class="lay-b__right">
                    <EventQuickBuy
                        :tickets="tickets"
                        :event="loadevent" />
                </div>
            </div>
        </div>
        <div class="lay-b">
            <div class="es__line" />
            <EventLocation :event="loadevent" />
            <EventOrganizer :event="loadevent" />
            <div style="height:10rem" />
        </div>
        <div>
            <div class="create-button__back">
                <button :disabled="disabled" class="create review" @click.prevent="onBack('images')"> Back </button>
            </div>
            <div class="create-button__forward review">
                <button :disabled="disabled" class="create review" @click.prevent="onSubmit()"> Submit </button>
            </div>
        </div>
    </div>
</template>

<script>
    import formValidationMixin from '../../mixins/form-validation-mixin'
    import {LMap, LTileLayer, LMarker, LPopup} from 'vue2-leaflet'
    import ShowMore  from '../events/components/show-more.vue'
    import EventHeader  from '../events/show/event-show-header.vue'
    import EventDetails  from '../events/show/event-show-details.vue'
    import EventAbout  from '../events/show/event-show-about.vue'
    import EventTickets  from '../events/show/event-show-tickets.vue'
    import EventDates  from '../events/show/event-show-dates.vue'
    import EventOrganizer  from '../events/show/event-show-organizer.vue'
    import EventLocation  from '../events/show/event-show-location.vue'
    import EventStaffpick  from '../events/show/event-show-staffpick.vue'
    import EventReviews  from '../events/show/event-show-reviews.vue'
    import EventQuickBuy  from '../events/show/event-show-quickbuy.vue'
    import EventRatings  from '../events/show/event-show-ratings.vue'

    export default {

        props: ['loadevent', 'user', 'tickets'],

        mixins: [ formValidationMixin ],

        components: { LMap, LTileLayer, LMarker, LPopup, ShowMore, EventHeader, EventAbout, EventDetails, EventTickets, EventDates, EventOrganizer, EventLocation, EventStaffpick, EventReviews, EventQuickBuy, EventRatings },

        computed: {
            locationPlaceholder() {
                return this.event.location.postal_code || this.event.location.city ? (this.event.location.home ? this.event.location.home + ' ' : '') 
                + (this.event.location.street ? this.event.location.street + ' | ' : '') 
                + (this.event.location.city ? this.event.location.city + ' | ' : '') 
                + (this.event.location.region ? this.event.location.region + ' | ' : '') 
                + (this.event.location.country ? this.event.location.country : '') 
                : '';
            },

            eventUrl() {
                if (this.loadevent.ticketUrl) {return this.loadevent.ticketUrl}
                if (this.loadevent.websiteUrl) {return this.loadevent.websiteUrl}
                return this.loadevent.organizer.website;
            },

        },

        data() {
            return {
                event: this.loadevent ? this.loadevent : '',
                zoom:13,
                center: this.loadevent.location_latlon,
                url:'https://{s}.basemaps.cartocdn.com/light_all/{z}/{x}/{y}{r}.png',
                attribution:'&copy; <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a> contributors &copy; <a href="https://carto.com/attributions">CARTO</a>',
                week: this.loadevent ? this.loadevent.show_on_going : '',
                showEventClass: 'show-heart-location',
                showEventMobileClass: 'show-heart-mobile-location',
                organizerImage: '',
                dates: [],
                remaining: [],
                lastScrollPosition: 0,
                config: this.initializeCalendarObject(),
                titleFontSize: '',
                hover: null,
                isMobile: window.innerWidth < 768 ? true : false,
                disabled: false,

            }
        },

        methods: {
            getDates() {
                if(this.event.shows) {
                    this.event.shows.forEach(event=> {
                        if (this.$dayjs().subtract(1, 'day').format('YYYY-MM-DD 23:59:00') < event.date) {
                            this.remaining.push(event.date);
                        } else {
                            this.config.disable.push(event.date);
                        }
                        this.dates.push(event.date);
                    });
                }
            },

            onSubmit() {
                this.disabled = true;
                axios.get(`/create/${this.event.slug}/submit`)
                .then(res => {
                   window.location.href = `/create/${this.event.slug}/thankyou`;
                })
                .catch(err => { this.onErrors(err) });
            },

            canUseWebP() {
                let webp = (document.createElement('canvas').toDataURL('image/webp').indexOf('data:image/webp') == 0);
                if (this.loadevent.organizer.thumbImagePath && webp) {
                    return this.organizerImage = `background-image:url('/storage/${this.loadevent.organizer.thumbImagePath}')`;
                }
                if (this.loadevent.organizer.thumbImagePath) {
                    return this.organizerImage = `background-image:url('/storage/${this.loadevent.organizer.thumbImagePath.slice(0, -4)}jpg')`;
                }
            },

            initializeCalendarObject() { 
                return {
                    maxDate: new Date().fp_incr(180),
                    mode: "multiple",
                    inline: true,
                    showMonths: window.innerWidth < 768 ? 1 : 2,
                    dateFormat: 'Y-m-d H:i:s',
                    disable: [],
                }
            },

        },

        mounted() {
            this.getDates();
            this.canUseWebP();
        },

        watch: {
            dates() {
                this.$refs.datePicker ? this.$refs.datePicker.fp.jumpToDate(new Date()) : ''
            },
            
            '$store.state.navurl'() {
                this.onBack(this.$store.state.navurl.page)
            },
        },
    };
</script>