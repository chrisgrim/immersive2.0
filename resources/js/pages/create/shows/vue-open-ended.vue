<template>
    <div class="ongoing-show-dates">
        <section class="event-enter-showdates">
            <div class="field">
                <label> Select show days</label>
                <div class="week-calendar grid">
                    <div
                        class="week-calendar__day" 
                        :class="{ active: datesObject.week.mon }" 
                        @click="addWeekDay('mon')">
                        <h4>Mon</h4>
                    </div>
                    <div 
                        class="week-calendar__day"
                        :class="{ active: datesObject.week.tue }" 
                        @click="addWeekDay('tue')">
                        <h4>Tue</h4>
                    </div>
                    <div 
                        class="week-calendar__day" 
                        :class="{ active: datesObject.week.wed }" 
                        @click="addWeekDay('wed')">
                        <h4>Wed</h4>
                    </div>
                    <div 
                        class="week-calendar__day" 
                        :class="{ active: datesObject.week.thu }" 
                        @click="addWeekDay('thu')">
                        <h4>Thu</h4>
                    </div>
                    <div 
                        class="week-calendar__day"
                        :class="{ active: datesObject.week.fri }" 
                        @click="addWeekDay('fri')">
                        <h4>Fri</h4>
                    </div>
                    <div 
                        class="week-calendar__day"
                        :class="{ active: datesObject.week.sat }"
                        @click="addWeekDay('sat')">
                        <h4>Sat</h4>
                    </div>
                    <div 
                        class="week-calendar__day"
                        :class="{ active: datesObject.week.sun }"
                        @click="addWeekDay('sun')">
                        <h4>Sun</h4>
                    </div>
                </div>
                <div v-if="$v.datesObject.week.$error" class="validation-error">
                    <p class="error" v-if="!$v.datesObject.week.ifWeekly">Please select at least one day</p>
                </div>
            </div>
            <div class="field">
                <label> Select the first day your weekly shows begin</label>
                <div class="embargo-calendar">
                    <flat-pickr
                        v-model="datesObject.startDate"
                        :config="embargoCalendarConfig"
                        ref="datePicker"                                              
                        class="form-control"
                        @on-value-update="onDateChange" 
                        :events="['onValueUpdate']"
                        placeholder="Select date"    
                        name="dates" />
                </div>
            </div>
        </section>
        <section>
            <div class="field">
                <label> Show Timezone </label>
                <v-select 
                    v-model="datesObject.timezone"
                    label="description"
                    :options="timezones"
                    :clearable="false"
                    placeholder="Select timezone"
                    @input="$v.datesObject.timezone.$touch"
                    :class="{ 'error': $v.datesObject.timezone.$error }">
                    <template #option="{ description }">
                        <p>{{ description }}</p>
                    </template>
                </v-select>
                <div v-if="$v.datesObject.timezone.$error" class="validation-error">
                    <p class="error" v-if="!$v.datesObject.timezone.required">Please include timezone of show</p>
                </div>
            </div>
        </section>
        <section class="event-enter-showtimes">
            <div class="field">
                <label> Show Times</label>
                <textarea 
                    v-model="datesObject.showTimes" 
                    class="create-input area"
                    :class="{ 'error': $v.datesObject.showTimes.$error }"
                    rows="8" 
                    :placeholder="'Please provide a brief description of weekly show times. For example:' + '\n' + '\n' + '10:00PM shows on Monday & Tuesday.' + '\n' + '12:00PM on Wednesday and Thursday.'" 
                    required
                    @input="$v.datesObject.showTimes.$touch"
                    autofocus />
                <div v-if="$v.datesObject.showTimes.$error" class="validation-error">
                    <p class="error" v-if="!$v.datesObject.showTimes.required">Please give a brief description of show times</p>
                    <p class="error" v-if="!$v.datesObject.showTimes.maxLength">Show time is too long</p>
                </div>
            </div>
        </section>
        <section>
            <div class="field">
                <label> Does the event have a specific embargo date? <br> (i.e. The date you would like it to first appear on EI) </label>
                <div id="cover">
                    <input 
                        @change="toggleEmbargoDate()" 
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
                            v-model="datesObject.embargoDate"
                            :config="embargoCalendarConfig"
                            ref="datePicker"                                              
                            class="form-control"
                            @on-value-update="$v.showEmbargoDate.$touch" 
                            :events="['onValueUpdate']"
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
            :renew="renew"
            @submit="submitDates"
            @renewDates="renewDates"
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
    import Submit  from '../components/submit-buttons.vue'

    export default {

        props: ['event', 'timezones', 'changeType', 'resubmit'],

        mixins: [ formValidationMixin ],

        components: { flatPickr, Submit },

        data() {
            return {
                active: null,
                disabled: false,
                updated: false,
                datesObject: this.initializeDatesObject(),
                showEmbargoDate: this.event.embargo_date ? true : false,
                embargoCalendarConfig: this.initializeEmbargoCalendarObject(),
            }
        },

        methods: {
            async submitDates(value) {
                if ( this.checkForChanges(value) ) { return this.onForward(value) }
                if (this.checkVuelidate()) { return false }
                await axios.post(`/create/${this.event.slug}/shows`, this.datesObject)
                value == 'save' ? this.save() : this.onForward(value);
            },

            async renewDates(value) {
                this.datesObject.startDate = this.$dayjs().format("YYYY-MM-DD HH:mm:ss")
                if ( this.checkForChanges(value) ) { return this.onForward(value) }
                if (this.checkVuelidate()) { return false }
                await axios.post(`/create/${this.event.slug}/shows`, this.datesObject)
                value == 'save' ? location.reload() : this.onForward(value);
            },

            toggleEmbargoDate() {
                this.$v.showEmbargoDate.$touch();
                if (!this.showEmbargoDate) { this.datesObject.embargoDate = null }
            },

            initializeDatesObject() {
                return {
                    showTimes: this.event.show_times,
                    embargoDate: this.event.embargo_date,
                    week:  this.event.show_on_going ? this.event.show_on_going : this.initializeWeekObject(),
                    onGoing: true,
                    startDate: this.event.shows.map(a => a.date)[this.event.shows.map(a => a.date).length - 1],
                    timezone: this.event.timezone,
                    resubmit: this.resubmit,
                }
            },

            addWeekDay(day) {
                this.$v.datesObject.week.$touch();
                this.datesObject.week[day] = !this.datesObject.week[day];
            },

            onLoad() {
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
        
            onDateChange() {
                this.$v.datesObject.week.$touch()
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
        },

        mounted() {
            setTimeout(() => this.$refs.datePicker.fp.jumpToDate(new Date()), 100);
            if (this.changeType) { this.$v.datesObject.week.$touch() }
        },
        
        watch: {
            '$store.state.navurl'() {
                this.checkForChanges() ? this.onBack(this.$store.state.navurl.page) : this.submitDates(this.$store.state.navurl.page)
            }
        },

        validations: {
            datesObject: {
                showTimes: {
                    required,
                    maxLength: maxLength(1000)
                },
                week: {
                    ifWeekly() {
                        return this.datesObject.week.mon == 1 || this.datesObject.week.tue == 1 || this.datesObject.week.wed == 1 || this.datesObject.week.thu == 1 || this.datesObject.week.fri == 1 || this.datesObject.week.sat == 1 || this.datesObject.week.sun == 1  ? true : false
                    }
                },
                timezone: {
                    required
                },
            },
            showEmbargoDate: {
                isRequired() {
                    return this.showEmbargoDate ? this.datesObject.embargoDate ? true : false : true
                },
                afterOpening() {
                    return this.datesObject.embargoDate > this.datesObject.startDate ? false : true
                }
            },
        },
    }
</script>