<template>
    <div class="event-create__description grid">
        <section class="event-create">
            <div class="title">
                <h2>Description</h2>
            </div>
            <div class="field">
                <label class="area"> Describe your event to our readers <span v-if="event.hasLocation"><b>(Please include COVID-19 precaution information)</b></span> </label>
                <textarea 
                    type="text"
                    name="description" 
                    v-model="event.description" 
                    placeholder="eg. Our super scary event will bring your fears to the surface..."
                    :class="{ active: active == 'description','error': $v.event.description.$error }"
                    @input="$v.event.description.$touch"
                    @click="active = 'description'"
                    @blur="active = null" 
                    rows="14" />
                <div v-if="$v.event.description.$error" class="validation-error">
                    <p class="error" v-if="!$v.event.description.required">Must provide a description</p>
                    <p class="error" v-if="!$v.event.description.maxLength">Description is too long</p>
                </div>
            </div>
            <div class="field">
                <label>Event Link (Optional)</label>
                <input 
                    type="text" 
                    v-model="event.websiteUrl"
                    :class="{ active: active == 'website','error': $v.event.websiteUrl.$error }"
                    @click="active = 'website'"
                    @blur="active = null"
                    @input="$v.event.websiteUrl.$touch"
                    placeholder="Link to a page that has more information about your event">
                <div v-if="$v.event.websiteUrl.$error" class="validation-error">
                    <p class="error" v-if="!$v.event.websiteUrl.url">Must be a url (https://...)</p>
                    <p class="error" v-if="!$v.event.websiteUrl.maxLength">Url is too long</p>
                </div>
            </div>
        </section>
        <Submit 
            @submit="onSubmit"
            :disabled="disabled" 
            previous="tickets"
            next="advisories" 
            :event="event" />
        <transition name="slide-fade">
            <div v-if="updated" class="updated-notifcation">
                <p>Your event has been updated.</p>
            </div>
        </transition>
    </div>
</template>

<script>
    import formValidationMixin from '../../mixins/form-validation-mixin'
    import { required, url, maxLength } from 'vuelidate/lib/validators'
    import Submit  from './components/submit-buttons.vue'

	export default {

		props: ['loadevent'],

        components: { Submit },

        mixins: [ formValidationMixin ],

        computed: {
            endpoint() {
                return `/create/${this.event.slug}/description`
            },
        },

		data() {
			return {
                event: this.loadevent ? this.loadevent : null,
                disabled: false,
                serverErrors: [],
                active: null,
                updated: false,
                approved: this.loadevent.status == 'p' || this.loadevent.status == 'e' ? true : false,
                creationPage: 6,
			}
		},

		methods: {
			async onSubmit(value) {
                if ( this.checkForChanges(value) ) { return this.onForward(value) }
                if ( this.checkVuelidate() ) { return }
				await axios.patch(this.endpoint, this.event)
                value == 'save' ? this.save() : this.onForward(value);
			},

            onLoad() {
                axios.get(this.onFetch('description'))
                .then(res => {
                    this.event = res.data
                });
            },
		},

        created() {
            this.onLoad();
            this.disabled = false;
        },

        watch: {
            '$store.state.navurl'() {
                this.checkForChanges() ? this.onBack(this.$store.state.navurl.page) : this.onSubmit(this.$store.state.navurl.page)
            },
        },

        validations: {
            event : {
                description: {
                    required,
                    maxLength: maxLength(5000),
                },
                websiteUrl: {
                    url,
                    maxLength: maxLength(200),
                },
            }
        },
    };



</script>