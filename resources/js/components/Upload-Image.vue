<template>
    <div 
        @mouseover="overImage = true"
        @mouseleave="overImage = false"
        :class="{ hoverImage: overImage && !locked }"
        class="default__image-upload-container">
        <template v-if="canDelete && overImage">
            <div class="delete-btn">
                <button 
                    @click="onDelete"
                    class="btn-icon">
                    <svg class="w-16 h-16">
                        <use :xlink:href="`/storage/website-files/icons.svg#ri-close-line`" />
                    </svg>
                </button>
            </div>
        </template>
        <label 
            :class="[ overImage ? 'before:bg-[#fffefe5c] before:absolute before:w-full before:h-full' : '', !locked ? 'cursor-pointer' : '']"
            class="default__image-upload justify-center items-center" 
            :style="backgroundImage">
            <template v-if="!locked">
                <svg 
                    class="w-16 h-16 fill-white"
                    v-if="!hasImage || overImage">
                    <use :xlink:href="`/storage/website-files/icons.svg#ri-image-line`" />
                </svg>
                <p v-if="text && !image"> {{ text }} </p>
                <image-upload @loaded="loaded" />
                <CubeSpinner :loading="loading" />
            </template>
        </label>
        <div v-if="$v.imageFile.$error" class="validation-error image">
            <p class="error" v-if="!$v.imageFile.fileSize">The image file size is over 10mb</p>
            <p class="error" v-if="!$v.imageFile.fileType">The image needs to be a JPG, PNG or GIF</p>
            <p class="error" v-if="!$v.imageFile.imageRatio">The image needs to be at least {{ width }} x {{ height }}</p>
            <p class="error" v-if="!$v.imageFile.imageRequired">An Image is required</p>
        </div>
    </div>
</template>

<script>
    import CubeSpinner  from './Spinner.vue'
    import ImageUpload from './image-upload.vue'

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
                default: true
            },
            externalSubmit: {
                type: Boolean,
                default: false,
            },
            text: {
                type: String,
                default: null
            },
            canDelete: {
                type: Boolean,
                default: false,
            },
            locked: {
                type: Boolean,
                default: false,
            },
            loading: {
                type: Boolean,
                default: false,
            }
        },

        computed: {
            hasImage() {
                return this.image || this.imageFile.src ? true : false;
            },
            backgroundImage() {
                if (!this.hasImage) { return }
                if (this.imageFile.src && !this.$v.imageFile.$error) { return `backgroundImage: url('${this.imageFile.src}')` }
                return `backgroundImage: url('${this.image.slice(0, -4)}jpg?timestamp=${new Date().getTime()}')`
            },
        },

        
        data() {
            return {
                imageFile: '',
                formData: new FormData(),
                updated: false,
                overImage: false,
            };
        },

        methods: {
            loaded(image) {
                this.imageFile = image
                if (this.validate) { this.$v.$touch() }
                if (this.$v.$invalid) { return this.inProcess = !this.inProcess }
                this.$emit('addImage', this.imageFile.file, this.imageFile.src)
            },
            onDelete() {
                this.$emit('onDelete')
                this.imageFile = ''
            },
            onToggle() {
                this.inProcess = !this.inProcess;
                this.disabled = !this.disabled;
            },
            reset() {
                this.imageFile = '';
            }
        },

        watch: {
            externalSubmit() {
                this.$v.$touch(); 
            },
            image() {
                if (this.image === `/storage/null`) {this.reset()}
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

