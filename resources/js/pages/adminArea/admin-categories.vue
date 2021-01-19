<template>
    <div class="categories">
        <div class="">
            <div class="title">
                <h1>Categories</h1>
                <div class="add">
                    <button @click.prevent="isModalVisible = true">
                        <IconSvg type="add" />
                    </button>
                </div>
            </div>
        </div>
        <tabs>
            <!-- location based category tab -->
            <tab 
                title="Location Based Events" 
                :active="true" 
                class="tab-events">
                <div 
                    class="list" 
                    :key="category.id"
                    v-for="(category) in categories.location">
                    <input 
                        type="text" 
                        v-model="category.name" 
                        placeholder="Category Name"
                        @blur="onEdit(category)">
                    <textarea
                        type="text" 
                        v-model="category.description" 
                        placeholder="Category Name"
                        @blur="onEdit(category)" />
                    <div 
                        class="box" 
                        :style="{ backgroundImage: `url('/storage/${category.thumbImagePath}')` }">
                        <label>
                            <image-upload @loaded="onImageEdit($event, category)" />
                            <CubeSpinner :loading="isLoading" />
                        </label> 
                    </div>
                    <input 
                        type="text" 
                        v-model="category.credit" 
                        placeholder="image credit"
                        @blur="onEdit(category)">
                    <div>
                        <input 
                            type="text" 
                            v-model="category.rank" 
                            placeholder="Category Rank"
                            @blur="onEdit(category)">
                    </div>
                    <button 
                        @click.prevent="showModal(category)" 
                        class="delete-circle">
                        <IconSvg type="delete" />
                    </button>
                </div>
            </tab>

            <!-- remote based category tab -->
            <tab 
                title="Online Events" 
                class="tab-events">
                <div 
                    class="list" 
                    :key="category.id"
                    v-for="(category) in categories.remote">
                    <input 
                        type="text" 
                        v-model="category.name" 
                        placeholder="Category Name"
                        @blur="onEdit(category)">
                    <textarea
                        type="text" 
                        v-model="category.description" 
                        placeholder="Category Name"
                        @blur="onEdit(category)" />
                    <div 
                        class="box" 
                        :style="{ backgroundImage: `url('/storage/${category.thumbImagePath}')` }">
                        <label>
                            <image-upload @loaded="onImageEdit($event, category)" />
                            <CubeSpinner :loading="isLoading" />
                        </label> 
                    </div>
                    <input 
                        type="text" 
                        v-model="category.credit" 
                        placeholder="image credit"
                        @blur="onEdit(category)">
                    <div>
                        <input 
                            type="text" 
                            v-model="category.rank" 
                            placeholder="Category Rank"
                            @blur="onEdit(category)">
                    </div>
                    <button 
                        @click.prevent="showModal(category)" 
                        class="delete-circle">
                        <IconSvg type="delete" />
                    </button>
                </div>
            </tab>
        </tabs>

        <!-- Delete Category Modal -->
        <modal 
            v-if="isEditModalVisible" 
            @close="isEditModalVisible = false">
            <div slot="header">
                <div class="circle del">
                    <p>X</p>
                </div>
            </div>
            <div slot="body"> 
                <h3>Are you sure?</h3>
                <p>You are deleting your {{ modalDelete.name }} category.</p>
            </div>
            <div slot="footer">
                <button 
                    class="btn del" 
                    @click.prevent="onDelete(modalDelete)">
                    Delete
                </button>
            </div>
        </modal>
        
        <!-- Add new category -->
        <div class="pin">
            <modal 
                v-if="isModalVisible" 
                @close="isModalVisible = false">
                <div slot="header">
                    <div />
                </div>
                <div slot="body" class="body">
                    <label :style="{ backgroundImage: `url('${imageFile.src}')` }">
                        <div 
                            v-if="!imageFile.src" 
                            class="box">
                            <div class="in">
                                <div>
                                    <svg 
                                        class="b" 
                                        height="32" 
                                        width="32" 
                                        viewBox="0 0 24 24" 
                                        aria-label="Add an image or video" 
                                        role="img">
                                        <path d="M24 12c0-6.627-5.372-12-12-12C5.373 0 0 5.373 0 12s5.373 12 12 12c6.628 0 12-5.373 12-12zm-10.767 3.75a1.25 1.25 0 0 1-2.5 0v-3.948l-1.031 1.031a1.25 1.25 0 0 1-1.768-1.768L12 7l4.066 4.065a1.25 1.25 0 0 1-1.768 1.768l-1.065-1.065v3.982z" />
                                    </svg>
                                </div>
                                <div>
                                    <p>Click here to upload image</p>
                                    <p>Must be at least 800x800 and under 2mb</p>
                                </div>
                            </div>
                        </div>
                        <image-upload @loaded="onImageUpload" />
                        <CubeSpinner :loading="isLoading" />
                        <div v-if="$v.imageFile.$error" class="validation-error">
                            <template class="error" v-if="!$v.imageFile.required">Please Add Image </template>
                            <template class="error" v-if="!$v.imageFile.fileSize">Image needs to be less than 2mb </template>
                            <template class="error" v-if="!$v.imageFile.fileType">Image should be jpg, gif, or png</template>
                            <template class="error" v-if="!$v.imageFile.imageRatio">Needs to be at least 800x800</template>
                        </div>
                    </label> 
                    <div class="text">
                        <div class="name">
                            <input 
                                type="text" 
                                v-model="name" 
                                placeholder="Category Name"
                                :class="{ active: nameActive}"
                                @click="nameActive = true"
                                @blur="nameActive = false"
                                @input="clearInfo">
                            <div v-if="$v.name.$error" class="validation-error">
                                <p class="error" v-if="!$v.name.required">Please Add Category Name </p>
                                <p class="error" v-if="!$v.name.serverFailed">This category has already been created</p>
                            </div>
                        </div>
                        <div class="desc">
                            <textarea
                                type="text"
                                rows="6"
                                v-model="description" 
                                placeholder="Category Description"
                                :class="{ active: nameActive }"
                                @click="nameActive = true"
                                @blur="nameActive = false"
                                @input="$v.description.$touch" />
                            <div v-if="$v.description.$error" class="validation-error">
                                <p class="error" v-if="!$v.description.required">Please Add Category Description </p>
                            </div>
                        </div>
                        <div class="field">
                            <div style="text-align:left;">Is this a remote category?</div>
                            <div id="cover">
                                <input 
                                    v-model="remote" 
                                    type="checkbox" 
                                    id="checkbox">
                                <div id="bar" />
                                <div id="knob">
                                    <p v-if="remote">
                                        Yes
                                    </p>
                                    <p v-else>
                                        No
                                    </p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div slot="footer">
                    <button 
                        @click.prevent="onSubmit()" 
                        class="btn sub">
                        Submit
                    </button>
                </div>
            </modal>
        </div>
    </div>
</template>

<script>
    
    import { required, minLength } from 'vuelidate/lib/validators'
    import CubeSpinner  from '../layouts/loading.vue'
    import _ from 'lodash'
    import formValidationMixin from '../../mixins/form-validation-mixin'
    import IconSvg from '../../components/Svg-icon'
    import ImageUpload from '../layouts/image-upload.vue'


    export default {
        components: { CubeSpinner, IconSvg, ImageUpload },

        mixins: [formValidationMixin],

        computed: {
            endpoint() {
                return `/create/${this.event.slug}/advisories`
            },

            submitObject() {
                return {
                    image: this.imageFile.file,
                    name: this.name,
                    description: this.description,
                    remote: this.remote ? 1 : 0,
                }
            }
        },

        data() {
            return {
                categories: '',
                nameActive: false,
                name: '',
                description: '',
                imageFile: '',
                isModalVisible: false,
                isEditModalVisible: false,
                modalDelete: '',
                serverErrors: [],
                isLoading: false,
                remote: false,
            }
        },

        methods: {

            onSubmit() {
                this.$v.$touch(); 
                if (this.$v.$invalid) { return false; }
                this.isLoading = true;

                var form_data = new FormData();
                for ( var key in this.submitObject ) { form_data.append(key, this.submitObject[key]) }

                axios.post('/categories', form_data)
                .then( res => { this.categories = res.data; this.isModalVisible = false; this.isLoading = false; })
                .catch();
            },

            onDelete(category) {
                axios.delete(`/categories/${category.slug}`)
                .then( res => { this.categories = res.data; this.isEditModalVisible = false; })
                .catch( error => { this.serverErrors = error.response.data.errors; });
            },

            onEdit(category) {
                axios.patch(`/categories/${category.slug}`, category)
                .then( res=> { this.categories = res.data } )
                .catch(err => { this.onErrors(err); });
            },

            onLoad() {
                axios.get(`/categories?timestamp=${new Date().getTime()}`)
                .then(res => { this.categories = res.data })
                .catch(error => { this.serverErrors = error.response.data.errors; });
            },

            clearInfo() {
                this.$v.name.$touch;
                this.serverErrors = [];
            },

            onImageUpload(image) {
                this.imageFile = image; 
                this.$v.imageFile.$touch();
                if (this.$v.imageFile.$invalid) { return false }
            },

            onImageEdit(image, category) {
                if (image.file.size > 20971520) { return alert('Image Filesize Too Big') };
                if (!["image/jpeg","image/png",'image/gif'].includes(image.file.type)) { return alert('Image needs to be jpeg, pgn or gif') };
                if (image.width < 800 || image.height < 800) { return alert('Image Proportions Too Small') };

                this.isLoading = true;
                let data = new FormData();
                data.append('image', image.file);
                data.append('_method', 'PATCH');
                axios.post(`/categories/${category.slug}`, data)
                .then( res => { this.categories = res.data; this.isLoading = false; } )
                .catch( error => { console.log(error.response.data.errors) });
            },

            showModal(category) {
                this.modalDelete = category;
                this.isEditModalVisible = true;
            },

            hasServerError(field) {
                return (field && _.has(this, 'serverErrors.' + field) && !_.isEmpty(this.serverErrors[field]));
            },
        },

        created() {
            this.onLoad()
        },

        validations: {
            name: {
                required,
                serverFailed : function(){ return !this.hasServerError('name'); },
            },
            description: {
                required,
            },
            imageFile: {
                required,
                fileSize() { 
                    return this.imageFile ? this.imageFile.file.size < 20971520 : true 
                },
                fileType() {
                    return this.imageFile ? ['image/jpeg','image/png','image/gif'].includes(this.imageFile.file.type) : true
                },
                imageRatio() {
                    return this.imageFile ? this.imageFile.width > 800 && this.imageFile.height > 800 : true 
                }
            }
        },
    }

</script>