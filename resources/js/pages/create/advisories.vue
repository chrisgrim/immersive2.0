<template>
    <div class="event-create__advisories grid">
        <section class="event-create">
            <div class="title">
                <h2>Physical Contact Advisories</h2>
            </div>
            <input 
                style="opacity:0;position:absolute;top:0;" 
                autofocus>
            <div class="field">
                <label class="area">Select physical interaction level with performers</label>
                <v-select 
                    v-model="contactAdvisories"
                    :options="contactAdvisoryOptions"
                    multiple
                    label="level" 
                    placeholder="Choose all that apply"
                    :clearable="false"
                    @search:blur="active = null"
                    @search:focus="active = 'contact'"
                    @input="$v.contactAdvisories.$touch"
                    :class="{ active: active == 'contact','error': $v.contactAdvisories.$error }" />
                <div v-if="$v.contactAdvisories.$error" class="validation-error">
                    <p class="error" v-if="!$v.contactAdvisories.required">Must choose at least one contact level </p>
                </div>
            </div>
        </section>
        <section class="event-create">
            <div class="title">
                <h2>Content Advisories</h2>
            </div>
            <div class="field">
                <label> Is there sexual content? </label>
                <v-select 
                    v-model="advisories.sexual"
                    :options="options"
                    label="name" 
                    placeholder="Choose one"
                    :clearable="false"
                    @search:blur="active = null"
                    @search:focus="active = 'sexual'"
                    @input="$v.advisories.sexual.$touch"
                    :class="{ active: active == 'sexual','error': $v.advisories.sexual.$error }" />
                <div v-if="$v.advisories.sexual.$error" class="validation-error">
                    <p class="error" v-if="!$v.advisories.sexual.required">Required</p>
                </div>
            </div>
            <div v-if="advisories.sexual && advisories.sexual.type">
                <div class="field">
                    <label class="area"> Explain more about the sexual content </label>
                    <textarea 
                        v-model="advisories.sexualDescription" 
                        class="create-input area" 
                        rows="8"
                        :class="{ active: active == 'sexual','error': $v.advisories.sexualDescription.$error }"
                        placeholder=" "
                        @click="active = 'sexual'"
                        @input="$v.advisories.sexualDescription.$touch"
                        @blur="active = null"
                        required 
                        autofocus />
                    <div v-if="$v.advisories.sexualDescription.$error" class="validation-error">
                        <p class="error" v-if="!$v.advisories.sexualDescription.ifSexual">Please describe the sexual content </p>
                        <p class="error" v-if="!$v.advisories.sexualDescription.maxLength">Description is too long </p>
                    </div>
                </div>
            </div>
            <div class="field">
                <label class="area">Include warnings and advisories</label>
                <v-select 
                    v-model="contentAdvisories"
                    :options="contentAdvisoryOptions"
                    multiple
                    taggable
                    label="advisories" 
                    placeholder="Select from list or enter your own" 
                    :clearable="false"
                    @search:blur="active = null"
                    @search:focus="active = 'content'"
                    @input="$v.contentAdvisories.$touch"
                    :class="{ active: active == 'content','error': $v.contentAdvisories.$error }" />
                <div v-if="$v.contentAdvisories.$error" class="validation-error">
                    <p class="error" v-if="!$v.contentAdvisories.required">Must enter a content advisory </p>
                    <p class="error" v-if="!$v.contentAdvisories.maxChar">Advisory character length is too long </p>
                </div>
            </div>
            <div class="field">
                <label>Age restriction</label>
                <v-select 
                    v-model="age"
                    :options="agelimit"
                    label="name" 
                    placeholder="Select the appropriate age group"
                    :clearable="false"
                    @search:blur="active = null"
                    @search:focus="active = 'age'"
                    @input="$v.age.$touch"
                    :class="{ active: active == 'age','error': $v.age.$error }" />
                <div v-if="$v.age.$error" class="validation-error">
                    <p class="error" v-if="!$v.age.required">Required</p>
                </div>
            </div>
        </section>
        <section class="event-create">
            <div class="title">
                <h2>Mobility Advisories</h2>
            </div>
            <div class="field">
                <label> Is the event wheelchair accessible? </label>
                <v-select 
                    v-model="advisories.wheelchairReady" 
                    :options="options"
                    label="name" 
                    placeholder="Choose one"
                    :clearable="false"
                    @search:blur="active = null"
                    @search:focus="active = 'wheel'"
                    @input="$v.advisories.wheelchairReady.$touch"
                    :class="{ active: active == 'wheel','error': $v.advisories.wheelchairReady.$error }" />
                <div v-if="$v.advisories.wheelchairReady.$error" class="validation-error">
                    <p class="error" v-if="!$v.advisories.wheelchairReady.required">Required</p>
                </div>
            </div>
            <div class="field">
                <label class="area">Select any mobility restrictions</label>
                <v-select 
                    v-model="mobilityAdvisories" 
                    :options="mobilityAdvisoryOptions"
                    multiple
                    taggable
                    label="mobilities" 
                    placeholder="Select from list or enter your own" 
                    :clearable="false"
                    @search:blur="active = null"
                    @search:focus="active = 'mobility'"
                    @input="$v.mobilityAdvisories.$touch"
                    :class="{ active: active == 'mobility','error': $v.mobilityAdvisories.$error }" />
                <div v-if="$v.mobilityAdvisories.$error" class="validation-error">
                    <p class="error" v-if="!$v.mobilityAdvisories.required">Must enter a mobility advisory </p>
                    <p class="error" v-if="!$v.mobilityAdvisories.maxChar">Advisory character length is too long </p>
                </div>
            </div>
        </section>

        <section class="event-create">
            <div class="title">
                <h2>Audience Interaction Level</h2>
            </div>
            <div class="field">
                <label class="area">Select interaction level with performers</label>
                <v-select 
                    v-model="interactiveLevel" 
                    :options="interactiveLevelOptions"
                    label="name" 
                    placeholder="Select your event's interaction level"
                    :clearable="false"
                    @search:blur="active = null"
                    @search:focus="active = 'interactive'"
                    @input="$v.interactiveLevel.$touch"
                    :class="{ active: active == 'interactive','error': $v.interactiveLevel.$error }">
                    <template #option="{ name, description }">
                        <div class="option__title--interaction">{{ name }}</div>
                        <div class="option__small-interaction">{{ description }}</div>
                    </template>
                </v-select>
                <div v-if="$v.interactiveLevel.$error" class="validation-error">
                    <p class="error" v-if="!$v.interactiveLevel.required">Must choose at least one interaction level </p>
                </div>
            </div>
            <div class="field">
                <label class="area"> Audience Role </label>
                <textarea 
                    v-model="advisories.audience" 
                    class="create-input area" 
                    rows="8" 
                    placeholder=" "
                    :class="{ active: active == 'audience','error': $v.advisories.audience.$error }"
                    @click="active = 'audience'"
                    @blur="active = null"
                    @input="$v.advisories.audience.$touch"
                    required 
                    autofocus />
                <div v-if="$v.advisories.audience.$error" class="validation-error">
                    <p class="error" v-if="!$v.advisories.audience.required">Must enter the audience's role </p>
                    <p class="error" v-if="!$v.advisories.audience.maxLength">Audience Role is too long </p>
                </div>
            </div>
        </section>
        <Submit 
            @submit="onSubmit"
            :disabled="disabled" 
            previous="description"
            next="images" 
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
    import _ from 'lodash';
    import { required, maxLength } from 'vuelidate/lib/validators'
    import Submit  from './components/submit-buttons.vue'

	export default {

        mixins: [formValidationMixin],

        components: { Submit },

		props: ['event', 'loadcontact', 'loadcontent', 'loadmobility', 'loadinteractive', 'agelimit'],

        computed: {
            endpoint() {
                return `/create/${this.event.slug}/advisories`
            },

            submitObject() {
                return {
                    advisories: {
                        contactAdvisories: this.advisories.contactAdvisories,
                        sexual: this.advisories.sexual.type,
                        sexualDescription: this.advisories.sexualDescription,
                        wheelchairReady: this.advisories.wheelchairReady.type,
                        ageRestriction: this.advisories.ageRestriction,
                        audience: this.advisories.audience,
                    },
                    contactAdvisory :   this.contactAdvisories.map(a => a.id),
                    contentAdvisory : this.contentAdvisories.map(a => a.advisories),
                    mobilityAdvisory : this.mobilityAdvisories.map(a => a.mobilities),
                    interactiveLevel : this.interactiveLevel,
                    age: this.age,
                }
            }
        },

		data() {
			return {
				advisories: this.initializeAdvisoriesObject(),
				contactAdvisoryOptions: this.loadcontact ? this.loadcontact : [],
				contentAdvisoryOptions: this.loadcontent ? this.loadcontent : [],
                mobilityAdvisoryOptions: this.loadmobility ? this.loadmobility : [],
                interactiveLevelOptions: this.loadinteractive ? this.loadinteractive: [],
				contactAdvisories: this.event.contact_levels ? this.event.contact_levels : '',
				contentAdvisories: this.event.content_advisories ? this.event.content_advisories : '',
                mobilityAdvisories: this.event.mobility_advisories ? this.event.mobility_advisories : '',
                interactiveLevel: this.event.interactivelevel ? this.event.interactivelevel : '',
                active: null,
                options: [{ name: 'Yes', type: true },{ name: 'No', type: false }],
                age:'',
                disabled: false,
                serverErrors: [],
                updated: false,
                approved: this.event.status == 'p' || this.event.status == 'e' ? true : false,
                creationPage: 7,
			}
		},

		methods: {
            async onSubmit(value) {
                if ( this.checkForChanges(value) ) { return this.onForward(value) }
                if ( this.checkVuelidate() ) { return }
                await axios.patch(this.endpoint, this.submitObject)
                value == 'save' ? this.save() : this.onForward(value);
            },

			initializeAdvisoriesObject() {
				return {
					contactAdvisories: this.event.advisories ? this.event.advisories.contactAdvisories : '',
					sexual: [],
					sexualDescription: this.event.advisories ? this.event.advisories.sexualDescription : '',
					wheelchairReady: [],
					ageRestriction: this.event.advisories ? this.event.advisories.ageRestriction : '',
                    audience: this.event.advisories ? this.event.advisories.audience : '',
				}
			},

            updateAdvisoryFields(input) {
                if ((input !== null) && (typeof input === "object") && (input.id !== null)) {
                    this.advisories = _.pick(input, _.intersection( _.keys(this.advisories), _.keys(input) ));
                }
                if (this.advisories.wheelchairReady != null) {
                    this.advisories.wheelchairReady ? this.advisories.wheelchairReady = { name: 'Yes', type: true } : this.advisories.wheelchairReady = { name: 'No', type: false };
                }
                if (this.advisories.sexual != null) {
                    this.advisories.sexual ? this.advisories.sexual = { name: 'Yes', type: true } : this.advisories.sexual = { name: 'No', type: false };
                }
            },

            onLoad() {
                axios.get(this.onFetch('advisories'))
                .then(res => {
                    this.updateAdvisoryFields(res.data.advisories);
                    res.data.contactPivots ? this.contactAdvisories = res.data.contactPivots : '';
                    res.data.contentPivots ? this.contentAdvisories = res.data.contentPivots : '';
                    res.data.mobilityPivots ? this.mobilityAdvisories = res.data.mobilityPivots : '';
                    res.data.interactivePivots ? this.interactiveLevel = res.data.interactivePivots : '';
                    res.data.age ? this.age = res.data.age : '';
                });
            },
		},

        watch: {
            '$store.state.navurl'() {
                this.checkForChanges() ? this.onBack(this.$store.state.navurl.page) : this.onSubmit(this.$store.state.navurl.page)
            }
        },

        created() {
            this.onLoad();
        },


        validations: {
            age: {
                required
            },
            contactAdvisories: {
                required
            },
            mobilityAdvisories: {
                required,
                maxChar() {
                    return this.mobilityAdvisories.filter( mobility => mobility.mobilities.length > 30 ).length ? false : true
                }
            },
            contentAdvisories: {
                required,
                maxChar() {
                    return this.contentAdvisories.filter( content => content.advisories.length > 30 ).length ? false : true
                }
            },
            interactiveLevel: {
                required
            },
			advisories: {
                sexualDescription: {
                    ifSexual() {
                        return this.advisories.sexual && this.advisories.sexual.type ? this.advisories.sexualDescription ? true : false : true
                    },
                    maxLength: maxLength(1000),
                },
                wheelchairReady: {
                    required,
                },
                audience: {
                    required,
                    maxLength: maxLength(1000),
                },
                sexual: {
                    required
                }
			},	
		},
    };

</script>