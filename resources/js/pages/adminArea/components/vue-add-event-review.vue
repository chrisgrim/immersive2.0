<template>
    <div class="new">
        <div class="content">
            <div class="event">
                <label>Event</label>
                <v-select 
                    v-model="event"
                    :options="events"
                    label="name"
                    placeholder="Select the event"
                    @search="onSearch"
                    @search:focus="onSearch"
                    @search:blur="active = null"
                    @input="$v.event.$touch"
                    :class="{ active: active == 'event','error': $v.event.$error}" />
                <div v-if="$v.event.$error" class="validation-error">
                    <p class="error" v-if="!$v.event.required">Please select event</p>
                </div>
            </div>
            <div class="field">
                <label>Reviewer</label>
                <v-select 
                    v-model="reviewername"
                    :options="reviewerList"
                    label="name"
                    taggable
                    placeholder="Select reviewer or add your own"
                    @search:blur="active = null"
                    @input="assignUrl(reviewername)"
                    :class="{ active: active == 'name','error': $v.reviewername.$error }" />
                <div v-if="$v.reviewername.$error" class="validation-error">
                    <p class="error" v-if="!$v.reviewername.required">Please add reviews name</p>
                </div>
            </div>
            <div class="rank">
                <label>Rank</label>
                <v-select 
                    v-model="rank"
                    :options="rankOptions"
                    placeholder="Leave blank for default Rank of 5 (1 being most important)"
                    @search:blur="active = null"
                    @search:focus="active = 'rank'"
                    @input="assignUrl(reviewername)"
                    :class="{ active: active == 'rank'}" />
            </div>
            <div class="field">
                <label>Review URL</label>
                <input 
                    type="text" 
                    v-model="url" 
                    placeholder="Link to the review"
                    :class="{ active: active == 'url','error': $v.url.$error}"
                    @click="active = 'url'"
                    @input="$v.url.$touch"
                    @blur="active = null">
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
            <button @click.prevent="onSubmit">
                Add Review
            </button>
        </div>
    </div>
</template>

<script>

    import { required,url,maxLength } from 'vuelidate/lib/validators'
    import formValidationMixin from '../../../mixins/form-validation-mixin'

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