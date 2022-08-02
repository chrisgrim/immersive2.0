<template>
    <div>
        <label 
            class=" cursor-pointer justify-center items-center flex mb-8 rounded-full border w-full h-full bg-cover bg-center bg-no-repeat flex-col" 
            :style="backgroundImage">  
            <image-upload @loaded="loaded" />
            <CubeSpinner :loading="loading" />
        </label>
        <Errors
            item="Image"
            :errors="$v.imageFile" />
    </div>
</template>

<script>
    import CubeSpinner  from '../../../../components/Spinner.vue'
    import ImageUpload from '../../../../components/image-upload.vue'
    import Errors from '../../creationError.vue'

    export default {

        components: { CubeSpinner, ImageUpload, Errors },

        props: ['loading', 'image'],

        computed: {
            hasImage() {
                return this.image || this.imageFile.src ? true : false;
            },
            backgroundImage() {
                if (!this.hasImage) { return }
                if (this.imageFile.src && !this.$v.imageFile.$error) { 
                    return `backgroundImage: url('${this.imageFile.src}')` }
                if (this.image) {
                    return `backgroundImage: url('${this.image.slice(0, -4)}jpg?timestamp=${new Date().getTime()}')`
                }
                return ''
            },
        },

        
        data() {
            return {
                imageFile: '',
                formData: new FormData(),
                size: 10485760,
                width: 400,
                height: 400,
            };
        },

        methods: {
            loaded(image) {
                this.imageFile = image
                this.$v.$touch()
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
        },

        watch: {
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
                    return this.imageFile || this.image
                }
            },
        },
    };
</script>