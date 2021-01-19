<template>
    <div class="user-profile grid">
        <section 
            class="user-profile-image" 
            :style="`background:${user.hexColor}`">
            <!-- Non Editable User Image --> 
            <template v-if="!canEditPage">
                <template v-if="avatar">
                    <picture>
                        <source 
                            type="image/webp" 
                            :srcset="`/storage/${avatar}`"> 
                        <img 
                            class="user-profile-image" 
                            :src="`/storage/${avatar.slice(0, -4)}jpg`" 
                            :alt="`${loaduser.name}`">
                    </picture>
                </template>
                <template v-else-if="user.gravatar">
                    <picture>
                        <img 
                            class="user-profile-image" 
                            :src="user.gravatar" 
                            :alt="`${loaduser.name}`">
                    </picture>
                </template>
                <div 
                    v-else 
                    class="user-profile-noimage__text">
                    <h2>{{ loaduser.name.charAt(0) }}</h2>
                </div>
            </template>
            
            <!-- Editable User Image --> 
            <div 
                v-else 
                class="user-profile-image">  
                <label 
                    v-if="avatar"
                    class="user-profile-image__wrapper"
                    :class="{ imageloaded: avatar, imageloading: uploading }"
                    :style="`background:url('/storage/${avatar}')`">
                    <image-upload @loaded="onImageUpload" />
                    <CubeSpinner :loading="loading" />
                    <span class="user-profile-image__update-text">
                        <p v-if="uploading">Loading</p>
                        <p v-else>Update</p>
                    </span>
                </label>

                <label
                    v-else-if="user.gravatar"
                    class="user-profile-image__wrapper"
                    :class="{ imageloaded: avatar, imageloading: uploading }"
                    :style="`background:url('${user.gravatar}')center no-repeat;background-size: cover;`">
                    <image-upload @loaded="onImageUpload" />
                    <CubeSpinner :loading="loading" />
                    <span class="user-profile-image__update-text">
                        <p v-if="uploading">Loading</p>
                        <p v-else>Update</p>
                    </span>

                </label>
                <div 
                    v-else 
                    class="user-profile-image">
                    <div 
                        class="user-profile-noimage__text" 
                        :style="`background:${user.hexColor}`">
                        <h2>{{ loaduser.name.charAt(0) }}</h2>
                    </div>
                    <label 
                        class="profile-wrapper"
                        :class="{ imageloaded: avatar, imageloading: uploading }">
                        <image-upload @loaded="onImageUpload" />
                        <CubeSpinner :loading="loading" />
                        <span class="user-profile-image__update-text">
                            <p v-if="uploading">Loading</p>
                            <p v-else>Update</p>
                        </span>
                    </label>
                </div>


                <div v-if="$v.imageFile.$error" class="validation-error">
                    <p class="error" v-if="!$v.imageFile.fileSize">Image size is over 20MB</p>
                    <p class="error" v-if="!$v.imageFile.fileType">Needs to be a Jpg, Png or Gif</p>
                    <p class="error" v-if="!$v.imageFile.imageRatio">Needs to be at least 400 x 400</p>
                    <p class="error" v-if="!$v.imageFile.auth">you don't have permission to edit</p>
                </div>
            </div>
        </section>

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
                        v-if="userOwnsPage && !user.email_verified_at && !onSent">
                        Please verify your account.
                    </button>
                    <div 
                        class="ver a" 
                        v-if="userOwnsPage && !user.email_verified_at && onSent">
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
    import ImageUpload from '../layouts/image-upload.vue'
    import { required, maxLength, email } from 'vuelidate/lib/validators'
    import CubeSpinner  from '../layouts/loading.vue'
    import profileLocationMixin from './components/profile-location-mixin'
    import formValidationMixin from '../../mixins/form-validation-mixin'


    export default {

        props: ['loaduser', 'events', 'auth'],

        mixins: [profileLocationMixin, formValidationMixin],

        components: { ImageUpload, CubeSpinner },

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
            },

            userOwnsPage() {
                return parseFloat(this.auth) == this.loaduser.id ? true : false
            },

            canEditPage() {
                return this.userOwnsPage && this.user.email_verified_at
            },

            submitObject() {
                return {
                    name: this.user.name,
                    location:this.location,
                    email: this.user.email,
                }
            },

            endPoint() {
                return `/users/${this.user.id}`;
            }
        },

        data() {
            return {
                user: this.loaduser,
                avatar: this.loaduser.thumbImagePath ? this.loaduser.thumbImagePath : '',
                location: this.loaduser.location ?  this.loaduser.location : this.initializeLocationObject(),
                imageFile: '',
                active: null,
                loading: false,
                serverErrors: [],
                onEditUser: false,
                uploading: false,
                onSent: false,
                disabled: false,
                webp: '',
                formData: new FormData(),
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

            resend() {
                this.disabled = true;
                axios.post(`/email/resend`)
                .then(response => {
                    this.onSent = true;
                    this.disabled = false;
                    console.log(response.data)
                })
                .catch(errorResponse => { 
                    console.log(errorResponse.data);
                })
            },

            onImageUpload(image) {
                this.imageFile = image;
                this.$v.$touch(); 
                if (this.$v.$invalid) { return false }
                this.onAddImage();
            },

            onAddImage() {
                this.onToggle();
                this.formData.append('image', this.imageFile.file);
                this.formData.append('_method', 'PATCH');
                axios.post(this.endPoint, this.formData)
                .then( location.reload() )
                .catch(err => {  console.log(err); });
            },

            onSubmit() {
                if (this.checkVuelidate()) { return false }
                axios.patch(this.endPoint, this.submitObject)
                .then(res => {
                    this.onEditUser = false;
                    this.disabled = false
                })
                .catch(err => {
                    this.onErrors(err);
                });
            },

            onToggle() {
                this.uploading = true;
                this.loading = true;
                this.disabled = true;
            },

            canUseWebP() {
                let webp = (document.createElement('canvas').toDataURL('image/webp').indexOf('data:image/webp') == 0);
                if (this.loaduser.thumbImagePath && webp) {
                    return this.avatar = this.loaduser.thumbImagePath
                };
                if (this.loaduser.thumbImagePath) {
                    return this.avatar = `${this.loaduser.thumbImagePath.slice(0, -4)}jpg`
                }
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
            this.canUseWebP();
        },

        validations: {
            imageFile: {
                fileSize() { 
                    return this.imageFile ? this.imageFile.file.size < 20971520 : true 
                },
                fileType() {
                    return this.imageFile ? ['image/jpeg','image/png','image/gif'].includes(this.imageFile.file.type) : true
                },
                imageRatio() {
                    return this.imageFile ? this.imageFile.width > 500 && this.imageFile.height > 500 : true 
                },
                auth() {
                    return this.auth ? this.auth !== this.user.id : true
                },
            },
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