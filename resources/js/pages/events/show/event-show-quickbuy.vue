<template>
    <section>
        <div 
            v-if="!ticketsVisible"
            class="es__tickets subtext">
            <h3>{{ event.price_range }}</h3>
            <p @click="ticketsVisible =! ticketsVisible">
                <template v-if="tickets.length > 1">
                    Show all prices
                </template>
                <template v-else>
                    Show pricing details
                </template>
            </p>
        </div>

        <div 
            :class="{ visible: ticketsVisible }"
            class="es__tickets--detailed">
            <div class="es__tickets--title">
                <button @click="ticketsVisible =! ticketsVisible">
                    <IconSvg type="delete" />
                </button>
                <h4>Ticket Details</h4>
            </div>
            <div
                class="es__ticket--element"
                v-for="ticket in tickets" 
                :key="ticket.name">
                <div class="es__ticket--name">
                    <h4> {{ ticket.name }} </h4>
                    <template v-if="ticket.type == 'f'">
                        <p>Free</p>
                    </template>
                    <template v-else-if="ticket.type == 'p'">
                        <p>Pay what you can</p>
                    </template>
                    <template v-else>
                        <p> {{ ticket.ticket_price == 0.00 ? 'Free' : `${ticket.currency} ${ticket.ticket_price}` }} </p>
                    </template>
                </div>
                <div class="es__ticket--description">
                    <p> {{ ticket.description }} </p>
                </div>
            </div>
        </div>
        <div 
            :class="{visible: datesVisible}"
            class="es__tickets--background" />
        <div 
            v-if="!isMobile"
            class="es__dates">
            <template v-if="event.showtype == 's' || event.showtype == 'l'">
                <button 
                    @click="showDates"
                    ref="datebutton"
                    class="es__dates-button subtext">
                    <h3>Dates</h3>
                    <p 
                        v-if="remaining && remaining.length > 1 ? remaining.length : ''" 
                        class="header__show-info bold">{{ remaining.length }} show dates remaining</p>
                    <p 
                        v-else-if="remaining && remaining.length == 1 ? remaining.length : ''" 
                        class="header__show-info bold">{{ remaining.length }} date remaining</p>
                    <p 
                        v-else 
                        class="header__show-info bold">no dates remaining</p>
                    <div class="es__dates-button--arrow">
                        <IconSvg 
                            v-if="datesVisible"
                            type="delete" />
                        <IconSvg 
                            v-else
                            type="back" />
                    </div>
                </button>
                <template v-if="datesVisible">
                    <div
                        ref="dates" 
                        class="es__dates--popup">
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

            <template v-if="event.showtype == 'o'">
                <button 
                    @click="datesVisible =! datesVisible"
                    class="es__dates-button subtext">
                    <h3>Week Days</h3>  
                    <div class="es__week--days">
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
                    <div class="es__dates-button--arrow">
                        <IconSvg 
                            v-if="datesVisible"
                            type="delete" />
                        <IconSvg 
                            v-else
                            type="back" />
                    </div>
                </button>
                <template v-if="datesVisible">
                    <div class="es__dates--popup">
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
                <div class="es__dates--body subtext">
                    <h3>Available Anytime</h3>
                    <div class="es__dates--description">
                        <ShowMore 
                            :text="event.show_times"
                            :limit="20" />
                    </div>
                </div>
            </template>
        </div>

        <div class="es__ticket--buy">
            <a 
                :href="eventUrl"
                @click="storeClick"
                rel="noreferrer noopener" 
                target="_blank">
                <button 
                    v-if="remaining && remaining.length"
                    class="event-bottom-bar__button">
                    {{ event.call_to_action ? event.call_to_action : 'Get Tickets' }}
                </button>
                <button 
                    v-else
                    class="event-bottom-bar__button">
                    Head to event
                </button>
            </a>
        </div>
    </section>
</template>

<script>
    import IconSvg from '../../../components/Svg-icon'
    import ShowMore  from '../components/show-more.vue'
    import flatPickr from 'vue-flatpickr-component'
    export default {

        props: [ 'event', 'tickets' ],

        components: { IconSvg, ShowMore, flatPickr },

        computed: {
            eventUrl() {
                if (this.event.ticketUrl) {return this.event.ticketUrl}
                if (this.event.websiteUrl) {return this.event.websiteUrl}
                return this.event.organizer.website;
            },
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
                isMobile: window.innerWidth < 768 ? true : false,
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
                    maxDate: new Date().fp_incr(180),
                    mode: "multiple",
                    inline: true,
                    showMonths: 2,
                    dateFormat: 'Y-m-d H:i:s',
                    disable: [],
                }
            },

            onClickOutside(event) {
                let arr =  this.$refs.dates;
                let arr2 = this.$refs.datebutton;
                if (!arr || !arr2 || arr.contains(event.target) || arr2.contains(event.target )) return;
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
        },

        mounted() {
            setTimeout(() => document.addEventListener("click", this.onClickOutside), 200);
            this.getDates();
        },

        unmounted() {
            document.removeEventListener("click", this.onClickOutside);
        },

        watch: {
            datesVisible() {
                this.datesVisible ? this.$nextTick(() => { this.$refs.datePicker.fp.jumpToDate(new Date()) }) : '';
            }
        }
    }
</script>