<template>
    <div class="event-create__title">
        <template v-if="newEvent">
            <vue-new-beginner />
        </template>
        <section 
            class="event-create" 
            v-else>
            <div class="title">
                <h2>Title</h2>
            </div>
            <div 
                @mouseover="showResubmit=true" 
                v-if="approved" 
                class="field">
                <label>Title</label>
                <p class="create-titlename">
                    {{ title.name }}
                </p>
                <button 
                    class="editTitle" 
                    v-if="showResubmit" 
                    @click.prevent="modal = 'changeEventName'">
                    Edit
                </button>
            </div>
            <div 
                v-else 
                class="field">
                <label>Project name</label>
                <input
                    type="text" 
                    v-model="title.name" 
                    placeholder=" "
                    :class="{ active: active == 'name','error': $v.title.name.$error }"
                    @input="clearInput"
                    @click="active = 'name'"
                    @blur="active = 'null'">
                <div v-if="$v.title.name.$error" class="validation-error">
                    <p class="error" v-if="!$v.title.name.required">Please add a title.</p>
                    <p class="error" v-if="!$v.title.name.maxLength">The title is too long.</p>
                </div>
            </div>
            <div class="field">
                <label>Project tag line (required)</label>
                <input 
                    type="text" 
                    v-model="title.tagLine"
                    :class="{ active: active == 'tag','error': $v.title.tagLine.$error }"
                    @input="$v.title.tagLine.$touch()"
                    @click="active = 'tag'"
                    @blur="active = 'null'"
                    placeholder="Quick blurb describing the event to get the audience hooked!">
                <div v-if="$v.title.tagLine.$error" class="validation-error">
                    <p class="error" v-if="!$v.title.tagLine.required">Please add a tag line.</p>
                    <p class="error" v-if="!$v.title.tagLine.maxLength">The tag line is too long.</p>
                </div>
            </div>
        </section>
        <Submit 
            :newsubmission="newEvent"
            @submit="onSubmit"
            @newevent="acceptNewEvent"
            :disabled="disabled" 
            previous="exit"
            next="location" 
            :event="event" />
        <VueModalAccept 
            @onSubmit="acceptDuplicateName"
            :accept="true"
            @close="clearInput()"
            v-if="serverErrors.errors && serverErrors.errors.name">
            <h3>Warning</h3>
            <p>An event named {{ title.name }} already exists. Please review the live listing to ensure you are not creating a duplicate event.
                <a 
                    style="color:#3a3aff" 
                    target="_blank" 
                    :href="`/events/${serverErrors.errors.name}`">View Event</a>
            </p>
        </VueModalAccept>
        <VueModalForm 
            v-if="modal === 'changeEventName'"
            @onSubmit="changeTitle"
            textarea="true"
            input="true"
            textarea-placeholder="Reason for title change"
            input-placeholder="New event name"
            @close="modal = null">
            <h3>Change Event Title: {{ event.name }}</h3>
            <p>Please enter the new name for your event and add a reason for requesting this change. Changes may take up to 3 days to be approved. <br> Please note: events on Everything Immersive may not share the same name. If you are remounting a show, please edit the existing event and add new dates/times..</p>
        </VueModalForm>
        <transition name="slide-fade">
            <div v-if="titleUpdated" class="updated-notifcation">
                <p>Your title change request has been submitted.</p>
            </div>
        </transition>
        <transition name="slide-fade">
            <div v-if="updated" class="updated-notifcation">
                <p>Your event has been updated.</p>
            </div>
        </transition>
    </div>
</template>

<script>
    import formValidationMixin from '../../mixins/form-validation-mixin'
    import { required, maxLength } from 'vuelidate/lib/validators';
    import Submit  from './components/submit-buttons.vue'
    import VueNewBeginner  from './components/vue-title-beginner.vue'
    import VueModalForm from '../../components/modals/Vue-Modal-Form'
    import VueModalAccept from '../../components/modals/Vue-Modal-Accept'

    export default {

        mixins: [formValidationMixin],

        props: ['event', 'newsubmission'],

        components: { Submit, VueNewBeginner, VueModalForm, VueModalAccept },

        computed: {
            endpoint() {
                return `/create/${this.event.slug}/title`
            },
        },

        data() {
            return {
                title: this.initializeTitleObject(),
                active: null,
                disabled:false,
                showResubmit: false,
                modal: '',
                approved: this.event.status == 'p' || this.event.status == 'e' ? true : false,
                serverErrors: '',
                updated: false,
                titleUpdated: false,
                newEvent: this.newsubmission,
                creationPage: 1,
            }
        },

        methods: {
            onLoad() {
                axios.get(this.onFetch('title'))
                .then(res => {
                    res.data.name ? this.title.name = res.data.name : null;
                    res.data.tag_line ? this.title.tagLine = res.data.tag_line : null;
                });
            },

            async onSubmit(value) {
                if ( this.checkForChanges(value) ) { return this.onForward(value) }
                if ( this.checkVuelidate() ) { return }
                await axios.patch( this.endpoint, this.title )
                .then( res => {
                    value == 'save' ? this.save() : this.onForward( value )
                })
                .catch( error => {
                    this.disabled = false;
                    this.serverErrors = error.response.data;
                })
            },
            async changeTitle(value) {
                await axios.patch( `/create/${this.event.slug}/change-title`, value );
                this.modal = '';
                this.titleUpdated = true;
                setTimeout(() => this.titleUpdated = false, 3000);
            },
            clearInput() {
                this.serverErrors = {};
                this.$v.title.name.$touch();
            },
            acceptNewEvent() {
                this.newEvent = false;
            },
            acceptDuplicateName() {
                this.title.accept_duplicate_name = true;
                this.clearInput()
            },
            initializeTitleObject() {
                return {
                    name: this.event.name ? this.event.name : '',
                    tagLine: this.event.tag_line ? this.event.tag_line : '',
                    accept_duplicate_name: false,
                }
            },
        },

        created() {
            this.onLoad();
        },

        watch: {
            '$store.state.navurl'() {
                this.onSubmit(this.$store.state.navurl.page)
            }
        },

        validations: {
            title: {
                name: {
                    required,
                    maxLength: maxLength(100),
                },
                tagLine: {
                    required,
                    maxLength: maxLength(100)
                },
            },
        },
    };

</script>