<template>
    <main class="transition ease-in duration-300">
        <div class="fixed w-[calc(100vw-26rem)] overflow-auto h-full left-[26rem]">
            <div class="p-12 h-28 w-full">
                <p class="font-semibold">Cover Image</p>
            </div>
            <div class="flex justify-center h-full w-full p-8">
                <div class="max-w-3xl">
                    <h2 class="mb-8">Add your image</h2>
                    <p class="font-light text-3xl">Lorem, ipsum dolor sit, amet consectetur adipisicing elit. Consequatur nostrum distinctio eaque ipsam itaque, ducimus ipsum cupiditate obcaecati esse incidunt, ratione blanditiis est culpa animi debitis, reprehenderit non cum repudiandae.</p>
                    <div class="w-full relative border border-dashed border-gray-600 flex items-center mt-16 rounded-lg">
                        <div class="w-full h-48">
                            <input 
                                type="file" 
                                @change="onChange">
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </main>
</template>

<script>
    import ImageUpload from '../../../components/image-upload.vue'
    export default {

        props: [],

        components: { ImageUpload },

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
                height: 500,
                text: 'Image should be at least 800 x 500',
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
    }
</script>