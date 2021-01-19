<template>
    <div class="limited-show-dates">
        <section class="event-enter-showdates">
            <div class="field limited__calender">
                <label> Select the first day your weekly shows begin</label>
                <div class="limited-run__calendar">
                    <flat-pickr
                        v-model="startDate"
                        :config="embargoCalendarConfig"
                        ref="datePicker"                                              
                        class="form-control"
                        @on-value-update="addDate" 
                        :events="eventsToBeEmitted"
                        placeholder="Select date"    
                        name="dates" />
                </div>
                <div v-if="$v.limit.$error" class="validation-error">
                    <p class="error" v-if="!$v.limit.ifLimit">Please select a start and end date</p>
                    <p class="error" v-if="!$v.limit.correctRange">The start date must be before the end date</p>
                </div>
            </div>
            <div class="field limited__calender">
                <label> Select the last day your weekly shows ends</label>
                <div class="limited-run__calendar">
                    <flat-pickr
                        v-model="endDate"
                        :config="embargoCalendarConfig"
                        ref="datePicker"                                              
                        class="form-control"
                        @on-value-update="addDate" 
                        :events="eventsToBeEmitted"
                        placeholder="Select date"    
                        name="dates" />
                </div>
                <div v-if="$v.limit.$error" class="validation-error">
                    <p class="error" v-if="!$v.limit.ifLimit">Please select a start and end date</p>
                    <p class="error" v-if="!$v.limit.correctRange">The start date must be before the end date</p>
                </div>
            </div>
            <div class="field ongoing-show-dates">
                <label> Select show days</label>
                <div class="week-calendar grid">
                    <div
                        class="week-calendar__day" 
                        :class="{ active: week.mon }" 
                        @click="addWeekDay('mon')">
                        <h4>Mon</h4>
                    </div>
                    <div 
                        class="week-calendar__day"
                        :class="{ active: week.tue }" 
                        @click="addWeekDay('tue')">
                        <h4>Tue</h4>
                    </div>
                    <div 
                        class="week-calendar__day" 
                        :class="{ active: week.wed }" 
                        @click="addWeekDay('wed')">
                        <h4>Wed</h4>
                    </div>
                    <div 
                        class="week-calendar__day" 
                        :class="{ active: week.thu }" 
                        @click="addWeekDay('thu')">
                        <h4>Thu</h4>
                    </div>
                    <div 
                        class="week-calendar__day"
                        :class="{ active: week.fri }" 
                        @click="addWeekDay('fri')">
                        <h4>Fri</h4>
                    </div>
                    <div 
                        class="week-calendar__day"
                        :class="{ active: week.sat }"
                        @click="addWeekDay('sat')">
                        <h4>Sat</h4>
                    </div>
                    <div 
                        class="week-calendar__day"
                        :class="{ active: week.sun }"
                        @click="addWeekDay('sun')">
                        <h4>Sun</h4>
                    </div>
                </div>
                <div v-if="$v.week.$error" class="validation-error">
                    <p class="error" v-if="!$v.week.ifWeekly">Please select at least one day</p>
                    <p class="error" v-if="!$v.week.serverFailed">The weekdays you have selected do not exists in the date range above.</p>
                </div>
            </div>
        </section>
        <section class="ongoing-show-dates">
            <div class="field">
                <label> Show Timezone </label>
                <v-select 
                    v-model="timezone"
                    label="description"
                    :options="timezones"
                    :clearable="false"
                    placeholder="Select timezone"
                    @search:blur="active = null"
                    @search:focus="active = 'timezone'"
                    @input="$v.timezone.$touch"
                    :class="{ active: active == 'timezone','error': $v.timezone.$error }">
                    <template #option="{ description }">
                        <p>{{ description }}</p>
                    </template>
                </v-select>
                <div v-if="$v.timezone.$error" class="validation-error">
                    <p class="error" v-if="!$v.timezone.required">Please include timezone of show</p>
                </div>
            </div>
        </section>
        <section class="event-enter-showtimes ongoing-show-dates">
            <div class="field">
                <label> Show Times</label>
                <textarea 
                    v-model="showTimes" 
                    class="create-input area"
                    :class="{ active: active == 'times','error': $v.showTimes.$error }"
                    rows="8" 
                    :placeholder="placeholdero" 
                    required
                    @click="active = 'times'"
                    @blur="active = null"
                    @input="$v.showTimes.$touch"
                    autofocus />
                <div v-if="$v.showTimes.$error" class="validation-error">
                    <p class="error" v-if="!$v.showTimes.required">Please give a brief description of show times</p>
                    <p class="error" v-if="!$v.showTimes.maxLength">Show time is too long</p>
                </div>
            </div>
        </section>
        <section>
            <div class="field">
                <label> Does the event have a specific embargo date? <br> (i.e. The date you would like it to first appear on EI) </label>
                <div id="cover">
                    <input 
                        @input="$v.showEmbargoDate.$touch" 
                        v-model="showEmbargoDate" 
                        type="checkbox" 
                        id="checkbox">
                    <div id="bar" />
                    <div id="knob">
                        <p v-if="showEmbargoDate">
                            Yes
                        </p>
                        <p v-else>
                            No
                        </p>
                    </div>
                </div>
                <div v-if="showEmbargoDate">
                    <div class="embargo-calendar">
                        <flat-pickr
                            v-model="embargoDate"
                            :config="embargoCalendarConfig"
                            ref="datePicker"
                            @on-value-update="$v.showEmbargoDate.$touch" 
                            :events="eventsToBeEmitted"                                        
                            class="form-control"
                            placeholder="Select date"               
                            name="dates" />
                        <div v-if="$v.showEmbargoDate.$error" class="validation-error">
                            <p class="error" v-if="!$v.showEmbargoDate.isRequired">Please select embargo date</p>
                            <p class="error" v-if="!$v.showEmbargoDate.afterOpening">Embargo Date is after show opening</p>
                        </div>
                    </div>
                </div>
            </div>
        </section>
        <Submit 
            @submit="onSubmit"
            :disabled="disabled" 
            previous="category"
            next="tickets" 
            :event="event" />
        <transition name="slide-fade">
            <div v-if="updated" class="updated-notifcation">
                <p>Your event has been updated.</p>
            </div>
        </transition>
    </div>
</template>

<script>
    import formValidationMixin from '../../../mixins/form-validation-mixin'
    import { required, maxLength } from 'vuelidate/lib/validators'
    import flatPickr from 'vue-flatpickr-component'
    import 'flatpickr/dist/flatpickr.css'
    import Submit  from './submit-buttons.vue'

    export default {

        props: ['event', 'timezones', 'changeType', 'resubmit'],

        mixins: [ formValidationMixin ],

        components: { flatPickr, Submit },

        computed: {
            endpoint() {
                return `/create/${this.event.slug}/shows`
            },

            weeklyOngoing() {
                if (this.week.mon === false) {return true}
                if (this.week.tue === false) {return true}
                if (this.week.wed === false) {return true}
                if (this.week.thu === false) {return true}
                if (this.week.fri === false) {return true}
                if (this.week.sat === false) {return true}
                if (this.week.sun === false) {return true}
                return false;
            },

            submitObject() {
                return {
                    'showtimes': this.showTimes,
                    'embargo_date' : this.showEmbargoDate ? this.embargoDate : null,
                    'week':  this.week,
                    'limited' : true,
                    'start_date': this.startDate ? this.startDate : null,
                    'end_date': this.endDate ? this.endDate : null,
                    'timezone': this.timezone,
                    'resubmit': this.resubmit,
                }
            },
        },

        data() {
            return {
                active: null,
                disabled: false,
                updated: false,
                calendarConfig: this.initializeCalendarObject(),
                eventsToBeEmitted : ['onReady', 'onChange','onValueUpdate'],
                week: this.event.show_on_going ? this.event.show_on_going : this.initializeWeekObject(),
                timezone: this.event.timezone ? this.event.timezone : '',
                showTimes: this.event.shows.length ? this.event.show_times : '',
                showEmbargoDate: this.event.embargo_date ? true : false,
                embargoDate: this.event.embargo_date ? this.event.embargo_date : '',
                embargoCalendarConfig: this.initializeEmbargoCalendarObject(),
                placeholdero: 'Please provide a brief description of weekly show times. For example:' + '\n' + '\n' + '10:00PM shows on Monday & Tuesday.' + '\n' + '12:00PM on Wednesday and Thursday.',
                endDate: this.event.shows.map(a => a.date)[0],
                startDate: this.event.shows.map(a => a.date)[this.event.shows.map(a => a.date).length - 1],
                serverErrors: null,
            }
        },

        methods: {
            async onSubmit(value) {
                if ( this.checkForChanges(value) ) { return this.onForward(value) }
                if (this.checkVuelidate()) { return false }
                await axios.post(this.endpoint, this.submitObject)
                .then( res => value == 'save' ? this.save() : this.onForward(value) )
                .catch( err => { this.onSingleError(err) });
            },

            addWeekDay(day) {
                this.serverErrors = '';
                this.$v.week.$touch();
                this.week[day] = !this.week[day];
            },

            addDate() {
                this.serverErrors = '';
                this.$v.limit.$touch();
            },

            initializeWeekObject() {
                return {
                    mon: false,
                    tue: false,
                    wed: false,
                    thu: false,
                    fri: false,
                    sat: false,
                    sun: false,
                }
            },

            initializeEmbargoCalendarObject() {
                return {
                    minDate: "today",
                    maxDate: new Date().fp_incr(180),
                    mode: "single",
                    inline: true,
                    showMonths: 1,
                    dateFormat: 'Y-m-d H:i:s',     
                }
            },

            onLoad() {},

            initializeCalendarObject() {
                return {
                    maxDate: new Date().fp_incr(180),
                    mode: "multiple",
                    inline: true,
                    showMonths: window.innerWidth < 768 ? 1 : 2,
                    dateFormat: 'Y-m-d H:i:s',     
                }
            },
        },

        mounted() {
            setTimeout(() => this.$refs.datePicker.fp.jumpToDate(new Date()), 100);
            if (this.changeType) { this.$v.week.$touch() }
        },

        watch: {
            '$store.state.navurl'() {
                this.checkForChanges() ? this.onBack(this.$store.state.navurl.page) : this.onSubmit(this.$store.state.navurl.page)
            }
        },

        validations: {
            showTimes: {
                required,
                maxLength: maxLength(1000)
            },
            limit: {
                ifLimit() {
                    return this.startDate && this.endDate  ? true : false
                },
                correctRange() {
                    return this.startDate < this.endDate  ? true : false
                }
            },
            week: {
                ifWeekly() {
                    return this.week.mon == 1 || this.week.tue == 1 || this.week.wed == 1 || this.week.thu == 1 || this.week.fri == 1 || this.week.sat == 1 || this.week.sun == 1  ? true : false
                },
                serverFailed() {
                    return this.serverErrors === 'noOverlap' ? false : true
                }
            },
            timezone: {
                required
            },
            showEmbargoDate: {
                isRequired() {
                    return this.showEmbargoDate ? this.embargoDate ? true : false : true
                },
                afterOpening() {
                    return this.embargoDate > this.startDate ? false : true
                }
            },
        },
    }
</script>