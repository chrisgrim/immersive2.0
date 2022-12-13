<template>
    <div class="everyday-show-dates">
        <section class="event-enter-showtimes">
            <div class="field">
                <label> Show Times</label>
                <textarea 
                    v-model="datesObject.showTimes" 
                    class="create-input area"
                    :class="{ 'error': $v.datesObject.showTimes.$error }"
                    rows="8" 
                    :placeholder="'Please provide a brief description of daily times. For example:' + '\n' + '\n' + 'Show begins everyday at 12PM.' + '\n' + 'Enjoy at any time.'" 
                    required
                    @input="$v.datesObject.showTimes.$touch"
                    autofocus />
                <div v-if="$v.datesObject.showTimes.$error" class="validation-error">
                    <p class="error" v-if="!$v.datesObject.showTimes.required">Please give a brief description of show times</p>
                    <p class="error" v-if="!$v.datesObject.showTimes.maxLength">Show time is too long</p>
                </div>
            </div>
        </section>
        <section class="event-enter-showdates">
            <div class="field" style="margin-top:6rem">
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
                            @on-value-update="$v.showEmbargoDate.$touch" 
                            :events="['onValueUpdate']"                                             
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
            @renewDates="renewDates"
            :disabled="disabled" 
            previous="category"
            next="tickets" 
            :event="editEvent" />
        <transition name="slide-fade">
            <div v-if="updated" class="updated-notifcation">
                <p>Your event has been updated.</p>
            </div>
        </transition>
        <transition name="slide-fade">
            <div v-if="renewed" class="updated-notifcation">
                <p>Your event has been renewed.</p>
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
                editEvent: this.event,
                disabled: false,
                updated: false,
                renewed: false,
                datesObject: this.initializeDatesObject(),
                showEmbargoDate: this.event.embargo_date ? true : false,
                embargoCalendarConfig: this.initializeEmbargoCalendarObject(),
            }
        },

        methods: {
            async onSubmit(value) {
                if ( this.checkForChanges(value) ) { return this.onForward(value) }
                if (this.checkVuelidate()) { return false }
                await axios.post(`/create/${this.event.slug}/shows`, this.datesObject)
                value == 'save' ? this.save() : this.onForward(value);
            },

            async renewDates(value) {
                if ( this.checkForChanges(value) ) { return this.onForward(value) }
                if (this.checkVuelidate()) { return false }
                await axios.post(`/create/${this.event.slug}/shows`, this.datesObject)
                .then( res => {
                    this.editEvent = res.data;
                    this.$emit('updateEvent', res.data)
                })
                this.hasBeenRenewed()
            },

            hasBeenRenewed() {
                this.disabled = false;
                this.renewed = true;
                setTimeout(() => this.renewed = false, 3000);
            },

            toggleEmbargoDate() {
                this.$v.showEmbargoDate.$touch();
                if (!this.showEmbargoDate) { this.datesObject.embargoDate = null }
            },

            initializeDatesObject() {
                return {
                    showTimes: this.event.show_times,
                    embargoDate: this.event.embargo_date,
                    always: true,
                    resubmit: this.resubmit,
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
            
            onLoad() {
            },
        },

        mounted() {
            if (this.changeType) { this.$v.datesObject.showTimes.$touch() }
        },

        watch: {
            '$store.state.navurl'() {
                this.checkForChanges() ? this.onBack(this.$store.state.navurl.page) : this.onSubmit(this.$store.state.navurl.page)
            }
        },

        validations: {
            datesObject: {
                showTimes: {
                    maxLength: maxLength(1000)
                },
            },
            showEmbargoDate: {
                isRequired() {
                    return this.showEmbargoDate ? this.datesObject.embargoDate ? true : false : true
                },
            },
        },
    }
</script>