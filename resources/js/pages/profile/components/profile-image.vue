<template>
    <section 
        class="user-profile-image" 
        :style="`background:${user.hexColor}`">
        <template v-if="userCanEdit">
            <div class="user-profile-image">  
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
            </div>
            <div v-if="$v.imageFile.$error" class="image-validation-error">
                <p class="error" v-if="!$v.imageFile.fileSize">Image size is over 10MB</p>
                <p class="error" v-if="!$v.imageFile.fileType">Needs to be a Jpg, Png or Gif</p>
                <p class="error" v-if="!$v.imageFile.imageRatio">Needs to be at least 400 x 400</p>
            </div>
        </template>

        <template v-else>
            <template v-if="avatar">
                <img 
                    class="user-profile-image" 
                    :src="`/storage/${avatar.slice(0, -4)}jpg`" 
                    :alt="`${loaduser.name}`">
            </template>
            <template v-else-if="user.gravatar">
                <img 
                    class="user-profile-image" 
                    :src="user.gravatar" 
                    :alt="`${loaduser.name}`">
            </template>
            <div 
                v-else 
                class="user-profile-noimage__text">
                <h2>{{ loaduser.name.charAt(0) }}</h2>
            </div>
        </template>
    </section>
</template>

<script>
    import ImageUpload from '../../layouts/image-upload.vue'
    import CubeSpinner  from '../../layouts/loading.vue'
    import formValidationMixin from '../../../mixins/form-validation-mixin'


    export default {

        props: ['loaduser', 'auth'],

        mixins: [ formValidationMixin ],

        components: { ImageUpload, CubeSpinner },

        computed: {
            userCanEdit() {
                return this.owner && this.user.email_verified_at
            },
        },

        data() {
            return {
                user: this.loaduser,
                avatar: this.loaduser.thumbImagePath ? this.loaduser.thumbImagePath : null,
                imageFile: '',
                active: null,
                loading: false,
                serverErrors: [],
                uploading: false,
                disabled: false,
                formData: new FormData(),
                owner: Number(this.auth) == this.loaduser.id ? true : false
            }
        },

        methods: {

            onImageUpload(image) {
                this.imageFile = image;
                this.$v.$touch(); 
                if (this.$v.$invalid) { return false }
                this.onAddImage();
            },

            async onAddImage() {
                this.cleanImage();
                await axios.post(`/users/${this.user.id}`, this.formData)
                .catch(err => {  console.log(err); });
                location.reload()
            },

            cleanImage() {
                this.uploading = true;
                this.loading = true;
                this.disabled = true;
                this.formData.append('image', this.imageFile.file);
                this.formData.append('_method', 'PATCH');
            },            
        },

        validations: {
            imageFile: {
                fileSize() { 
                    return this.imageFile ? this.imageFile.file.size < 10485760 : true 
                },
                fileType() {
                    return this.imageFile ? ['image/jpeg','image/png','image/gif'].includes(this.imageFile.file.type) : true
                },
                imageRatio() {
                    return this.imageFile ? this.imageFile.width > 399 && this.imageFile.height > 399 : true 
                },
            },
        },
    };
</script>