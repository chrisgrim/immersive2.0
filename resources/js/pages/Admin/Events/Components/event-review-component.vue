<template>
    <div class="border border-black rounded-2xl p-4 mb-8 shadow-custom-1">
        <div class="flex w-full gap-4 items-center">
            <div class="w-3/12">
                <label>Event</label>
                <v-select 
                    v-model="event"
                    :options="events"
                    label="name"
                    placeholder="Select the event"
                    @search="debounce"
                    @search:focus="debounce"
                    @input="$v.event.$touch"
                    class="bg-white"
                    :class="{ 'error': $v.event.$error}" />
                <div v-if="$v.event.$error" class="validation-error">
                    <p class="error" v-if="!$v.event.required">Please select event</p>
                </div>
            </div>
            <div class="w-3/12">
                <label>Reviewer</label>
                <v-select 
                    v-model="reviewername"
                    :options="reviewerList"
                    label="name"
                    taggable
                    placeholder="Select reviewer or add your own"
                    @input="assignUrl(reviewername)"
                    :class="{ 'error': $v.reviewername.$error }" />
                <div v-if="$v.reviewername.$error" class="validation-error">
                    <p class="error" v-if="!$v.reviewername.required">Please add reviews name</p>
                </div>
            </div>
            <div class="w-3/12">
                <label>Rank</label>
                <v-select 
                    v-model="rank"
                    :options="rankOptions"
                    placeholder="Leave blank for default Rank of 5 (1 being most important)"
                    @input="assignUrl(reviewername)" />
            </div>
            <div class="w-3/12">
                <label>Review URL</label>
                <input 
                    type="text" 
                    v-model="url" 
                    placeholder="Link to the review"
                    :class="{ 'error': $v.url.$error}"
                    class="border p-5 rounded-full"
                    @input="$v.url.$touch">
                <div v-if="$v.url.$error" class="validation-error">
                    <p class="error" v-if="!$v.url.required">Please add url</p>
                </div>
            </div>
        </div>
        <div class="content">
            <label>Review</label>
            <textarea 
                type="textarea" 
                v-model="review"
                rows="6"
                placeholder="Review snippet (no longer than 120 characters)"
                :class="{ active: active == 'review','error': $v.review.$error}"
                @click="active = 'review'"
                @input="$v.review.$touch"
                @blur="active = null" />
            <div v-if="$v.review.$error" class="validation-error">
                <p class="error" v-if="!$v.review.required">Please add review snippet.</p>
                <p class="error" v-if="!$v.review.maxLength">Please keep it under 1200 letters.</p>
            </div>
        </div>
        <div class="content">
            <button 
                class="font-medium py-6 px-4 rounded-2xl border-none text-white bg-gradient-to-r from-button-red-1 via-button-red-2 to-button-red-3 md:px-20 hover:from-button-red-2 hover:via-button-red-3 hover:to-button-red-1 disabled:from-bg-gray-500 disabled:to-gray-500"
                @click.prevent="onSubmit">
                Add Review
            </button>
        </div>
    </div>
</template>

<script>

    import { required,url,maxLength } from 'vuelidate/lib/validators'
    import formValidationMixin from '../../../../mixins/form-validation-mixin'

    export default {

        mixins: [ formValidationMixin ],

        computed: {
            submitObject() {
                return {
                    reviewername: this.reviewername ? this.reviewername : '',
                    review: this.review ? this.review : '',
                    url: this.url ? this.url : '',
                    rank: this.rank ? this.rank : '',
                    event: this.event ? this.event : '',
                    image_path: this.image_path ? this.image_path : '/storage/reviews/default.png'
                }
            },
        },

        data() {
            return {
                event: '',
                events: [],
                rank: '',
                rankOptions: ['1', '2', '3', '4', '5'],
                active: '',
                reviewername: '',
                image_path: '',
                review: '',
                url: '',
                selectedModal: '',
                reviewerList: ['No Proscenium', 'Room Escape Artist'],
            }
        },

         methods: {
            async onSubmit() {
                if (this.checkVuelidate()) { return false }
                await axios.post('/reviewevents', this.submitObject)
                location.reload()
            },
            onSearch(query) {
                axios.get('/api/admin/events/search', { params: { keywords: query } })
                .then( res => { this.events = res.data.data });
            },
            assignUrl(arr) {
                this.$v.reviewername.$touch;
                if(arr == 'No Proscenium') {
                    return this.image_path = '/storage/reviews/nopro.png'
                }
                this.image_path = '/storage/reviews/default.png'
                return this.url = '';
            },
            debounce(query) {
                if (this.timeout) 
                    clearTimeout(this.timeout); 
                this.timeout = setTimeout(() => {
                    this.onSearch(query)
                }, 500); // delay
            },
        },

        validations: {
            event: {
                required,
            },
            reviewername: {
                required,
            },
            url: {
                required,
                url
            },
            review: {
                required,
                maxLength: maxLength(1200)
            },
        },
    }
</script>