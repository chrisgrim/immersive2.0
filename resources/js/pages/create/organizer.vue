<template>
    <div>
        <div class="event-create__organizer">
            <div class="title">
                <h2>Organizer</h2>
            </div>
            <div class="event-create__organizer-container grid">
                <section class="event-enter-organization">
                    <div class="floating-form">
                        <div 
                            @mouseover="editButton=true" 
                            v-if="approved" 
                            class="field">
                            <p class="name">
                                {{ organizer.name }}
                            </p>
                            <button 
                                class="editTitle" 
                                v-if="editButton" 
                                @click.prevent="modal=true">
                                Edit
                            </button>
                        </div>
                        <div 
                            v-else 
                            class="field">
                            <label>Organization name</label>
                            <input 
                                type="text" 
                                placeholder=" " 
                                name="name"
                                :class="{ 'error': $v.organizer.name.$error }"
                                @input="$v.organizer.name.$touch"
                                @click="toggle()"
                                v-model="organizer.name">
                            <div v-if="$v.organizer.name.$error" class="validation-error">
                                <p class="error" v-if="!$v.organizer.name.required">The name is required</p>
                                <p class="error" v-if="!$v.organizer.name.serverFailed">The name needs to be unique</p>
                                <p class="error" v-if="!$v.organizer.name.maxLength">The name is too long</p>
                            </div>
                        </div>
                        <div class="field">
                            <label>Organization description</label>
                            <textarea 
                                type="text"
                                name="description" 
                                v-model="organizer.description" 
                                placeholder=" "
                                :class="{ 'error': $v.organizer.description.$error }"
                                @input="$v.organizer.description.$touch"
                                rows="8" />
                            <div v-if="$v.organizer.description.$error" class="validation-error">
                                <p class="error" v-if="!$v.organizer.description.required">The description is required</p>
                            </div>
                        </div>
                        <div class="field">
                            <label>Your organization website (recommended)</label>
                            <input 
                                type="url" 
                                v-model="organizer.website" 
                                name="website"
                                :class="{ 'error': $v.organizer.$error && $v.organizer.website.$error }"
                                @input="$v.organizer.website.$touch"
                                @click="toggle()"
                                placeholder=" ">
                            <div v-if="$v.organizer.$error && $v.organizer.website.$error" class="validation-error">
                                <p class="error" v-if="!$v.organizer.website.url">Must be a url (Needs http://)</p>
                                <p class="error" v-if="!$v.organizer.website.notWorking">The url doesn't seem to be working</p>
                            </div>
                        </div>
                        <div class="field">
                            <label>Your organization email (optional)</label>
                            <input 
                                type="text" 
                                v-model="organizer.email" 
                                name="email"
                                :class="{'error': $v.organizer.$error && $v.organizer.email.$error }"
                                @input="$v.organizer.email.$touch"
                                placeholder=" ">
                            <div v-if="$v.organizer.$error && $v.organizer.email.$error" class="validation-error">
                                <p class="error" v-if="!$v.organizer.email.email">Must be email</p>
                            </div>
                        </div>
                        <div class="field">
                            <label>Twitter handle (optional)</label>
                            <div class="inline input">
                                <p>
                                    https://www.twitter.com/
                                </p>
                                <input 
                                    type="text" 
                                    v-model="organizer.twitterHandle" 
                                    name="twitterHandle"
                                    @input="$v.organizer.twitterHandle.$touch"
                                    :class="{ 'error': $v.organizer.twitterHandle.$error}"
                                    placeholder=" ">
                                <div v-if="$v.organizer.twitterHandle.$error" class="validation-error">
                                    <p class="error" v-if="!$v.organizer.twitterHandle.ifHttp">Please only include the social media handle (no urls or @)</p>
                                </div>
                            </div>
                        </div>
                        <div class="field">
                            <label>Facebook handle (optional)</label>
                            <div class="inline input">
                                <p>
                                    https://www.facebook.com/
                                </p>
                                <input 
                                    type="text" 
                                    v-model="organizer.facebookHandle" 
                                    name="facebookHandle"
                                    @input="$v.organizer.facebookHandle.$touch"
                                    :class="{ 'error': $v.organizer.facebookHandle.$error }"
                                    placeholder=" ">
                                <div v-if="$v.organizer.facebookHandle.$error" class="validation-error">
                                    <p class="error" v-if="!$v.organizer.facebookHandle.ifHttp">Please only include the social media handle (no urls or @)</p>
                                </div>
                            </div>
                        </div>
                        <div class="field">
                            <label>Instagram handle (optional)</label>
                            <div class="inline input">
                                <p>
                                    https://www.instagram.com/
                                </p>
                                <input 
                                    type="text" 
                                    v-model="organizer.instagramHandle" 
                                    name="instagramHandle"
                                    @input="$v.organizer.instagramHandle.$touch"
                                    :class="{'error': $v.organizer.instagramHandle.$error }"
                                    placeholder=" ">
                                <div v-if="$v.organizer.instagramHandle.$error" class="validation-error">
                                    <p class="error" v-if="!$v.organizer.instagramHandle.ifHttp">Please only include the social media handle (no urls or @)</p>
                                </div>
                            </div>
                        </div>
                        <div class="field">
                            <label>Organization Patreon (optional)</label>
                            <div class="inline input">
                                <p>
                                    https://www.patreon.com/
                                </p>
                                <input 
                                    type="text" 
                                    v-model="organizer.patreon" 
                                    name="patreon"
                                    @input="$v.organizer.patreon.$touch"
                                    :class="{ 'error': $v.organizer.patreon.$error }"
                                    placeholder="">
                                <div v-if="$v.organizer.patreon.$error" class="validation-error">
                                    <p class="error" v-if="!$v.organizer.patreon.ifHttp">
                                        Please use the full patreon link
                                    </p>
                                </div>
                            </div>
                        </div>
                    </div>
                </section>
                <section class="event-enter-organization-image">
                    <div class="add-organzation-image">          
                        <div class="add-organzation-image_loader">
                            <label class="" :style="displayImage">
                                <div class="add-organzation-image__card" :class="{ over: hasImage, load: isLoading }">
                                    <div class="add-organzation-image__dotted">
                                        <div class="add-organzation-image__text">
                                            <div>
                                                <div v-if="!hasImage">
                                                    <h4>Click to upload organization image (optional)</h4>
                                                    <div v-if="!isLoading">
                                                        <svg class="b" height="32" width="32" viewBox="0 0 24 24" aria-label="Add an image or video" role="img"><path d="M24 12c0-6.627-5.372-12-12-12C5.373 0 0 5.373 0 12s5.373 12 12 12c6.628 0 12-5.373 12-12zm-10.767 3.75a1.25 1.25 0 0 1-2.5 0v-3.948l-1.031 1.031a1.25 1.25 0 0 1-1.768-1.768L12 7l4.066 4.065a1.25 1.25 0 0 1-1.768 1.768l-1.065-1.065v3.982z"></path></svg>
                                                    </div>
                                                </div>
                                                <p v-if="hasImage">Change Image</p>
                                            </div>
                                        </div>
                                    </div>
                                    <image-upload @loaded="onImageUpload" />
                                </div>
                                <CubeSpinner :loading="isLoading" />
                            </label>
                            <div class="field">
                                <div v-if="$v.imageFile.$error" class="validation-error">
                                    <p class="error" v-if="!$v.imageFile.fileSize">The Image is too large</p>
                                    <p class="error" v-if="!$v.imageFile.fileType">The Image needs to be a JPG, PNG or GIF</p>
                                    <p class="error" v-if="!$v.imageFile.imageSize">The image needs to be at least 400 x 400</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </section>
                <div class="create-button__back">
                    <button v-if="this.loadorganizer" class="create" @click.prevent="goBack"> Back </button>
                </div>
                <div class="create-button__forward">
                    <button :disabled="disabled" class="create" @click.prevent="onSubmit"> Save Organizer </button>
                </div>
            </div>
        </div>
        <RenameModal
            v-if="modal"
            @close="modal=false"
            @onSubmit="onResubmit" />
    </div>
</template>

<script>
import _ from 'lodash'
import ImageUpload from '../layouts/image-upload.vue'
import RenameModal from './components/modal-organizer.vue'
import CubeSpinner  from '../layouts/loading.vue'
import { required, minLength, maxLength, url, email } from 'vuelidate/lib/validators'
import formValidationMixin from '../../mixins/form-validation-mixin'

export default {
    mixins: [formValidationMixin],
    
    props: ['user', 'loadorganizer'],

    components: { ImageUpload, CubeSpinner, RenameModal },

    computed: {
        hasImage() {
            return this.organizer.largeImagePath || this.imageFile.src ? true : false;
        },

        displayImage() {
            if (this.imageFile.src) {
                if (!this.$v.imageFile.$invalid) {
                    return `backgroundImage: url('${this.imageFile.src}')`
                }
            } else {
                return `backgroundImage: url('${(this.loadorganizer ? this.$envImageUrl + this.loadorganizer.largeImagePath : '')}')`
            }
        },

        endPoint() {
            return this.loadorganizer ? `/organizer/${this.loadorganizer.slug}/patch` : '/organizer';
        },

    },
    
    data() {
        return {
            organizer: this.loadorganizer ? this.loadorganizer : this.initializeOrganizerObject(),
            active: '',
            serverErrors: [],
            isLoading: false,
            imageFile: '',
            disabled: false,
            approved: this.loadorganizer ? true : false,
            editButton: false,
            modal: false,
            reSubmit: false,
            formData: new FormData(),
        };
    },

    methods: {

        //on page load creates an empty organizer object
        initializeOrganizerObject() {
            return {
                id: '',
                name: '',
                description: '',
                website: '',
                email: '',
                imagePath: '',
                twitterHandle: '',
                facebookHandle: '',
                instagramHandle: '',
                user_id:''
            }
        },

        toggle() {
            this.serverErrors = [];
        },

        onResubmit() {
            this.resubmit = 'resubmit';
            this.approved = false;
            this.modal = false;
        },

        onImageUpload(image) {
            this.imageFile = image; 
            this.$v.imageFile.$touch();
            if (this.$v.imageFile.$invalid) { return false; }
            this.formData.append('image', this.imageFile.file);
        },

        onSubmit() {
            if (this.checkVuelidate()) { return false }
            this.appendData()
            axios.post(this.endPoint, this.formData)
            .then(res => { 
                this.onFinishOrganizer('/create/events/edit');
            })
            .catch(err => {
                this.onErrors(err);
            });
        },

        appendData() {
            this.imageFile ? this.isLoading = true : '';
            for (var field in this.organizer) {
                if (this.organizer[field] !== null) {
                    this.formData.append(field, this.organizer[field]);
                } 
            }
        },

        onErrors(errors) {
            errors.response.data.message.length ? this.serverErrors = {broken: 'Url is broken'} : '';
            errors.response.data.errors ? this.serverErrors = errors.response.data.errors : '';
            this.isLoading = false;
            this.disabled = false;
        },

        goBack() {
            window.location.href = '/create/events/edit';
        },

        validateText(str) {
            return str && str.startsWith("http") || str && str.startsWith("@") ? true : false
        },

        //checks to see if passed variable is in the server errors
        hasServerError(field) {
            return (field && _.has(this, 'serverErrors.' + field) && !_.isEmpty(this.serverErrors[field]));
        },
    },

    validations: {
        imageFile: {
            fileSize() { 
                return this.imageFile ? this.imageFile.file.size < 20971520 : true 
            },
            fileType() {
                return this.imageFile ? ['image/jpeg','image/png','image/gif', 'image/webp'].includes(this.imageFile.file.type) : true
            },
            imageSize() {
                return this.imageFile ? this.imageFile.width > 400 && this.imageFile.height > 400 :  true
            }
        },
        organizer: {
            description: {
               required,
               maxLength: maxLength(10000)
            },
            name: {
               required,
               maxLength: maxLength(60),
               serverFailed : function(){ return !this.hasServerError('name'); },
            },
            website: {
                // required,
                url,
                // serverFailed : function(){ return !this.hasServerError('website'); },
                notWorking : function(){ return !this.hasServerError('broken'); },
            },
            email: {
                email,
            },
            twitterHandle: {
                ifHttp() {
                    return this.validateText(this.organizer.twitterHandle) ? false : true
                }
            },
            facebookHandle: {
                ifHttp() {
                    return this.validateText(this.organizer.facebookHandle) ? false : true
                }
            },
            instagramHandle: {
                ifHttp() {
                    return this.validateText(this.organizer.instagramHandle) ? false : true
                }
            },
            patreon: {
                ifHttp() {
                    return this.validateText(this.organizer.patreon) ? false : true
                }
            }
        },
    },
};
</script>