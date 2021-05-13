<template>
    <div class="event-create__image">
        <section class="field">
            <div class="event-create-image"> 
                <h2>Upload Image</h2>
                <div class="field">
                    <label> Would you like to add a youtube video? </label>
                    <div id="cover">
                        <input  
                            v-model="hasVideo" 
                            type="checkbox" 
                            id="checkbox">
                        <div id="bar" />
                        <div id="knob">
                            <p v-if="hasVideo">Yes</p>
                            <p v-else>No</p>
                        </div>
                    </div>
                </div>     
                <template v-if="hasVideo">
                    <div class="field">
                        <div class="inline input">
                            <p>
                                https://youtu.be/
                            </p>
                            <input 
                                type="text" 
                                v-model="video" 
                                name="video"
                                @input="$v.video.$touch"
                                :class="{ 'error': $v.video.$error }"
                                placeholder="eg: q9fQW4VFNk8Y">
                            <div v-if="$v.video.$error" class="validation-error">
                                <p class="error" v-if="!$v.video.ifHttp">Please only include the youtube video id (no urls or @)</p>
                            </div>
                        </div>
                    </div>
                </template> 
                <div class="image-loader">
                    <label 
                        class="image-loader__label" 
                        :style="displayImage">
                        <div 
                            class="image-loader__label-body" 
                            :class="{ over: hasImage, load: loading }">
                            <div class="box">
                                <div class="in">
                                    <div 
                                        class="add-image-button" 
                                        v-if="!hasImage && !loading">
                                        <svg 
                                            class="b" 
                                            height="32" 
                                            width="32" 
                                            viewBox="0 0 24 24" 
                                            aria-label="Add an image or video" 
                                            role="img">
                                            <path d="M24 12c0-6.627-5.372-12-12-12C5.373 0 0 5.373 0 12s5.373 12 12 12c6.628 0 12-5.373 12-12zm-10.767 3.75a1.25 1.25 0 0 1-2.5 0v-3.948l-1.031 1.031a1.25 1.25 0 0 1-1.768-1.768L12 7l4.066 4.065a1.25 1.25 0 0 1-1.768 1.768l-1.065-1.065v3.982z" />
                                        </svg>
                                        Upload Image (10mb max)
                                    </div>
                                    <div 
                                        class="add-image-button" 
                                        v-if="hasImage && !loading">
                                        <svg 
                                            class="b" 
                                            height="32" 
                                            width="32" 
                                            viewBox="0 0 24 24" 
                                            aria-label="Add an image or video" 
                                            role="img">
                                            <path d="M24 12c0-6.627-5.372-12-12-12C5.373 0 0 5.373 0 12s5.373 12 12 12c6.628 0 12-5.373 12-12zm-10.767 3.75a1.25 1.25 0 0 1-2.5 0v-3.948l-1.031 1.031a1.25 1.25 0 0 1-1.768-1.768L12 7l4.066 4.065a1.25 1.25 0 0 1-1.768 1.768l-1.065-1.065v3.982z" />
                                        </svg>
                                        Change Image (10mb max)
                                    </div>
                                </div>
                            </div>
                            <image-upload @loaded="onImageUpload" />
                            <CubeSpinner :loading="loading" />
                        </div>
                    </label>
                    <div>
                        <div v-if="$v.imageFile.$error" class="validation-error image">
                            <p class="error" v-if="!$v.imageFile.fileSize">The image file size is over 10mb</p>
                            <p class="error" v-if="!$v.imageFile.fileType">The image needs to be a JPG, PNG or GIF</p>
                            <p class="error" v-if="!$v.imageFile.imageRatio">The image needs to be at least 800 x 450</p>
                        </div>
                    </div>
                </div>
                <div class="image-display-example">
                    <div class="iphone-example">
                        <img
                            class="underlay" 
                            :src="displayExample">
                        <img 
                            class="overlay" 
                            src="/storage/website-files/iphone-example.png">
                    </div>
                    <div class="computer-example">
                        <img
                            class="underlay" 
                            :src="displayExample">
                        <img 
                            class="overlay" 
                            src="/storage/website-files/computer-example.png">
                    </div>
                </div>  
            </div>
        </section>
        <Submit 
            @submit="onSubmit"
            :disabled="disabled" 
            :ready="readySubmit"
            previous="advisories"
            next="review"
            :event="event" />
        <transition name="slide-fade">
            <div v-if="updated" class="updated-notifcation">
                <p>Your event has been updated.</p>
            </div>
        </transition>
        <transition name="slide-fade">
            <div v-if="imageUpdated" class="updated-notifcation">
                <p>Image has been added</p>
            </div>
        </transition>
    </div>
</template>

<script>
    import Submit  from './components/submit-buttons.vue'
    import formValidationMixin from '../../mixins/form-validation-mixin'
    import { required } from 'vuelidate/lib/validators'
    import CubeSpinner  from '../layouts/loading.vue'
    import ImageUpload from '../layouts/image-upload.vue'

    export default {

        mixins: [formValidationMixin],

        components: { CubeSpinner, Submit, ImageUpload },
        
        props: ['event'],

        computed: {
            hasImage() {
                return this.image || this.imageFile.src ? true : false;
            },

            displayImage() {
                return `backgroundImage: url('${this.imageFile.src && !this.$v.imageFile.$error ? this.imageFile.src : this.image}')`
            },

            displayExample() {
                return `${this.imageFile.src && !this.$v.imageFile.$error ? this.imageFile.src : this.image}`
            },

            readySubmit() {
                return this.readyToSubmit && this.imageAdded ? false : true;
            },

            eventPublished() {
                return this.event.status == 'p' || this.event.status == 'e' ? false : true;
            }
        },

        
        data() {
            return {
                image: this.event.largeImagePath ? `/storage/${this.event.largeImagePath.slice(0, -4)}jpg` : '',
                loading: false,
                disabled: false,
                readyToSubmit: false,
                imageFile: '',
                formData: new FormData(),
                imageAdded: this.event.largeImagePath ? true : false,
                updated: false,
                imageUpdated: false,
                approved: this.event.status == 'p' || this.event.status == 'e' ? true : false,
                hasVideo: this.event.video ? true : false,
                video: this.event.video ? this.event.video  : ''
            };
        },

        methods: {
            onImageUpload(image) {
                this.imageFile = image; 
                this.$v.$touch(); 
                if (this.$v.$invalid) { return }
                this.formData.append('image', this.imageFile.file);
                this.submitImage();
            },

            submitImage() {
                this.onToggle();
                axios.post(`/create/${this.event.slug}/add-images`, this.formData)
                .then(res => {
                    console.log(res.data);
                    res.data.status >= 8 ? this.readyToSubmit = true : false;
                    this.showImageUpdatedModal()
                    this.imageAdded = true;
                    this.onToggle();
                })
                .catch(err => { this.onErrors(err) });
            },

            onSubmit(value) {
                this.onToggle();
                this.$v.$touch(); 
                if (this.$v.$invalid) { return }
                axios.post(`/create/${this.event.slug}/images`, {video: this.hasVideo ? this.video : null})
                .then(res => {
                    if (this.approved) {this.showUpdatedModal()}
                    value == 'save' ? this.updateImage() : this.onForward(value);
                })
                .catch(err => { this.onErrors(err) });
            },

            showUpdatedModal() {
                this.updated = true; 
                setTimeout(() => this.updated = false, 3000)
            },

            showImageUpdatedModal() {
                this.imageUpdated = true; 
                setTimeout(() => this.imageUpdated = false, 3000)
            },

            navChange(value) {
                this.onForward(value);
            },

            onToggle() {
                this.loading = !this.loading;
                this.disabled = !this.disabled;
            },

            updateImage() {
                this.checkResubmitStatus();
                this.disabled = false;
                this.loading = !this.loading;
                this.updated = true;
                setTimeout(() => this.updated = false, 3000);
            },

            validateText(str) {
                return str && str.startsWith("http") || str && str.startsWith("@") ? true : false
            },

            checkSubmissionStatus() {
                this.event.status >= '8' ? this.readyToSubmit = true : false;
            },

        },

        created() {
            this.checkSubmissionStatus();
            this.disabled = false;
        },

        watch: {
            '$store.state.navurl'() {
                this.onBack(this.$store.state.navurl.page)
            }
        },

        validations: {
            video: {
                ifHttp() {
                    return this.validateText(this.video) ? false : true
                }
            },
            imageFile: {
                fileSize() { 
                    return this.imageFile ? this.imageFile.file.size < 10485760 : true 
                },
                fileType() {
                    return this.imageFile ? ['image/jpeg','image/png','image/gif'].includes(this.imageFile.file.type) : true
                },
                imageRatio() {
                    return this.imageFile ? this.imageFile.width >= 800 && this.imageFile.height >= 450 : true 
                }
            },
        },
    };
</script>