<template>
    <section 
        id="reviews" 
        class="es__rating--reviews element">
        <template v-if="event.ratingAmount > 1">
            <span class="es__star active"><IconSvg type="star" /></span><h2>{{event.averageRating}} ({{event.ratingAmount}} Reviews)</h2>
        </template>
        <template v-else-if="event.ratingAmount === 1">
            <span class="es__star active"><IconSvg type="star" /></span><h2>{{event.averageRating}} ({{event.ratingAmount}} Review)</h2>
        </template>
        <template v-else>
            <span class="es__star"><IconSvg type="star" /></span><h2>No Reviews</h2>
        </template>
        <div class="es__rating">
            <div 
                v-if="!event.hasRated"
                class="es__rating--submit">
                <div class="es__rating--details">
                    <div class="es__rating--image">
                        <template v-if="pageUser && pageUser.thumbImagePath">
                            <img 
                                :src="`/storage/${pageUser.thumbImagePath}`" 
                                :alt="`Image of ${pageUser.name}`">
                        </template>
                        <template v-else-if="pageUser">
                            <p> {{ pageUser.name.charAt(0) }} </p>
                        </template>
                        <template v-else>
                            <IconSvg type="user" />
                        </template>
                    </div>
                    <div class="es__rating--name subtext">
                        <h4> Add your rating: </h4>
                        <div>
                            <span
                                :key="star"
                                v-for="star in starRatings">
                                <button
                                    class="es__star"
                                    :class="{'active': ((rating.rating >= star) && rating.rating != null)}"
                                    @click="setRating(star)" 
                                    @mouseover="star_over(star)" 
                                    @mouseout="star_out">
                                    <IconSvg type="star" />
                                </button>
                            </span>
                            <div v-if="$v.rating.rating.$error" class="validation-error">
                                <p class="error" v-if="!$v.rating.rating.required">Required</p>
                                <p class="error" v-if="!$v.rating.rating.numeric">Must be a number</p>
                                <p class="error" v-if="!$v.rating.rating.between">Must be between 0-5</p>
                            </div>
                        </div>
                        <div class="es__rating--date">
                            <p>Date Attended:</p>
                            <flat-pickr
                                v-if="rating"
                                v-model="rating.date"
                                :config="config"                                  
                                class="form-control"
                                placeholder="Select date"
                                ref="datePicker"             
                                name="dates" />
                        </div>
                        <div v-if="$v.rating.date.$error" class="validation-error">
                            <p style="float:right" class="error" v-if="!$v.rating.date.required">Please select the show date you saw</p>
                        </div>
                    </div>
                </div>
                <textarea
                    name="comments"
                    v-model="rating.comment" />
                <div v-if="$v.rating.comment.$error" class="validation-error">
                    <p class="error" v-if="!$v.rating.comment.required">Must provide a comment</p>
                    <p class="error" v-if="!$v.rating.comment.maxLength">Comment is too long</p>
                    <p class="error" v-if="!$v.rating.comment.minLength">Please leave a longer comment {{rating.comment.length}}/50</p>
                </div>
                <button 
                    class="rate"
                    @click="onSubmit">Review</button>
            </div>
            <div 
                class="es__rating--element" 
                :key="rate.id"
                v-for="rate in ratings">
                <div class="es__rating--details">
                    <div class="es__rating--image">
                        <template v-if="rate.user.thumbImagePath">
                            <img 
                                :src="`/storage/${rate.user.thumbImagePath}`" 
                                :alt="`Image of ${rate.user.name}`">
                        </template>
                        <template v-else>
                            <p> {{ rate.user.name.charAt(0) }} </p>
                        </template>
                    </div>
                    <div class="es__organizer--name subtext">
                        <h4> {{ rate.user.name.replace(/ .*/,'') }}</h4>
                        <p> {{ cleanDate(rate.user.created_at) }}</p>
                    </div>
                </div>
                <div>
                    <div
                        class="es__star"
                        :key="star"
                        v-for="star in starRatings"
                        :class="{'active': ((rate.rating >= star) && rate.rating != null)}">
                        <IconSvg type="star" />
                    </div>
                    <ShowMore 
                        :text="rate.comment"
                        white-space="normal"
                        :less-button="false"
                        :limit="35" />
                </div>
                <div 
                    v-if="pageUser && pageUser.email_verified_at"
                    class="es__rating--helpful subtext">
                    <button 
                        class="helpful" 
                        @click="onHelpful(rate)">
                        Helpful
                    </button>
                    <template v-if="rate.flags.length === 1">
                        <p>{{rate.flags.length}} person found this helpful</p>
                    </template>
                    <template v-if="rate.flags.length > 1">
                        <p>{{rate.flags.length}} people found this helpful</p>
                    </template>
                </div>
            </div>
            <div v-if="guestRate">
                <VueLogin 
                    @close="guestRate = false"
                    @submit="verify"
                    :redirect="false"
                    page="register"/>
            </div>
            <div 
                class="es__rating--verify" 
                v-if="onVerify">
                <div class="es__rating--verifybox">
                    <h3>Please verify your account.</h3>
                    <p>We have sent you a verification email. Reviews by unverified accounts will be removed after a week.</p>
                    <a href="">Resend verification email</a>
                    <button @click="onVerify=false"> Ok </button>
                </div>
            </div>
        </div>
    </section>
</template>

<script>
    import ShowMore  from '../components/show-more.vue'
    import IconSvg from '../../../components/Svg-icon'
    import VueLogin from '../../layouts/login-pop'
    import formValidationMixin from '../../../mixins/form-validation-mixin'
    import { required, numeric, between, maxLength, minLength } from 'vuelidate/lib/validators'
    import flatPickr from 'vue-flatpickr-component'
    export default {

        props: [ 'event', 'user'],

        components: { ShowMore, IconSvg, VueLogin, flatPickr },

        mixins: [ formValidationMixin ],

        data() {
            return {
                ratings: this.event.ratings,
                rating: this.$cookies.isKey('rating') ? this.$cookies.get('rating') : this.initializeRatingObject(),
                config: this.initializeCalendarObject(),
                starRatings: [1, 2, 3, 4, 5],
                temp_value: '',
                guestRate: false,
                pageUser: this.user,
                onVerify: false,
            }
        },

        methods: {
            async onSubmit() {
                if ( this.checkVuelidate() ) { return }
                if (!this.pageUser) { return this.setRatingCookie() } 
                await axios.post(`/rate/${this.event.slug}/rating`, this.rating);
                this.$cookies.remove("rating")
                location.reload();
            },
            async onHelpful(val) {
                await axios.post(`/flag/${val.id}/`, {flag: 'helpful'})
                .then(res => {
                    this.ratings.map(rate => rate.id === val.id ? rate.flags = res.data.flags  : '');   
                });
            },
            setRatingCookie() {
                this.$cookies.set('rating', this.rating);
                this.guestRate = true 
            },
            initializeRatingObject() { 
                return {
                    rating: '',
                    comment: '',
                    date: [],
                }
            },
            initializeCalendarObject() { 
                return {
                    maxDate: new Date().fp_incr(180),
                    mode: "single",
                    inline: false,
                    altFormat: "F j, Y",
                    altInput: true,
                    showMonths: window.innerWidth < 768 ? 1 : 2,
                    dateFormat: 'Y-m-d H:i:s',
                    disable: [],
                }
            },
            verify(value) {
                this.pageUser = value.user;
                this.guestRate = false;
                this.onSubmit();
            },
            cleanDate(data) {
                return this.$dayjs(data).format("YYYY");
            },
            setRating(value) {
                this.temp_value = value;
                return this.rating.rating = value;
            },
            star_over(index) {
                this.temp_value = this.rating.rating;
                return this.rating.rating = index;
            },
            star_out() {
                return this.rating.rating = this.temp_value;
            },

        },

        mounted() {
            !this.pageUser.email_verified_at && this.ratings.length && this.ratings[0].user_id === this.pageUser.id ? this.onVerify = true : ''
        },

        validations: {
            rating : {
                rating: {
                    required,
                    numeric,
                    between: between(0, 5),
                },
                comment: {
                    required,
                    maxLength: maxLength(500),
                    minLength: minLength(50),
                },
                date: {
                    required,
                },
            }
        },

    }
</script>