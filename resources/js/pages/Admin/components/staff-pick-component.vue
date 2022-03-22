<template>
    <div class="border border-black shadow-custom-3 rounded-2xl p-4">
        <div>
            <div class="flex min-w-100 gap-4">
                <div class="w-3/12">
                    <p class="text-md mb-4">Event</p>
                    <v-select 
                        v-model="staffPick.event"
                        :options="events"
                        label="name"
                        placeholder="Event"
                        @search="onSearch"
                        @search:focus="onSearch"
                        @input="addShowDates"
                        :class="{'error': $v.staffPick.event.$error}" />
                    <div v-if="$v.staffPick.event.$error" class="validation-error">
                        <p class="error" v-if="!$v.staffPick.event.required">Please select event</p>
                        <p class="error" v-if="!$v.staffPick.event.serverFailed"> This event has been picked already.</p>
                    </div>
                </div>
                <div class="w-3/12">
                    <p class="text-md mb-4">Staff Member</p>
                    <v-select 
                        v-model="staffPick.staff"
                        :options="staff"
                        label="name"
                        placeholder="Select the staff Member"
                        @search:focus="staffPick.staff=null"
                        @input="$v.staffPick.staff.$touch"
                        :class="{'error': $v.staffPick.event.$error}" />
                    <div v-if="$v.staffPick.event.$error" class="validation-error">
                        <p class="error" v-if="!$v.staffPick.event.required">Please select event</p>
                    </div>
                </div>
                <div class="w-1/12 text-center">
                    <p class="text-md mb-4">Rank</p>
                    <select 
                        class="text-xl border rounded-2xl h-20 px-4 py-2 rounded-xl hover:bg-slate-500 hover:text-white"
                        v-model="staffPick.rank">
                        <option 
                            v-for="pick in ['1', '2', '3', '4', '5']"
                            :key="pick">
                            {{ pick }}
                        </option>
                    </select>
                </div>
                <div class="w-5/12">
                    <p class="text-md">Comments</p>
                    <textarea 
                        v-model="staffPick.comments" 
                        class="create-input area" 
                        rows="4"
                        :class="{ error: $v.staffPick.comments.$error }"
                        @input="$v.staffPick.comments.$touch"
                        required 
                        autofocus />
                    <div v-if="$v.staffPick.comments.$error" class="validation-error">
                        <p class="error" v-if="!$v.staffPick.comments.required"> Comment is required </p>
                    </div>
                </div>
            </div>
            <div>
                <div 
                    v-if="staffPick.event"
                    class="flex">
                    <div class="input-hidden w-1/2 text-center">
                        <p class="mb-4"> Front page dates (Must choose a date range from Thur to the next Wed) 
                            <span v-if="datesFormatted[0]">{{ datesFormatted[0] }} to {{ datesFormatted[1] }}</span>
                        </p>
                        <flat-pickr
                            v-model="staffPick.dates"
                            :config="config"
                            @input="disabled=false"                                     
                            placeholder="Select date"               
                            name="dates" />
                        <div v-if="$v.staffPick.dates.$error" class="validation-error">
                            <p class="error" v-if="!$v.staffPick.dates.required"> Please add at least 1 show date </p>
                        </div>
                    </div>       
                    <div class="lockedcalendar input-hidden w-1/2 text-center">
                        <p class="mb-4"> 
                            {{ staffPick.event ? staffPick.event.name : '' }} Show Dates 
                        </p>
                        <flat-pickr
                            v-model="showDates"
                            v-if="staffPick.event && staffPick.event.showtype != 'a'"
                            :config="eventConfig"                                         
                            placeholder="Select date"               
                            name="dates" />
                        <div v-else>
                            Shows are every day for {{ staffPick.event.name }}
                        </div>
                        <div v-if="$v.staffPick.dates.$error" class="validation-error">
                            <p class="error" v-if="!$v.staffPick.dates.required"> Please add at least 1 show date </p>
                        </div>
                    </div>
                </div>
                <button 
                    @click.prevent="onSubmit" 
                    class="font-medium px-4 rounded-2xl w-full border-none text-white bg-gradient-to-r from-button-red-1 via-button-red-2 to-button-red-3 md:px-20 hover:from-button-red-2 hover:via-button-red-3 hover:to-button-red-1">
                    Add Staff Pick
                </button>
            </div>
        </div>
    </div>
</template>

<script>
    
    import formValidationMixin from '../../../mixins/form-validation-mixin'
    import flatPickr from 'vue-flatpickr-component'
    import { required } from 'vuelidate/lib/validators';
    import 'flatpickr/dist/flatpickr.css'

    export default {
        props: ['staff', 'user'],

        mixins: [formValidationMixin],

        components: { flatPickr },

        computed: {
            isDisabled() {
                return this.disabled || !this.staffPick.event || !this.staffPick.dates || !this.staffPick.comments || !this.staffPick.staff;
            }
        }, 

        data() {
            return {
                staffPick: this.initializePickObject(),
                serverErrors: [],
                disabled: false,
                events: [],
                dates: [],
                listDates: [],
                showDates:[],
                submitDates: [],
                datesFormatted: [],
                config: this.initializeDateObject('new'),
                eventConfig: this.initializeDateObject('event'),
            }
        },

        methods: {
            async onSubmit() {
                if (this.checkVuelidate()) { return }
                if (this.checkDates()) { alert('pick date must overlap show date'); return}
                await axios.post('/staffpicks', this.staffPick)
                .then( res => { 
                    this.$emit('update', res.data);
                })
                .catch(err => { this.onErrors(err) });
            },
            async onSearch (query) {
                await axios.get('/api/admin/events/search', { params: { keywords: query } })
                .then( res => { this.events = res.data.data });
            },
            checkDates() {
                if (this.staffPick.event.showtype == 'a' ) {return false;}
                let from = new Date(this.staffPick.submitDates[0]);
                let to = new Date(this.staffPick.submitDates[1]);
                let result = [];
                for (var i = 0; i <  this.staffPick.event.shows.length; i++) {
                    let date = this.staffPick.event.shows[i].date;
                    result.push(new Date(date) >= from && new Date(date) <= to);
                }

                return result.includes(true) ? false : true;
            },
            addShowDates(event) {
                this.$v.staffPick.event.$touch
                this.showDates = event.shows.map(a => a.date);
            },
            clearInfo() {
                this.$v.mobility.mobilities.$touch;
                this.serverErrors = [];
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
                    that.staffPick.submitDates = value.map(date => 
                        this.formatDate(date, "Y-m-d H:i:S"));
                    }
            },
            listDateFunc() {
                const that = this;
                return function(value) {
                    that.listsubmitDates = value.map(date => 
                        this.formatDate(date, "Y-m-d H:i:S"));
                    that.listDatesFormatted = value.map(date => 
                        this.formatDate(date, "M d"));
                    }
            },
            initializePickObject() {
                return {
                    event: null,
                    rank: 5,
                    dates: null,
                    staff: this.staff.find(staff=> staff.id === this.user.id),
                    comments: null,
                    submitDates: [],
                };
            },
        },

        validations: {
            staffPick: {
                event: {
                    required,
                    serverFailed() { return !this.serverErrors.event},
                },
                staff: {
                    required
                },
                dates: {
                    required
                },
                comments: {
                    required
                }
            }
        },
    }

</script>