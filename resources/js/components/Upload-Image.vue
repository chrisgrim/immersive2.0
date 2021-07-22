<template>
    <div class="default__image-upload-container">
        <label 
            class="default__image-upload" 
            :style="backgroundImage">
            <svg v-if="!hasImage">
                <use :xlink:href="`/storage/website-files/icons.svg#ri-image-line`" />
            </svg>
            <image-upload @loaded="loaded" />
            <CubeSpinner :loading="loading" />
        </label>
        <div v-if="$v.imageFile.$error" class="validation-error image">
            <p class="error" v-if="!$v.imageFile.fileSize">The image file size is over 10mb</p>
            <p class="error" v-if="!$v.imageFile.fileType">The image needs to be a JPG, PNG or GIF</p>
            <p class="error" v-if="!$v.imageFile.imageRatio">The image needs to be at least {{width}} x {{height}}</p>
            <p class="error" v-if="!$v.imageFile.imageRequired">An Image is required</p>
        </div>
    </div>
</template>

<script>
    import CubeSpinner  from '../pages/layouts/loading.vue'
    import ImageUpload from '../pages/layouts/image-upload.vue'

    export default {

        components: { CubeSpinner, ImageUpload },

        props: {
            height: {
              type: Number,
              default: 500
            },
            width: {
              type: Number,
              default: 800
            },
            size: {
                type: Number,
                default: 10485760
            },
            image: {
                type: String,
                default: null
            },
            validate: {
                type: Boolean,
                default: false
            }
        },

        computed: {
            hasImage() {
                return this.image || this.imageFile.src ? true : false;
            },
            backgroundImage() {
                return `backgroundImage: url('${this.imageFile.src && !this.$v.imageFile.$error ? this.imageFile.src : this.image}')`
            },
        },

        
        data() {
            return {
                loading: false,
                imageFile: '',
                formData: new FormData(),
                updated: false,
            };
        },

        methods: {
            loaded(image) {
                this.imageFile = image; 
                this.$v.$touch(); 
                if (this.$v.$invalid) { return }
                this.$emit('addImage', this.imageFile.file, this.imageFile.src)
            },

            onToggle() {
                this.loading = !this.loading;
                this.disabled = !this.disabled;
            },
            checkValidation() {
                this.$v.$touch(); 
            }
        },

        watch: {
            validate() {
                this.checkValidation()
            }
        },

        validations: {
            imageFile: {
                fileSize() { 
                    return this.imageFile ? this.imageFile.file.size < this.size : true 
                },
                fileType() {
                    return this.imageFile ? ['image/jpeg','image/png','image/gif'].includes(this.imageFile.file.type) : true
                },
                imageRatio() {
                    return this.imageFile ? this.imageFile.width >= this.width && this.imageFile.height >= this.height : true 
                },
                imageRequired() {
                    return this.imageFile
                }
            },
        },
    };
</script>

