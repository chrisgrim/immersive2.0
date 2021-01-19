<template>
    <div class="show-content">
        <EventHeader 
            :user="user"
            :event="event" />
        <div class="es__body">
            <div class="es__body--left">
                <EventAbout 
                    :remaining="remaining"
                    :event="event" />
                <EventStaffpick
                    v-if="event.staffpick"
                    :event="event" />
                <EventDates 
                    v-if="isMobile"
                    :event="event" />
                <EventLocation :event="event" />
                <EventDetails :event="event" />
            </div>
            <div class="es__body--right">
                <div class="es__quickbuy--body">
                    <EventQuickBuy
                        :tickets="tickets"
                        :event="event" />
                </div>
            </div>
        </div>
        <div class="es__widebody">
            <div class="es__line" />
            <EventReviews :event="event" />
            <EventOrganizer :event="event" />
            <EventRatings 
                :user="user"
                :event="event" />
        </div>
    </div>
</template>

<script>
    import {LMap, LTileLayer, LMarker, LPopup} from 'vue2-leaflet'
    import ShowMore  from './components/show-more.vue'
    import EventHeader  from './show/event-show-header.vue'
    import EventDetails  from './show/event-show-details.vue'
    import EventAbout  from './show/event-show-about.vue'
    import EventTickets  from './show/event-show-tickets.vue'
    import EventDates  from './show/event-show-dates.vue'
    import EventOrganizer  from './show/event-show-organizer.vue'
    import EventLocation  from './show/event-show-location.vue'
    import EventStaffpick  from './show/event-show-staffpick.vue'
    import EventReviews  from './show/event-show-reviews.vue'
    import EventQuickBuy  from './show/event-show-quickbuy.vue'
    import EventRatings  from './show/event-show-ratings.vue'

    export default {

        props: ['loadevent', 'user', 'tickets'],

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
    };
</script>