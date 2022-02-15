<template>
    <div class="fixed bottom-0 left-0 z-[1001] inline-block border w-full bg-white flex py-6 px-8 md:sticky md:top-36 md:pb-12 md:mt-16 md:rounded-lg md:flex-col md:shadow-custom-1 md:mb-16">
        <div 
            v-if="!ticketsVisible"
            class="inline-block w-8/12 md:w-full">
            <h3>{{ event.price_range }}</h3>
            <p 
                class="underline cursor-pointer text-xl text-gray-500"
                @click="ticketsVisible =! ticketsVisible">
                Show ticket details
            </p>
        </div>

        <div 
            v-if="ticketsVisible"
            class="w-full absolute inline-block bg-white bottom-0 left-0 border-t md:relative md:rounded-2xl md:border">
            <div class="p-4 border-b flex items-center justify-between">
                <button 
                    class="border-none"
                    @click="ticketsVisible =! ticketsVisible">
                    <svg class="w-10 h-10">
                        <use :xlink:href="`/storage/website-files/icons.svg#ri-close-line`" />
                    </svg>
                </button>
                <h4 class="font-semibold">Ticket Details</h4>
            </div>
            <div
                class="py-8 px-4 border-b last:border-none"
                v-for="ticket in tickets" 
                :key="ticket.name">
                <div class="flex justify-between">
                    <h4 class="text-2xl"> {{ ticket.name }} </h4>
                    <p 
                        v-if="ticket.type == 'f'"
                        class="text-2xl">Free</p>
                    <p 
                        v-else-if="ticket.type == 'p'"
                        class="text-2xl">Pay what you can</p>
                    <p 
                        v-else
                        class="text-2xl"> {{ ticket.ticket_price == 0.00 ? 'Free' : `${ticket.currency} ${ticket.ticket_price}` }} </p>
                </div>
                <div>
                    <p class="text-xl text-gray-500"> {{ ticket.description }} </p>
                </div>
            </div>
        </div>
        <div 
            :class="{visible: datesVisible}"
            class="es__tickets--background" />
        <template v-if="!mobile">
            <div 
                v-click-outside="hide"
                class="w-full mt-12 relative">
                <template v-if="event.showtype == 's' || event.showtype == 'l'">
                    <button 
                        @click="showDates"
                        class="mb-4 z-50 flex flex-col relative w-full rounded-2xl p-4 border text-left bg-white hover:bg-black hover:text-white">
                        <span class="text-2xl font-medium">{{ showDateRange }}</span>
                        <span 
                            v-if="remaining && remaining.length > 1 ? remaining.length : ''" 
                            class="text-xl">{{ remaining.length }} show dates remaining</span>
                        <span 
                            v-else-if="remaining && remaining.length == 1 ? remaining.length : ''" 
                            class="text-xl">{{ remaining.length }} date remaining</span>
                        <span 
                            v-else 
                            class="text-xl">no dates remaining</span>
                        <svg class="w-12 h-12 right-8 top-8 absolute">
                            <use v-if="!datesVisible" :xlink:href="`/storage/website-files/icons.svg#ri-arrow-down-s-line`" />
                            <use v-else :xlink:href="`/storage/website-files/icons.svg#ri-close-line`" />
                        </svg>
                    </button>
                    <template v-if="datesVisible">
                        <div class="absolute border shadow-custom-1 bg-white px-8 pt-36 pb-8 max-h-[calc(100vh-20rem)] overflow-y-scroll overflow-x-hidden rounded-2xl top-[-2rem] right-[-2rem] shadow-hidden">
                            <flat-pickr
                                v-model="dates"
                                :config="config"                                  
                                class="form-control"
                                placeholder="Select date"
                                ref="datePicker"             
                                name="dates" />
                            <div class="overflow-hidden">
                                <ShowMore
                                    :text="event.show_times"
                                    :limit="20" />
                            </div>
                        </div>
                    </template>
                </template>

                <template v-if="event.showtype == 'o'">
                    <button 
                        @click="showDates"
                        class="mb-4 z-50 flex flex-col relative w-full rounded-2xl p-4 border text-left bg-white hover:bg-slate-200">
                        <h3>Weekly</h3>  
                        <div class="flex gap-1">
                            <p v-if="event.show_on_going.mon"><b>M</b></p>
                            <p v-else>M</p>
                            <p v-if="event.show_on_going.tue"><b>T</b></p>
                            <p v-else>T</p>
                            <p v-if="event.show_on_going.wed"><b>W</b></p>
                            <p v-else>W</p>
                            <p v-if="event.show_on_going.thu"><b>T</b></p>
                            <p v-else>T</p>
                            <p v-if="event.show_on_going.fri"><b>F</b></p>
                            <p v-else>F</p>
                            <p v-if="event.show_on_going.sat"><b>S</b></p>
                            <p v-else>S</p>
                            <p v-if="event.show_on_going.sun"><b>S</b></p>
                            <p v-else>S</p>
                        </div>   
                        <svg class="w-12 h-12 right-8 top-8 z-100 absolute">
                            <use v-if="!datesVisible" :xlink:href="`/storage/website-files/icons.svg#ri-arrow-down-s-line`" />
                            <use v-else :xlink:href="`/storage/website-files/icons.svg#ri-close-line`" />
                        </svg>
                    </button>
                    <template v-if="datesVisible">
                        <div class="absolute border shadow-custom-1 bg-white px-8 pt-36 pb-8 max-h-[calc(100vh-20rem)] overflow-y-scroll overflow-x-hidden rounded-2xl top-[-2rem] right-[-2rem] shadow-hidden">
                            <flat-pickr
                                v-model="dates"
                                :config="config"                                  
                                class="form-control"
                                placeholder="Select date"
                                ref="datePicker"             
                                name="dates" />
                            <div class="es__dates--description">
                                <ShowMore 
                                    :text="event.show_times"
                                    :limit="20" />
                            </div>
                        </div>
                    </template>
                </template>

                <template v-if="event.showtype == 'a'">
                    <div class="flex flex-col relative w-full rounded-2xl p-4 border text-left">
                        <h3 class="text-2xl">Available Anytime</h3>
                        <div class="overflow-hidden">
                            <ShowMore 
                                :body-class="`text-xl text-gray-500`"
                                :text="event.show_times"
                                :limit="20" />
                        </div>
                    </div>
                </template>
            </div>
        </template>
        

        <div class="w-5/12 inline-block align-bottom md:mt-12 md:w-full">
            <a 
                :href="eventUrl"
                @click="storeClick"
                rel="noreferrer noopener" 
                target="_blank">
                <button class="font-medium py-6 px-4 rounded-2xl w-full border-none text-white float-right bg-gradient-to-r from-button-red-1 via-button-red-2 to-button-red-3 md:px-20 hover:from-button-red-2 hover:via-button-red-3 hover:to-button-red-1">
                    <span v-if="remaining && remaining.length">{{ event.call_to_action ? event.call_to_action : 'Get Tickets' }}</span>
                    <span v-else>View Event</span>
                </button>
            </a>
        </div>
    </div>
</template>

<script>
    import ShowMore  from '../../../components/ShowMore.vue'
    import flatPickr from 'vue-flatpickr-component'
    import clickOutside from '../../../Directives/clickOutside'
    export default {

        props: [ 'event', 'tickets', 'mobile' ],

        components: { ShowMore, flatPickr },

        computed: {
            eventUrl() {
                if (this.event.ticketUrl) {return this.event.ticketUrl}
                if (this.event.websiteUrl) {return this.event.websiteUrl}
                return this.event.organizer.website;
            },
            showDateRange() {
                if (this.event.shows.length > 1) {
                    return `${this.cleanDate(this.event.shows[this.event.shows.length-1].date)} - ${this.cleanDate(this.event.shows[0].date)}`
                }
                return this.cleanDate(this.event.shows[0].date)
            }
        },

        data() {
            return {
                hover: null,
                visible: false,
                config: this.initializeCalendarObject(),
                dates: [],
                week: this.event ? this.event.show_on_going : '',
                remaining: [],
                ticketsVisible: false,
                datesVisible: false,
            }
        },

        methods: {
            showDates() {
                this.ticketsVisible = false;
                this.datesVisible =! this.datesVisible;
            },
            storeClick() {
                axios.post('/track/event/click', {event: this.event.id});
            },
            initializeCalendarObject() { 
                return {
                    mode: "multiple",
                    inline: true,
                    showMonths: 2,
                    dateFormat: 'Y-m-d H:i:s',
                    disable: [],
                }
            },
            hide() {
                this.datesVisible = false;
            },
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
            cleanDate(data) {
                return this.$dayjs(data).format("MMM D, YYYY");
            },
        },
        mounted() {
            this.getDates();
        },

        watch: {
            datesVisible() {
                this.datesVisible ? this.$nextTick(() => { this.$refs.datePicker.fp.jumpToDate(new Date()) }) : '';
            }
        }
    }
</script>