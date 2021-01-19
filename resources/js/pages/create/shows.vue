<template>
    <div class="event-create__shows">
        <section class="">
            <div class="listing-details-block">
                <div class="title">
                    <h2>Dates and Times</h2>
                </div>
                <input 
                    style="opacity:0;position:absolute;top:0;" 
                    autofocus>
                <div class="show-type-selection">
                    <div class="field">
                        <label> Select all show dates</label>
                        <v-select 
                            v-model="showType"
                            label="name"
                            :options="showTypeOptions" 
                            :clearable="false"
                            placeholder="Show hours type"
                            @search:blur="active = null"
                            @search:focus="checkStatus"
                            @input="changeShowType"
                            :class="{ active: active == 'type' }">
                            <template class="event__select-shows" #option="{ name, description }">
                                <h3>{{ name }}</h3>
                                <em>{{ description }}</em>
                            </template>
                        </v-select>
                    </div>
                </div>
                <template v-if="showType.id === 1">
                    <vue-limited-show-dates
                        :event="event"
                        :resubmit="resubmit"
                        :change-type="changeType"
                        :timezones="timezones" />
                </template>
                <template v-if="showType.id === 2">
                    <vue-limited-run
                        :event="event"
                        :resubmit="resubmit"
                        :change-type="changeType"
                        :timezones="timezones" />
                </template>
                <template v-if="showType.id === 3">
                    <vue-open-ended 
                        :event="event"
                        :resubmit="resubmit"
                        :change-type="changeType"
                        :timezones="timezones" />
                </template>
                <template v-if="showType.id === 4">
                    <vue-everyday 
                        :event="event"
                        :resubmit="resubmit"
                        :change-type="changeType"
                        :timezones="timezones" />
                </template>     
            </div>
            <div class="create-button__back">
                <button 
                    v-if="!showType.name"
                    class="create" 
                    @click.prevent="onBack('category')"> 
                    Back
                </button>
            </div>
        </section>
        
        <transition name="slide-fade">
            <div v-if="updated" class="updated-notifcation">
                <p>Your event has been updated.</p>
            </div>
        </transition>
        <modal v-if="modal" @close="modal = false">
            <div slot="header">
                <div class="circle del">
                    <p>?</p>
                </div>
            </div>
            <div slot="body"> 
                <h3>Changing the type of dates?</h3>
                <p>Changing the show run type will require the event to be reapproved.</p>
            </div>
            <div slot="footer">
                <button class="btn del" @click="onResubmit()">Change</button>
            </div>
        </modal>
    </div>
</template>

<script>
    import formValidationMixin from '../../mixins/form-validation-mixin'
    import VueLimitedRun  from './components/vue-limited-run.vue'
    import VueLimitedShowDates from './components/vue-limited-show-dates.vue'
    import VueOpenEnded  from './components/vue-open-ended.vue'
    import VueEveryday  from './components/vue-everyday.vue'


export default {
    props: [ 'event', 'timezones' ],

    mixins: [ formValidationMixin ],

    components: { VueLimitedRun, VueLimitedShowDates, VueOpenEnded, VueEveryday },

    computed: {
        published() {
            return this.event.status == 'p' || this.event.status == 'e' ? true : false;
        },
    },

    data() {
        return {
            showType: '',
            active: null,
            modal: false,
            showTypeOptions: [
                {   id: 1, name: 'Select Dates (Specific Dates)', description:'Your show has specific performance dates.'},
                {   id: 2, name: 'Limited Run (A range of dates, by day of week)', description:'Your show has an opening date and a closing date, and may have dark days each week.'}, 
                {   id: 3, name: 'Open Ended (Weekly)', description:'Your show recurs regularly and has no end date in the next 6 months, and may have dark days each week.'}, 
                {   id: 4, name: 'Ongoing', description:'Your show has no closing date and is available to do by audience members 24/7, with no dark dates. '}],
            updated: false,
            resubmit: false,
            changeType: false,
            creationPage: 4,
        }
    },

    methods: {
        onLoad() {
            if (this.event.showtype == 's') { return this.showType = this.showTypeOptions[0]}
            if (this.event.showtype == 'l') { return this.showType = this.showTypeOptions[1]}
            if (this.event.showtype == 'o') { return this.showType = this.showTypeOptions[2]}
            if (this.event.showtype == 'a') { return this.showType = this.showTypeOptions[3]}
        },

        changeShowType() {
            this.changeType = true
        },

        checkStatus() {
            if ((this.event.status == 'p' || this.event.status == 'e') && this.resubmit == false) this.modal = true
        },

        onResubmit() {
            this.resubmit = true;
            this.approved = false;
            this.modal = false;
        },
    },

    mounted() {
        this.onLoad();
        this.disabled = false;
    },
}  
</script>