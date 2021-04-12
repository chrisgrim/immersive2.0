<template>
    <div class="user-profile grid">
        <ProfileImage
            :auth="auth"
            :loaduser="user" />
        <!-- User information Section --> 
        <section class="user-enter-profile">
            <div v-show="onEditUser">
                <div class="field">
                    <div class="text">
                        <div class="field">
                            <label>User Name</label>
                            <input 
                                type="text" 
                                v-model="user.name"
                                :class="{ active: active == 'user','error': $v.user.name.$error }"
                                @click="active = 'website'"
                                @blur="active = null"
                                @input="$v.user.name.$touch">
                            <div v-if="$v.user.name.$error" class="validation-error">
                                <p class="error" v-if="!$v.user.name.required">Must have a name</p>
                                <p class="error" v-if="!$v.user.name.maxLength">Can't be more than 50 characters</p>
                                <p class="error" v-if="!$v.user.name.auth">You don't have permission to edit</p>
                            </div>
                        </div>
                        <div class="field">
                            <label> Location </label>
                            <input 
                                ref="autocomplete" 
                                :placeholder="locationPlaceholder ? locationPlaceholder : 'Choose your location'"
                                :class="{ active: active == 'location'}"
                                autocomplete="false"
                                onfocus="value = ''" 
                                @click="active = 'location'"
                                @blur="active = null"
                                type="text">
                        </div>
                        <div class="field">
                            <label> Email </label>
                            <input 
                                type="email" 
                                v-model="user.email"
                                :class="{ active: active == 'email','error': $v.user.email.$error }"
                                @click="active = 'email'"
                                @blur="active = null"
                                @input="$v.user.email.$touch">
                            <div v-if="$v.user.email.$error" class="validation-error">
                                <p class="error" v-if="!$v.user.email.required">Must have an email</p>
                                <p class="error" v-if="!$v.user.email.email">Must be a valid email</p>
                                <p class="error" v-if="!$v.user.email.auth">You don't have permission to edit</p>
                            </div>
                        </div>
                        <div class="">
                            <button 
                                :disabled="disabled" 
                                @click.prevent="onSubmit()" 
                                class="save"> 
                                Save 
                            </button>
                            <button 
                                @click.prevent="onEditUser=false" 
                                class="cancel"> 
                                Cancel
                            </button>
                        </div>
                    </div>
                </div>
            </div>
            <div v-show="!onEditUser">
                <div class="profile-user-name">
                    <h1>{{ user.name }}</h1>
                </div>
                <div class="info">
                    <div class="profile-user-info">
                        Member since {{ cleanDate(user.created_at) }}
                    </div>
                    <div 
                        v-if="canEditPage" 
                        class="profile-user-edit" 
                        @click="onEditUser = true">
                        Edit profile
                    </div>
                    <button 
                        :disabled="disabled" 
                        @click.prevent="resend" 
                        class="verify-email" 
                        v-if="owner && !user.email_verified_at && !onSent">
                        Please verify your account.
                    </button>
                    <div 
                        class="ver a" 
                        v-if="owner && !user.email_verified_at && onSent">
                        Please check your email.
                    </div>
                </div>
                <div 
                    class="profile-user-info" 
                    v-if="location.latitude">
                    <div>
                        Lives near <span style="font-weight:600;color:#616161">{{ locationPlaceholder }}</span> 
                    </div>
                </div>
            </div>
            <div class="logout mobile">
                <button @click.prevent="logout()">
                    Log out
                </button>
            </div>
        </section>
    </div>
</template>
<script>
    import ProfileImage from './components/profile-image.vue'
    import { required, maxLength, email } from 'vuelidate/lib/validators'
    import profileLocationMixin from './components/profile-location-mixin'
    import formValidationMixin from '../../mixins/form-validation-mixin'


    export default {

        props: ['loaduser', 'events', 'auth'],

        mixins: [ profileLocationMixin, formValidationMixin ],

        components: { ProfileImage },

        computed: {
            locationPlaceholder() {
                if (this.location.city) {
                    return this.location.city + ', ' +  this.location.country
                }
                if (this.location.region) {
                    return this.location.region
                }
                if (this.location.country) {
                    return ' the ' + this.location.country
                }
                return null
            },
            canEditPage() {
                return this.owner && this.user.email_verified_at
            },
            submitObject() {
                return {
                    name: this.user.name,
                    location:this.location,
                    email: this.user.email,
                }
            },
        },

        data() {
            return {
                user: this.loaduser,
                location: this.loaduser.location ?  this.loaduser.location : this.initializeLocationObject(),
                active: null,
                serverErrors: [],
                onEditUser: false,
                onSent: false,
                disabled: false,
                owner: Number(this.auth) === Number(this.loaduser.id) ? true : false
            }
        },

        methods: {
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

            async resend() {
                if (this.checkVuelidate()) { return }
                await axios.post(`/email/verification-notification`);
                this.onSent = true;
                this.disabled = false;
            },

            async onSubmit() {
                if (this.checkVuelidate()) { return }
                await axios.patch(`/users/${this.user.id}`, this.submitObject)
                .then(res => {
                    this.onEditUser = false;
                    this.disabled = false
                })
                .catch(err => { this.onErrors(err) });
            },

            logout(){
                axios.post('/logout').
                    then(response => {
                        if (response.status === 302 || 401) {
                            window.location.href = '/';
                        } else {
                        // throw error and go to catch block
                        }
                    }).catch(error => {
                });
            },

            cleanDate(data) {
                return this.$dayjs(data).format("YYYY");
            }
            
        },

        mounted() {
            this.autocomplete = new google.maps.places.Autocomplete(
                (this.$refs.autocomplete),
                {
                    types: ['geocode'],
                    // componentRestrictions: {country: 'us'}
                },
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
                email: {
                    required,
                    email,
                    auth() {
                        return this.auth ? this.auth !== this.user.id : true
                    }
                }
            },
        },
    };
</script>