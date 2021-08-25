<template>
    <div class="es__dates element">
        <h2>Show Dates</h2>
        <template v-if="event.showtype == 's' || event.showtype == 'l'">
            <button 
                @click="datesVisible =! datesVisible"
                class="es__dates-button subtext">
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
</template>

<script>
    import IconSvg from '../../../components/Svg-icon'
    import ShowMore  from '../components/show-more.vue'
    import flatPickr from 'vue-flatpickr-component'
    export default {

        props: [ 'event', ],

        components: { ShowMore, flatPickr, IconSvg },

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