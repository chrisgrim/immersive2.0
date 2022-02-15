<template>
    <div class="py-12 px-8 border-b border-t border-slate-200">
        <h2 class="text-3xl mb-4">Show Dates</h2>
        <template v-if="hasSpecificDates">
            <button 
                @click="datesVisible =! datesVisible"
                :class="{ 'bg-black div-text-white' : datesVisible }"
                class="mb-4 z-50 flex flex-col relative w-full rounded-2xl p-4 border text-left">
                <h3>{{ showDateRange }}</h3>
                <p 
                    v-if="remaining && remaining.length > 1 ? remaining.length : ''" 
                    class="header__show-info bold">{{ remaining.length }} show dates remaining</p>
                <p 
                    v-else-if="remaining && remaining.length == 1 ? remaining.length : ''" 
                    class="header__show-info bold">{{ remaining.length }} date remaining</p>
                <p 
                    v-else 
                    class="header__show-info bold">no dates remaining</p>
                <svg 
                    :class="{ 'fill-white' : datesVisible }"
                    class="w-12 h-12 right-8 top-8 z-100 absolute">
                    <use v-if="!datesVisible" :xlink:href="`/storage/website-files/icons.svg#ri-arrow-down-s-line`" />
                    <use v-else :xlink:href="`/storage/website-files/icons.svg#ri-close-line`" />
                </svg>
            </button>
            <template v-if="datesVisible">
                <div class="relative m-0 border border-black p-4 rounded-2xl graydates shadow-hidden lockedcalendar">
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

        <template v-if="hasOngoingDates">
            <button 
                @click="datesVisible =! datesVisible"
                :class="{ 'bg-black div-text-white' : datesVisible }"
                class="mb-4 z-50 flex flex-col relative w-full rounded-2xl p-4 border text-left">
                <h3>Week Days</h3>  
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
                <svg 
                    :class="{ 'fill-white' : datesVisible }"
                    class="w-12 h-12 right-8 top-8 z-100 absolute">
                    <use v-if="!datesVisible" :xlink:href="`/storage/website-files/icons.svg#ri-arrow-down-s-line`" />
                    <use v-else :xlink:href="`/storage/website-files/icons.svg#ri-close-line`" />
                </svg>
            </button>
            <template v-if="datesVisible">
                <div class="relative m-0 border border-black p-4 rounded-2xl shadow-hidden">
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

        <template v-if="hasAllDates">
            <div class="flex flex-col relative w-full rounded-2xl p-4 border text-left">
                <h3>Available Anytime</h3>
                <div class="overflow-hidden">
                    <ShowMore 
                        :text="event.show_times"
                        :limit="20" />
                </div>
            </div>
        </template>
    </div>
</template>

<script>
    import ShowMore  from '../../../components/ShowMore.vue'
    import flatPickr from 'vue-flatpickr-component'
    export default {

        props: [ 'event', ],

        components: { ShowMore, flatPickr },

        computed: {
            showDateRange() {
                if (this.event.shows.length > 1) {
                    return `${this.cleanDate(this.event.shows[this.event.shows.length-1].date)} - ${this.cleanDate(this.event.shows[0].date)}`
                }
                return this.cleanDate(this.event.shows[0].date)
            }
        },

        data() {
            return {
                config: this.initializeCalendarObject(),
                dates: [],
                week: this.event ? this.event.show_on_going : '',
                remaining: [],
                visible: false,
                datesVisible: false,
                hasSpecificDates: this.event.showtype == 's' || this.event.showtype == 'l',
                hasOngoingDates: this.event.showtype == 'o',
                hasAllDates: this.event.showtype == 'a'
            }
        },

        methods: {
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
                return this.$dayjs(data).format("MMM D");
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