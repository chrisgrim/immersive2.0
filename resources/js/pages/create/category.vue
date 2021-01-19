<template>
    <div 
        :class="{ showcat: catgory}" 
        class="event-create__category grid">
        <section 
            :class="{ showcat: catgory}" 
            class="event-enter-category">
            <div class="title">
                <h2>Immersive Categories</h2>
            </div>
            <div class="field">
                <v-select 
                    v-model="catgory"
                    label="name"
                    :options="categoryOptions"
                    :clearable="false"
                    placeholder="Select Category"
                    @search:blur="active = null"
                    @search:focus="active = 'category'"
                    @input="$v.catgory.$touch"
                    :class="{ active: active == 'category','error': $v.catgory.$error}" />
                <input 
                    type="hidden" 
                    name="category" 
                    v-model="catgory">
                <div v-if="$v.catgory.$error" class="validation-error">
                    <p class="error" v-if="!$v.catgory.required">Please select your event's category</p>
                </div>
                <div>
                    <br>
                    <p v-text="this.catgory ? catgory.description : ''" />
                </div>
            </div>
        </section>

        <section 
            :class="{ showcat: catgory}" 
            v-if="catgory" 
            class="event-show-category__image" 
            :style="`height:calc(${window/pageHeight}px - 7rem)`">
            <picture>       
                <source 
                    type="image/webp" 
                    :srcset="`/storage/${catgory.largeImagePath}`"> 
                <img :src="`/storage/${catgory.largeImagePath.slice(0, -4)}jpg`">
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
	import { required } from 'vuelidate/lib/validators';

	export default {

        props: ['event', 'categories'],

        mixins: [ formValidationMixin ],

		components: { Submit },

        computed: {
            endpoint() {
                return `/create/${this.event.slug}/category`
            },
        },

		data() {
			return {
				catgory: '',
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
				await axios.patch(this.endpoint, this.catgory)
                value == 'save' ? this.save() : this.onForward(value);
			},

            onLoad() {
                axios.get(this.onFetch('category'))
                .then( res => { this.catgory = res.data; });
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
			catgory: {
				required
			},
		},
    };
</script>