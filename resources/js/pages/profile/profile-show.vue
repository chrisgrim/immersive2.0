<template>
    <div class="m-auto w-full px-8 md:py-8 md:px-12 lg:py-0 lg:px-32 lg:max-w-screen-xl lg:pt-24">
        <div class="flex flex-col md:flex-row">
            <div class="w-full inline-block md:w-2/6 md:px-8 lg:p-8">
                <div class="sticky top-16 items-center flex flex-col">
                    <CardImage
                        v-if="owner || user.thumbImagePath"
                        :loading="disabled"
                        :locked="!owner || !user.email_verified_at"
                        text="The image must be at least 400px x 400px"
                        :image="image"
                        @addImage="addImage" />
                </div>
            </div>
            <!-- User information Section --> 
            <section class="w-full inline-block md:w-4/6">
                <div>
                    <div class="text">
                        <div class="flex justify-between items-center gap-16">
                            <div class="w-full">
                                <input 
                                    type="text" 
                                    v-model="user.name"
                                    :readonly="!owner"
                                    :disabled="!owner"
                                    class="my-4"
                                    :class="[ 
                                        $v.user.name.$error ? 'error' : '', 
                                        owner ? 'h-16 border rounded-2xl p-4 w-full' : 'text-4xl bg-white' ]"
                                    @input="$v.user.name.$touch">
                                <div v-if="$v.user.name.$error" class="validation-error">
                                    <p class="error" v-if="!$v.user.name.required">Must have a name</p>
                                    <p class="error" v-if="!$v.user.name.maxLength">Can't be more than 50 characters</p>
                                    <p class="error" v-if="!$v.user.name.auth">You don't have permission to edit</p>
                                </div>
                            </div>
                            <div class="w-96">
                                <p>Member since {{ cleanDate(user.created_at) }}</p>
                            </div>
                        </div>
                        <div class="flex items-center gap-4">
                            <p 
                                class="mt-[0.4rem]"
                                v-if="!owner && location.latitude">Lives near</p>
                            <input 
                                @input="$v.user.location.$touch"
                                ref="autocomplete" 
                                :readonly="!owner"
                                :disabled="!owner"
                                class="my-4"
                                :class="[ 
                                    $v.user.name.$error ? 'error' : '', 
                                    owner ? 'h-16 border rounded-2xl p-4 w-full' : 'text-4xl bg-white' ]"
                                :placeholder="locationPlaceholder"
                                autocomplete="false"
                                onfocus="value = ''" 
                                type="text">
                        </div>
                        <button 
                            :disabled="disabled" 
                            @click.prevent="resend" 
                            class="border-none bg-orange-500 text-white my-4 hover:bg-black" 
                            v-if="owner && !user.email_verified_at && !onSent">
                            Please verify your account.
                        </button>
                        <div
                            class="border w-96 p-4 rounded-2xl my-4" 
                            v-if="owner && !user.email_verified_at && onSent">
                            Please check your email.
                        </div>
                        <div class="">
                            <button 
                                v-if="$v.user.$anyDirty"
                                :disabled="disabled" 
                                @click.prevent="onSubmit()" 
                                class="border-none bg-gradient-to-r from-button-red-1 via-button-red-2 to-button-red-3 text-white"> 
                                Save 
                            </button>
                        </div>
                    </div>
                </div>
            </section>
        </div>
        <transition name="slide-fade">
            <div 
                v-if="updated" 
                class="fixed top-16 right-16 z-[2003] rounded-2xl bg-green-500 p-8">
                <p class="text-white">Your profile has been updated.</p>
            </div>
        </transition>
    </div>
</template>
<script>
    import CardImage from './Components/vue-add-image.vue'
    import { required, maxLength, email } from 'vuelidate/lib/validators'
    import profileLocationMixin from './Components/profile-location-mixin'
    import formValidationMixin from '../../mixins/form-validation-mixin'
    import _ from 'lodash'
    export default {

        props: ['loaduser', 'owner'],

        mixins: [ profileLocationMixin, formValidationMixin ],

        components: { CardImage },

        computed: {
            submitObject() {
                return {
                    name: this.user.name,
                    location: this.location
                }
            },
        },

        data() {
            return {
                user: this.loaduser,
                location: this.loaduser.location ?  this.loaduser.location : this.initializeLocationObject(),
                formData: new FormData(),
                active: null,
                serverErrors: [],
                onSent: false,
                disabled: false,
                image: this.loaduser && this.loaduser.largeImagePath ? `${process.env.MIX_IMAGE_URL}${this.loaduser.largeImagePath}` : null,
                locationPlaceholder: this.initializeLocationPlaceholder(),
                updated: false,
            }
        },

        methods: {
            async onSubmit() {
                if ( this.checkVuelidate() && this.user.isUser ) { return }
                await axios.patch(`/users/${this.user.id}`, this.submitObject)
                .then(res => {
                    this.onUpdated()
                })
                .catch( err => { this.onErrors(err) })
            },
            async submitImage() {
                await axios.post(`/users/${this.user.id}`, this.formData)
                .then( res => { 
                    this.onUpdated()
                });
            },
            async logout(){
                await axios.post('/logout')
                .then(res => { window.location.href = '/' })
                .catch(error => {});
            },
            addImage(image) {
                this.disabled = true
                this.formData.append('_method', 'PUT');
                this.formData.append('image', image);
                this.submitImage()
            },
            onUpdated() {
                this.$v.$reset();
                this.disabled = false
                this.updated = true;
                setTimeout(() => this.updated = false, 3000);
            },
            async resend() {
                await axios.post(`/email/verification-notification`)
                this.onSent = true
                setTimeout(() => this.onSent = false, 10000);
            },
            cleanDate(data) {
                return this.$dayjs(data).format("YYYY");
            },
            initializeLocationObject() {
                return {
                    street:  '',
                    city:  '',
                    region: '',
                    country: '',
                    postal_code: '',
                    hiddenLocation: '',
                    hiddenLocationToggle: 0,
                    latitude: '',
                    longitude: '',
                    home: '',
                }
            },
            initializeLocationPlaceholder() {
                if (this.loaduser.location && this.loaduser.location.city) { return this.loaduser.location.city + ', ' +  this.loaduser.location.country }
                if (this.loaduser.location && this.loaduser.location.region) { return this.loaduser.location.region }
                if (this.loaduser.location && this.loaduser.location.country) { return ' the ' + this.loaduser.location.country }
                if (this.loaduser.isUser) { return 'Add your location'}
                return ''
            }
        },

        mounted() {
            this.autocomplete = new google.maps.places.Autocomplete(
                (this.$refs.autocomplete),
                { types: ['geocode'] },
            );
            this.autocomplete.addListener('place_changed', this.setPlace);
        },

        validations: {
            user: {
                name: {
                    required,
                    maxLength: maxLength(50),
                    auth() {
                        return this.auth ? this.auth !== this.user.id : true
                    }
                },
                location: {
                    latitude: {
                        maxLength: maxLength(2000),
                    },
                },
            },
        },
    };
</script>