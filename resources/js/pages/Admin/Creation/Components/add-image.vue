<template>
    <div 
        @mouseover="overImage = true"
        @mouseleave="overImage = false"
        :class="{ '': overImage }"
        class="relative w-96 h-96">
        <label 
            :class="{ 'before:bg-[#aeaeae5c] before:absolute before:w-full before:h-full': overImage }"
            class=" cursor-pointer justify-center items-center flex mb-8 w-full h-full bg-cover bg-center bg-no-repeat flex-col bg-slate-400" 
            :style="backgroundImage">  
            <div v-if="!hasImage || overImage">
                <svg class="w-16 h-16 m-auto fill-white">
                    <use :xlink:href="`/storage/website-files/icons.svg#ri-image-line`" />
                </svg>
                <p class="text-lg text-white"> {{ text && !hasImage ? text : null }} </p>
            </div>
            <image-upload @loaded="loaded" />
            <CubeSpinner :loading="loading" />
        </label>
        <div v-if="$v.imageFile.$error" class="absolute w-96 h-36 rounded-2xl inset-0 m-auto p-4 bg-white">
            <p class="text-red-600" v-if="!$v.imageFile.fileSize">The image file size is over 10mb</p>
            <p class="text-red-600" v-if="!$v.imageFile.fileType">The image needs to be a JPG, PNG or GIF</p>
            <p class="text-red-600" v-if="!$v.imageFile.imageRatio">The image needs to be at least {{ width }} x {{ height }}</p>
            <p class="text-red-600" v-if="!$v.imageFile.imageRequired">An Image is required</p>
        </div>
    </div>
</template>

<script>
    import CubeSpinner  from '../../../../components/Spinner.vue'
    import ImageUpload from '../../../../components/image-upload.vue'

    export default {

        components: { CubeSpinner, ImageUpload },

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
                updated: false,
                overImage: false,
                size: 10485760,
                width: 800,
                height: 800,
                text: 'Image should be at least 800 x 800',
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
            // reset() {
            //     this.imageFile = '';
            // }
        },

        // watch: {
        //     image() {
        //         if (this.image === `/storage/null`) { this.reset() }
        //     },
        // },

        validations: {
            imageFile: {
                fileSize() { 
                    return this.imageFile ? this.imageFile.file.size < this.size : true 
                },
                fileType() {
                    return this.imageFile ? ['image/jpeg','image/png','image/gif', 'image/webp'].includes(this.imageFile.file.type) : true
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