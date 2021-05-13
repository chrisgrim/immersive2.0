<template>
    <div class="specific-show-dates">
        <section class="event-enter-showdates">
            <div class="field">
                <label> Select each individual show date</label>
                <template v-if="mobile">
                    <div class="calendar mobileshow">
                        <flat-pickr
                            v-if="mobile"
                            v-model="datesObject.dates"
                            :config="calendarConfig"
                            ref="datePicker"
                            @on-value-update="onDateChange" 
                            :events="['onValueUpdate']"                                           
                            class="form-control"
                            placeholder="Select date"        
                            name="dates" />
                    </div>
                </template>
                <template v-else>
                    <div class="calendar desktopshow">
                        <flat-pickr
                            v-model="datesObject.dates"
                            :config="calendarConfig"
                            ref="datePicker"                                              
                            class="form-control"
                            @on-value-update="onDateChange" 
                            :events="['onValueUpdate']"
                            placeholder="Select date"   
                            name="dates" />
                    </div>
                </template>
                <div v-if="$v.datesObject.dates.$error" class="validation-error">
                    <p class="error" v-if="!$v.datesObject.dates.required">Please add at least 1 show date</p>
                </div>
                <div>
                    <label v-if="datesObject.dateArray.length">({{ datesObject.dateArray.length }} dates selected)</label>
                    <label v-if="!datesObject.dateArray.length && datesObject.dates.length">({{ datesObject.dates.length }} dates selected)</label>
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
                    :placeholder="'Please provide a brief description of show times. For example:' + '\n' + '\n' + '“Doors at 7, Show at 8.”' + '\n' + '“Two shows an hour from 8-10.”'" 
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
                            v-model="datesObject.embargoDate"
                            :config="embargoCalendarConfig"
                            ref="datePicker"
                            @on-value-update="$v.showEmbargoDate.$touch" 
                            :events="['onValueUpdate']"                                          
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
    import Submit  from '../components/submit-buttons.vue'

    export default {

        props: [ 'event', 'timezones', 'changeType', 'resubmit' ],

        mixins: [ formValidationMixin ],

        components: { flatPickr, Submit },

        data() {
            return {
                datesObject: this.initializeDatesObject(),
                disabled: false,
                updated: false,
                showEmbargoDate: this.event.embargo_date ? true : false,
                calendarConfig: this.initializeCalendarObject(),
                embargoCalendarConfig: this.initializeEmbargoCalendarObject(),
                mobile: window.innerWidth < 768,
            }
        },

        methods: {
            async onSubmit(value) {
                if ( this.checkForChanges(value) ) { return this.onForward(value) }
                if (this.checkVuelidate()) { return false }
                await axios.post(`/create/${this.event.slug}/shows`, this.datesObject)
                value == 'save' ? this.save() : this.onForward(value);
            },

            onDateChange(value) {
                this.$v.datesObject.dates.$touch()
                this.datesObject.dateArray = value.map( date => this.$dayjs(date).format("YYYY-MM-DD HH:mm:ss"))
            },

            initializeDatesObject() {
                return {
                    dates: this.loadDates(),
                    dateArray: this.loadDates(),
                    showTimes: this.event.show_times,
                    embargoDate : this.event.embargo_date,
                    shows: true,
                    timezone: this.event.timezone,
                    resubmit: this.resubmit,
                }
            },

            loadDates() {
                return this.event.shows ? this.event.shows.map(a => a.date) : ''
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

            initializeCalendarObject() {
                return {
                    maxDate: new Date().fp_incr(180),
                    mode: "multiple",
                    inline: true,
                    showMonths: window.innerWidth < 768 ? 1 : 2,
                    dateFormat: 'Y-m-d H:i:s',     
                }
            },

            onLoad() {
            },
        },

        mounted() {
            setTimeout(() => this.$refs.datePicker.fp.jumpToDate(new Date()), 100);
            if (this.changeType) { this.$v.datesObject.dates.$touch() }
        },

        watch: {
            '$store.state.navurl'() {
                this.checkForChanges() ? this.onBack(this.$store.state.navurl.page) : this.onSubmit(this.$store.state.navurl.page)
            }
        },

        validations: {
            datesObject: {
                showTimes: {
                    required,
                    maxLength: maxLength(1000)
                },
                dates: {
                    ifDates() { 
                        return this.datesObject.dates.length ? true : false
                    },
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
                    return this.datesObject.embargoDate > this.datesObject.dateArray.sort()[0] ? false : true
                }
            },
        },
    }
</script>