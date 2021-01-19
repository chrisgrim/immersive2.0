<template>
    <div class="c-staffpicks__new">
        <div class="c-staffpicks__new--fields">
            <div class="field">
                <label>Event</label>
                <v-select 
                    v-model="event"
                    :options="events"
                    label="name"
                    placeholder="Select the event"
                    @search="onSearch"
                    @search:focus="onSearch"
                    @search:blur="active = null"
                    @input="addShowDates"
                    :class="{ active: active == 'event','error': $v.event.$error}" />
                <div 
                    v-if="$v.event.$error" 
                    class="validation-error">
                    <p 
                        class="error" 
                        v-if="!$v.event.required">
                        Please select event
                    </p>
                    <p 
                        class="error" 
                        v-if="!$v.event.serverFailed">
                        This event has been picked already.
                    </p>
                </div>
            </div>
            <div class="field">
                <label>Rank</label>
                <v-select 
                    v-model="rank"
                    :options="rankOptions"
                    placeholder="Leave blank for default Rank of 5 (1 being most important)"
                    @search:blur="active = null"
                    @search:focus="active = 'rank'"
                    :class="{ active: active == 'rank'}" />
            </div>
            <div class="field">
                <label class="area"> Comments </label>
                <textarea 
                    v-model="comments" 
                    class="create-input area" 
                    rows="8"
                    :class="{ active: active == 'comments','error': $v.comments.$error }"
                    placeholder=" "
                    @click="active = 'comments'"
                    @input="$v.comments.$touch"
                    @blur="active = null"
                    required 
                    autofocus />
                <div 
                    v-if="$v.comments.$error" 
                    class="validation-error">
                    <p 
                        class="error" 
                        v-if="!$v.comments.required">
                        Comment is required
                    </p>
                </div>
            </div>
        </div>
        <div class="c-staffpicks__new--dates">
            <div class="field">
                <label class="area"> Front page dates: 
                    <span v-if="datesFormatted[0]">{{ datesFormatted[0] }} to {{ datesFormatted[1] }}</span>
                </label>
                <flat-pickr
                    v-model="dates"
                    :config="config"                                         
                    placeholder="Select date"               
                    name="dates" />
                <div 
                    v-if="$v.dates.$error" 
                    class="validation-error">
                    <p 
                        class="error" 
                        v-if="!$v.dates.required">
                        Please add at least 1 show date
                    </p>
                </div>
            </div>
        </div>
        <div 
            v-if="event" 
            class="c-staffpicks__new--dates lockedcalendar">
            <div class="field">
                <label class="area"> {{ event ? event.name : '' }} Show Dates
                </label>
                <flat-pickr
                    v-model="showDates"
                    v-if="event && event.showtype != 'a'"
                    :config="eventConfig"                                         
                    placeholder="Select date"               
                    name="dates" />
                <div v-else>
                    Shows are every day for {{ event.name }}
                </div>
                <div 
                    v-if="$v.dates.$error" 
                    class="validation-error">
                    <p 
                        class="error" 
                        v-if="!$v.dates.required">
                        Please add at least 1 show date
                    </p>
                </div>
            </div>
        </div>
        <br>
        <br>
        <button @click.prevent="onSubmit">
            Add staff pick
        </button>
    </div>
</template>

<script>
    import flatPickr from 'vue-flatpickr-component'
    import { required } from 'vuelidate/lib/validators';
    import 'flatpickr/dist/flatpickr.css'
    import _ from 'lodash'
    import formValidationMixin from '../../../mixins/form-validation-mixin'

    export default {

        components: { flatPickr },

        mixins: [ formValidationMixin ],

        computed: {
            submitObject() {
                return {
                    event_id: this.event ? this.event : '',
                    rank: this.rank ? this.rank : '',
                    dates: this.datesSubmit ? this.datesSubmit : '',
                    comments: this.comments ? this.comments : ''
                };
            },
        },

        data() {
            return {
                event: '',
                events: [],
                comments: '',
                dates: [],
                listDates: [],
                showDates:[],
                rank: '',
                active: '',
                rankOptions: ['1', '2', '3', '4', '5'],
                datesSubmit: [],
                datesFormatted: [],
                config: this.initializeDateObject('new'),
                eventConfig: this.initializeDateObject('event'),
            }
        },

        methods: {

            async onSubmit() {
                if (this.checkVuelidate()) { return }
                if (this.checkDates()) {alert('pick date must overlap show date'); return }
                await axios.post('/staffpicks', this.submitObject)
                location.reload()
            },

            onSearch (query) {
                axios.get('/api/admin/events/search', { params: { keywords: query } })
                .then( res => { this.events = res.data.data });
            },

            addShowDates(event) {
                this.$v.event.$touch
                this.showDates = event.shows.map(a => a.date);
            },

            checkDates() {
                if (this.event.showtype == 'a' ) {return false;}
                let from = new Date(this.datesSubmit[0]);
                let to = new Date(this.datesSubmit[1]);

                let result = [];
                for (var i = 0; i <  this.event.shows.length; i++) {
                    let date = this.event.shows[i].date;
                    result.push(new Date(date) >= from && new Date(date) <= to);
                }

                return result.includes(true) ? false : true;
            },

            initializeDateObject(val) {
                return {
                    altFormat:'M d',
                    altInput: true,
                    mode: val == 'event' ? 'multiple' : 'range',
                    inline: val == 'list' ? false : true,
                    showMonths: 1,
                    dateFormat: 'Y-m-d H:i:s',
                    onClose:  val == 'new' ? [this.dateFunc()] : [this.listDateFunc()], 
                }
            },

            dateFunc() {
                const that = this;
                return function(value) {
                    that.datesSubmit = value.map(date => 
                        this.formatDate(date, "Y-m-d H:i:S"));
                    that.datesFormatted = value.map(date => 
                        this.formatDate(date, "M d"));
                    }
            },

            listDateFunc() {
                const that = this;
                return function(value) {
                    that.listDatesSubmit = value.map(date => 
                        this.formatDate(date, "Y-m-d H:i:S"));
                    that.listDatesFormatted = value.map(date => 
                        this.formatDate(date, "M d"));
                    }
            },

            hasServerError(field) {
                return (field && _.has(this, 'serverErrors.' + field) && !_.isEmpty(this.serverErrors[field]));
            },
        },

        validations: {
            event: {
                required,
                serverFailed : function(){ return !this.hasServerError('event_id'); },
            },
            dates: {
                required
            },
            comments: {
                required
            }
        },
    }
</script>