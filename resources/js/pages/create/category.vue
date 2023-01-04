<template>
    <div 
        :class="{ showcat: category}" 
        class="event-create__category grid">
        <section 
            :class="{ showcat: category}" 
            class="event-enter-category">
            <div class="title">
                <h2>Immersive Categories</h2>
            </div>
            <div class="field">
                <label>The broad categorization of your event</label>
                <v-select 
                    v-model="category"
                    label="name"
                    :options="categoryOptions"
                    :clearable="false"
                    placeholder="Select Category"
                    @search:blur="active = null"
                    @search:focus="active = 'category'"
                    @input="$v.category.$touch"
                    :class="{ active: active == 'category','error': $v.category.$error}" />
                <input 
                    type="hidden" 
                    name="category" 
                    v-model="category">
                <div v-if="$v.category.$error" class="validation-error">
                    <p class="error" v-if="!$v.category.required">
                        Please select your event's category 
                    </p>
                </div>
                <template v-if="category">
                    <div class="category-description">
                        <p>{{ category.description }}</p>
                    </div>
                </template>
            </div>
            <div class="tag-section">
                <div class="title">
                    <h3>Event Tags</h3>
                </div>
                <div class="field">
                    <label>Tags will help users with the more finite categorization of your event. You can select from pre-generated tags or create your own tags.</label>
                    <v-select 
                        v-model="genres"
                        label="name"
                        placeholder="Type here to create your own" 
                        :options="tags"
                        taggable
                        multiple
                        @search:blur="active = null"
                        @search:focus="active = 'genre'"
                        @input="$v.genres.$touch"
                        :class="{ active: active == 'genre','error': $v.genres.$error }" />
                    <div v-if="$v.genres.$error" class="validation-error">
                        <p class="error" v-if="!$v.genres.name.required && $v.genres.maxLength">Must enter at least one Tag</p>
                        <p class="error" v-if="!$v.genres.maxLength">No more than 10 tags</p>
                        <p class="error" v-if="!$v.genres.name.maxChar && $v.genres.name.required">Tag character length is too long</p>
                    </div>
                </div>
            </div>
        </section>

        <section 
            :class="{ showcat: category}" 
            v-if="category" 
            class="event-show-category__image" 
            :style="`height:calc(${window/pageHeight}px + 7rem)`">
            <picture>       
                <source 
                    type="image/webp" 
                    :srcset="`${envImageUrl}${category.largeImagePath}`"> 
                <img :src="`${envImageUrl}${category.largeImagePath.slice(0, -4)}jpg`">
            </picture>
        </section>
        <Submit 
            @submit="onSubmit"
            :disabled="disabled" 
            previous="location"
            next="shows" 
            :event="event" />
        <transition name="slide-fade">
            <div 
                v-if="updated" 
                class="updated-notifcation">
                <p>Your event has been updated.</p>
            </div>
        </transition>
    </div>
</template>

<script>
    import Submit  from './components/submit-buttons.vue'
    import formValidationMixin from '../../mixins/form-validation-mixin'
	import { required, maxLength } from 'vuelidate/lib/validators';

	export default {

        props: ['event', 'categories', 'tags'],

        mixins: [ formValidationMixin ],

		components: { Submit },

        computed: {
            endpoint() {
                return `/create/${this.event.slug}/category`
            },
        },

		data() {
			return {
				category: '',
                genres: '',
				categoryOptions: this.categories,
				active: null,
                window: window.innerHeight,
                pageHeight: window.innerWidth > 1050 ? 1 : 2,
                disabled: false,
                updated: false,
                creationPage: 3,
			}
		},


		methods: {
			async onSubmit(value) {
                if ( this.checkForChanges(value) ) { return this.onForward(value) }
                if ( this.checkVuelidate() ) { return }
				await axios.patch(this.endpoint, {category: this.category, genres: this.genres})
                value == 'save' ? this.save() : this.onForward(value);
			},

            onLoad() {
                axios.get(this.onFetch('category'))
                .then( res => { 
                    res.data.genres ? this.genres = res.data.genres : null;
                    res.data.category ? this.category = res.data.category : null; 
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
            }
        },

		validations: {
			category: {
				required
			},
            genres: {
                maxLength: maxLength(10),
                required,
                name: {
                    maxChar() {
                        return this.genres.length ? this.genres.filter( tag => tag.name.length > 30 ).length ? false : true : false
                    }
                }
            },
		},
    };
</script>