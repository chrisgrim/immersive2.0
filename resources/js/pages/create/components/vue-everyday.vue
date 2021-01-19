<template>
    <div class="everyday-show-dates">
        <section class="event-enter-showtimes">
            <div class="field">
                <label> Show Times</label>
                <textarea 
                    v-model="showTimes" 
                    class="create-input area"
                    :class="{ active: active == 'times','error': $v.showTimes.$error }"
                    rows="8" 
                    :placeholder="placeholdera" 
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
        <section class="event-enter-showdates">
            <div class="field" style="margin-top:6rem">
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

            submitObject() {
                return {
                    'showtimes': this.showTimes,
                    'embargo_date' : this.showEmbargoDate ? this.embargoDate : null,
                    'always': true,
                    'resubmit': this.resubmit,
                }
            },
        },

        data() {
            return {
                active: null,
                disabled: false,
                updated: false,
                eventsToBeEmitted : ['onReady', 'onChange','onValueUpdate'],
                timezone: this.event.timezone ? this.event.timezone : '',
                showTimes: this.event.shows.length ? this.event.show_times : '',
                showEmbargoDate: this.event.embargo_date ? true : false,
                embargoDate: this.event.embargo_date ? this.event.embargo_date : '',
                embargoCalendarConfig: this.initializeEmbargoCalendarObject(),
                placeholdera: 'Please provide a brief description of daily times. For example:' + '\n' + '\n' + 'Show begins everyday at 12PM.' + '\n' + 'Enjoy at any time.',
            }
        },

        methods: {
            async onSubmit(value) {
                if ( this.checkForChanges(value) ) { return this.onForward(value) }
                if (this.checkVuelidate()) { return false }
                await axios.post(this.endpoint, this.submitObject)
                value == 'save' ? this.save() : this.onForward(value);
            },

            initializeEmbargoCalendarObject() {
                return {
                    minDate: "today",
                    maxDate: new Date().fp_incr(180),
                    mode: "single",
                    inline: true,
                    showMonths: 1,
                    dateFormat: 'Y-m-d H:i:s',
                    eventsToBeEmitted : ['onReady', 'onChange','onValueUpdate'],   
                }
            },

            onLoad() {},
        },

        mounted() {
            if (this.changeType) { this.$v.showTimes.$touch() }
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
            showEmbargoDate: {
                isRequired() {
                    return this.showEmbargoDate ? this.embargoDate ? true : false : true
                },
            },
        },
    }
</script>