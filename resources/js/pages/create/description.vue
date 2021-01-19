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
                    v-model="group.description" 
                    placeholder="eg. Our super scary event will bring your fears to the surface..."
                    :class="{ active: active == 'description','error': $v.group.description.$error }"
                    @input="$v.group.description.$touch"
                    @click="active = 'description'"
                    @blur="active = null" 
                    rows="14" />
                <div v-if="$v.group.description.$error" class="validation-error">
                    <p class="error" v-if="!$v.group.description.required">Must provide a description</p>
                    <p class="error" v-if="!$v.group.description.maxLength">Description is too long</p>
                </div>
            </div>
            <div class="field">
                <label>Event Link (Optional)</label>
                <input 
                    type="text" 
                    v-model="group.websiteUrl"
                    :class="{ active: active == 'website','error': $v.group.websiteUrl.$error }"
                    @click="active = 'website'"
                    @blur="active = null"
                    @input="$v.group.websiteUrl.$touch"
                    placeholder="Link to a page that has more information about your event">
                <div v-if="$v.group.websiteUrl.$error" class="validation-error">
                    <p class="error" v-if="!$v.group.websiteUrl.url">Must be a url (https://...)</p>
                    <p class="error" v-if="!$v.group.websiteUrl.maxLength">Url is too long</p>
                </div>
            </div>
        </section>

        <section>
            <div class="tag-title">
                <h3>Event Tags</h3>
            </div>
            <div class="field">
                <label>Type in or select all show tags. We use these to help people find your event!</label>
                <v-select 
                    v-model="tagName"
                    label="name"
                    placeholder="Type here to create your own" 
                    :options="tagOptions"
                    taggable
                    multiple
                    @search:blur="active = null"
                    @search:focus="active = 'genre'"
                    @input="$v.tagName.$touch"
                    :class="{ active: active == 'genre','error': $v.tagName.$error }" />
                <div v-if="$v.tagName.$error" class="validation-error">
                    <p class="error" v-if="!$v.tagName.required">Must select at least one Tag</p>
                    <p class="error" v-if="!$v.tagName.maxLength">No more than 10 tags</p>
                    <p class="error" v-if="!$v.tagName.maxChar">Tag character length is too long</p>
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

		props: ['event', 'loadtags'],

        components: { Submit },

        mixins: [ formValidationMixin ],

        computed: {
            endpoint() {
                return `/create/${this.event.slug}/description`
            },
        },

		data() {
			return {
                group: this.initializeSubmitObject(),
                tagName: this.event.genres ? this.event.genres : '',
                tagOptions: this.loadtags,
                disabled: false,
                serverErrors: [],
                active: null,
                updated: false,
                approved: this.event.status == 'p' || this.event.status == 'e' ? true : false,
                creationPage: 6,
			}
		},

		methods: {
			async onSubmit(value) {
                if ( this.checkForChanges(value) ) { return this.onForward(value) }
                if ( this.checkVuelidate() ) { return }
				await axios.patch(this.endpoint, this.group)
                value == 'save' ? this.save() : this.onForward(value);
			},

            onLoad() {
                axios.get(this.onFetch('description'))
                .then(res => {
                    res.data.event ? this.group.description = res.data.event.description : '';
                    res.data.event ? this.group.websiteUrl = res.data.event.websiteUrl : '';
                    res.data.genres ? this.group.genre = res.data.genres.map(a => a.name) : '';
                });
            },
            
            initializeSubmitObject() {
                return {
                    description: this.event.description ? this.event.description : '',
                    websiteUrl: this.event.websiteUrl ? this.event.websiteUrl : '',
                    genre: this.event.genres ? this.event.genres.map(a => a.name) : '',
                }
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

            tagName(){
                return this.group.genre = this.tagName.map(a => a.name);
            },
        },

        validations: {
            tagName: {
                required,
                maxLength: maxLength(10),
                maxChar() {
                    return this.tagName.filter( tag => tag.name.length > 30 ).length ? false : true
                }
            },
            group : {
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