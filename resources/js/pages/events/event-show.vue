<template>
    <div class="show-content">
        <EventHeader 
            :mobile="mobile"
            :user="user"
            :event="loadevent" />
        <div class="relative w-full m-auto p-0 md:px-12 lg:px-32 lg:max-w-screen-xl">
            <div class="md:flex md:gap-36 border-b">
                <div class="relative inline-block">
                    <EventAbout :event="loadevent" />
                    <EventDates 
                        v-if="mobile"
                        :event="loadevent" />
                    <EventStaffpick
                        v-if="loadevent.staffpick"
                        :event="loadevent" />
                    <EventDetails :event="loadevent" />
                </div>
                <div class="w-full relative inline-block md:w-[37rem] md:min-w-[37rem]">
                    <EventQuickBuy
                        :mobile="mobile"
                        :tickets="tickets"
                        :event="loadevent" />
                </div>
            </div>
        </div>
        <div class="relative w-full m-auto p-0 md:px-12 lg:px-32 lg:max-w-screen-xl">
            <EventReviews :event="loadevent" />
            <EventLocation :event="loadevent" />
            <EventOrganizer :event="loadevent" />
        </div>
    </div>
</template>

<script>
    import EventHeader  from './Show/event-show-header.vue'
    import EventDetails  from './Show/event-show-details.vue'
    import EventAbout  from './Show/event-show-about.vue'
    import EventDates  from './Show/event-show-dates.vue'
    import EventOrganizer  from './Show/event-show-organizer.vue'
    import EventLocation  from './Show/event-show-location.vue'
    import EventStaffpick  from './Show/event-show-staffpick.vue'
    import EventReviews  from './Show/event-show-reviews.vue'
    import EventQuickBuy  from './Show/event-show-quickbuy.vue'

    export default {

        props: ['loadevent', 'user', 'tickets', 'mobile'],

        components: { EventHeader, EventAbout, EventDetails, EventDates, EventOrganizer, EventLocation, EventStaffpick, EventReviews, EventQuickBuy },


    };
</script>