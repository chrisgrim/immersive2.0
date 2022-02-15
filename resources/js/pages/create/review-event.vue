<template>
    <div class="event-create__review">
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
    import ShowMore  from '../../components/ShowMore.vue'
    import EventHeader  from '../Events/Show/event-show-header.vue'
    import EventDetails  from '../Events/Show/event-show-details.vue'
    import EventAbout  from '../Events/Show/event-show-about.vue'
    import EventDates  from '../Events/Show/event-show-dates.vue'
    import EventOrganizer  from '../Events/Show/event-show-organizer.vue'
    import EventLocation  from '../Events/Show/event-show-location.vue'
    import EventStaffpick  from '../Events/Show/event-show-staffpick.vue'
    import EventReviews  from '../Events/Show/event-show-reviews.vue'
    import EventQuickBuy  from '../Events/Show/event-show-quickbuy.vue'

    export default {

        props: ['loadevent', 'user', 'tickets', 'mobile'],

        mixins: [ formValidationMixin ],

        components: { ShowMore, EventHeader, EventAbout, EventDetails, EventDates, EventOrganizer, EventLocation, EventStaffpick, EventReviews, EventQuickBuy },

        computed: {


            eventUrl() {
                if (this.loadevent.ticketUrl) {return this.loadevent.ticketUrl}
                if (this.loadevent.websiteUrl) {return this.loadevent.websiteUrl}
                return this.loadevent.organizer.website;
            },

        },

        data() {
            return {
                event: this.loadevent ? this.loadevent : '',
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