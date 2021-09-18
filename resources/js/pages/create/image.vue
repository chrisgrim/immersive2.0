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
                <CardImage
                    text="Image must be at least 1200px by 450px"
                    :height="450"
                    :width="1200"
                    :image="`/storage/${event.largeImagePath}`"
                    :external-submit="checkImage"
                    @addImage="addImage" />
            </div>
        </section>
        <Submit 
            @submit="onSubmit"
            :disabled="disabled" 
            :ready="ready"
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
    import CardImage from '../../components/Upload-Image.vue'

    export default {

        mixins: [formValidationMixin],

        components: { Submit, CardImage },
        
        props: ['loadevent'],

        computed: {
            ready() {
                return this.event.status >= 8 && this.event.thumbImagePath.length
            },
        },
        
        data() {
            return {
                event: this.loadevent,
                loading: false,
                disabled: false,
                isPublished: this.loadevent.status == 'p' || this.loadevent.status == 'e',
                checkImage: false,
                formData: new FormData(),
                updated: false,
                imageUpdated: false,
                hasVideo: this.loadevent.video ? true : false,
                video: this.loadevent.video ? this.loadevent.video  : ''
            };
        },

        methods: {
            async addImage(image) {
                this.formData.append('image', image)
                this.disabled = true
                await axios.post(`/create/${this.event.slug}/add-images`, this.formData)
                .then(res => {
                    this.event = res.data
                    this.showImageUpdatedModal()
                    this.disabled = false
                })
            },
            async onSubmit(value) {
                if ( this.checkVuelidate() ) { return }
                await axios.post(`/create/${this.event.slug}/images`, { video: this.hasVideo ? this.video : null })
                if (this.isPublished) { return this.showUpdatedModal() }
                this.onForward(value)
            },
            showUpdatedModal() {
                this.updated = true
                this.disabled = false
                setTimeout(() => this.updated = false, 3000)
            },
            showImageUpdatedModal() {
                this.imageUpdated = true
                this.disabled = false
                setTimeout(() => this.imageUpdated = false, 3000)
            },
            navChange(value) {
                this.onForward(value);
            },
            updateImage() {
                this.checkResubmitStatus()
                this.disabled = false
                this.updated = true
                setTimeout(() => this.updated = false, 3000)
            },
            validateText(str) {
                return str && str.startsWith("http") || str && str.startsWith("@") ? true : false
            },

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
        },
    };
</script>