<template>
    <div class="m-auto w-full px-8 md:py-8 md:px-12 lg:py-0 lg:px-32 lg:max-w-screen-xl lg:pt-24">
        <div class="flex flex-col md:flex-row gap-16">
            <div class="w-full inline-block md:w-2/6 md:px-8 lg:p-8">
                <section class="sticky top-16 items-center flex flex-col">
                    <CardImage
                        :loading="disabled"
                        text="The image must be at least 400px x 400px"
                        :image="image"
                        @addImage="addImage" />
                    <div class="mt-16">
                        <div class="flex items-center gap-4 w-full mb-8 relative">
                            <p>twitter.com/</p>
                            <input 
                                type="text" 
                                v-model="organizer.twitterHandle" 
                                name="twitterHandle"
                                class="h-12 border-b rounded-none mx-4" 
                                @input="$v.organizer.twitterHandle.$touch"
                                :class="{ 'error': $v.organizer.twitterHandle.$error}"
                                placeholder=" ">
                            <div v-if="$v.organizer.twitterHandle.$error" class="absolute bottom-[-2rem]">
                                <p class="text-red-500 text-lg" v-if="!$v.organizer.twitterHandle.ifHttp">Please only include the social media handle (no urls or @)</p>
                            </div>
                        </div>
                        <div class="flex items-center gap-4 w-full mb-8 relative">
                            <p>facebook.com/</p>
                            <input 
                                type="text" 
                                v-model="organizer.facebookHandle" 
                                name="facebookHandle"
                                @input="$v.organizer.facebookHandle.$touch"
                                class="h-12 border-b rounded-none mx-4" 
                                :class="{ 'error': $v.organizer.facebookHandle.$error }"
                                placeholder=" ">
                            <div v-if="$v.organizer.facebookHandle.$error" class="absolute bottom-[-2rem]">
                                <p class="text-red-500 text-lg" v-if="!$v.organizer.facebookHandle.ifHttp">Please only include the social media handle (no urls or @)</p>
                            </div>
                        </div>
                        <div class="flex items-center gap-4 w-full mb-8 relative">
                            <p>instagram.com/</p>
                            <input 
                                type="text" 
                                v-model="organizer.instagramHandle" 
                                name="instagramHandle"
                                @input="$v.organizer.instagramHandle.$touch"
                                class="h-12 border-b rounded-none mx-4" 
                                :class="{'error': $v.organizer.instagramHandle.$error }"
                                placeholder=" ">
                            <div v-if="$v.organizer.instagramHandle.$error" class="absolute bottom-[-2rem]">
                                <p class="text-red-500 text-lg" v-if="!$v.organizer.instagramHandle.ifHttp">Please only include the social media handle (no urls or @)</p>
                            </div>
                        </div>
                        <div class="flex items-center gap-4 w-full mb-8 relative">
                            <p>patreon.com/</p>
                            <input 
                                type="text" 
                                v-model="organizer.patreon" 
                                name="patreon"
                                @input="$v.organizer.patreon.$touch"
                                class="h-12 border-b rounded-none mx-4" 
                                :class="{ 'error': $v.organizer.patreon.$error }"
                                placeholder="">
                            <div v-if="$v.organizer.patreon.$error" class="absolute bottom-[-2rem]">
                                <p class="text-red-500 text-lg" v-if="!$v.organizer.patreon.ifHttp">
                                    Please use the full patreon link
                                </p>
                            </div>
                        </div>
                    </div>
                </section>
            </div>
            <div class="w-full inline-block md:w-4/6">
                <div class="title">
                    <h2>Create Organizer</h2>
                </div>
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
                </div>
                <div>
                    <button 
                        v-if="$v.organizer.$anyDirty"
                        :disabled="disabled || $v.organizer.$anyError" 
                        class="border-none bg-gradient-to-r from-button-red-1 via-button-red-2 to-button-red-3 text-white disabled:bg-slate-50" 
                        @click.prevent="submitOrganizer"> 
                        Save Organizer 
                    </button>
                    <button @click="onBack">
                        Back
                    </button>
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
import CardImage from './Create/vue-add-image.vue'
import RenameModal from './Create/vue-name-edit-modal.vue'
import { required, minLength, maxLength, url, email } from 'vuelidate/lib/validators'
import formValidationMixin from '../../mixins/form-validation-mixin'

export default {
    mixins: [formValidationMixin],
    
    props: ['user', 'loadorganizer', 'mobile'],

    components: { RenameModal, CardImage },
    
    data() {
        return {
            organizer: this.loadorganizer ? this.loadorganizer : this.initializeOrganizerObject(),
            formData: new FormData(),
            serverErrors: null,
            isLoading: false,
            disabled: false,
            approved: this.loadorganizer ? true : false,
            editButton: false,
            modal: false,
            reSubmit: false,
            endPoint: this.loadorganizer ? `/organizer/${this.loadorganizer.slug}/patch` : '/organizer',
            image: this.loadorganizer && this.loadorganizer.largeImagePath ? `/storage/${this.loadorganizer.largeImagePath}` : null,
        };
    },

    methods: {
        async submitOrganizer() {
            if ( this.checkVuelidate()) { return }
            this.appendData()
            axios.post(this.endPoint, this.formData)
            .then( res => {
                window.location.href = `/create/events/edit`
            })
            .catch(err => {
                this.onErrors(err);
            });
        },
        onBack() {
            window.location.href = `/create/events/edit`
        },
        addImage(image) {
            this.$v.organizer.$touch()
            this.disabled = false
            this.formData.append('image', image);
        },
        appendData() {
            this.disabled = true
            for (var field in this.organizer) {
                if (this.organizer[field] !== null) {
                    this.formData.append(field, this.organizer[field]);
                } 
            }
        },
        onResubmit() {
            this.resubmit = 'resubmit';
            this.approved = false;
            this.modal = false;
        },
        toggle() {
            this.serverErrors = [];
        },
        onErrors(errors) {
            errors.response.data.message.length ? this.serverErrors = {broken: 'Url is broken'} : '';
            errors.response.data.errors ? this.serverErrors = errors.response.data.errors : '';
            this.isLoading = false;
            this.disabled = false;
        },
        validateText(str) {
            return str && str.startsWith("http") || str && str.startsWith("@") || str && str.startsWith("www.") ? true : false
        },
        //checks to see if passed variable is in the server errors
        hasServerError(field) {
            return (field && _.has(this, 'serverErrors.' + field) && !_.isEmpty(this.serverErrors[field]));
        },
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
    },

    validations: {
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
                url,
                maxLength: maxLength(1000),
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