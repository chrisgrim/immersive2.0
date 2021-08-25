<template>
    <div class="show-content">
        <EventHeader 
            :user="user"
            :event="loadevent" />
        <div class="lay-b">
            <div class="lay-b__wrapper">
                <div class="lay-b__left">
                    <EventAbout :event="loadevent" />
                    <EventDates 
                        v-if="isMobile"
                        :event="loadevent" />
                    <EventStaffpick
                        v-if="loadevent.staffpick"
                        :event="loadevent" />
                    <EventDetails :event="loadevent" />
                </div>
                <div class="lay-b__right">
                    <div class="es__quickbuy--body">
                        <EventQuickBuy
                            :tickets="tickets"
                            :event="loadevent" />
                    </div>
                </div>
            </div>
        </div>
        <div class="lay-b">
            <div class="es__line" />
            <EventReviews :event="loadevent" />
            <EventLocation :event="loadevent" />
            <EventOrganizer :event="loadevent" />
            <!--
            <EventRatings 
                :user="user"
                :event="loadevent" />
            -->
        </div>
    </div>
</template>

<script>
    import EventHeader  from './show/event-show-header.vue'
    import EventDetails  from './show/event-show-details.vue'
    import EventAbout  from './show/event-show-about.vue'
    import EventDates  from './show/event-show-dates.vue'
    import EventOrganizer  from './show/event-show-organizer.vue'
    import EventLocation  from './show/event-show-location.vue'
    import EventStaffpick  from './show/event-show-staffpick.vue'
    import EventReviews  from './show/event-show-reviews.vue'
    import EventQuickBuy  from './show/event-show-quickbuy.vue'
    import EventRatings  from './show/event-show-ratings.vue'

    export default {

        props: ['loadevent', 'user', 'tickets'],

        components: { EventHeader, EventAbout, EventDetails, EventDates, EventOrganizer, EventLocation, EventStaffpick, EventReviews, EventQuickBuy, EventRatings },

        data() {
            return {
                isMobile: window.innerWidth < 768 ? true : false,
            }
        },

        methods: {

        },
    };
</script>