<template>
    <div class="w-full md:w-2/3">
        <div class="border p-8 rounded-2xl shadow-custom-1">
            <h3>Add Category</h3>
            <div class="flex gap-4">
                <div class="w-1/2 p-4">
                    <CardImage
                        :image="null"
                        :loading="loading"
                        :external-submit="checkImage"
                        @addImage="addImage" />
                </div>                
                <div class="w-1/2 flex flex-col">
                    <div>
                        <div>
                            <input 
                                type="text"
                                class="w-full p-2 border rounded-2xl mt-4" 
                                :class="{'error' : $v.category.name.$error}"
                                v-model="category.name" 
                                placeholder="Category Name"
                                @input="clearInfo">
                            <div v-if="$v.category.name.$error" class="validation-error">
                                <p class="error" v-if="!$v.category.name.required">Please Add Category Name </p>
                                <p class="error" v-if="!$v.category.name.serverFailed">This category has already been created</p>
                            </div>
                        </div>
                    </div>
                    <div>
                        <textarea
                            type="text"
                            rows="6"
                            class="w-full"
                            :class="{'error' : $v.category.description.$error}"
                            v-model="category.description" 
                            placeholder="Category Description"
                            @input="$v.category.description.$touch" />
                        <div v-if="$v.category.description.$error" class="validation-error">
                            <p class="error" v-if="!$v.category.description.required">Please Add Category Description </p>
                        </div>
                    </div>
                </div>
            </div>
            <div class="mt-8">
                <p>Is this a remote category?</p>
                <div id="cover">
                    <input 
                        v-model="category.remote" 
                        type="checkbox" 
                        id="checkbox">
                    <div id="bar" />
                    <div id="knob">
                        <p v-if="category.remote">
                            Yes
                        </p>
                        <p v-else>
                            No
                        </p>
                    </div>
                </div>
                <button 
                    :disabled="isDisabled" 
                    @click.prevent="onSubmit" 
                    class="my-8 font-medium px-4 rounded-2xl w-full border-none text-white bg-gradient-to-r from-button-red-1 via-button-red-2 to-button-red-3 md:px-20 hover:from-button-red-2 hover:via-button-red-3 hover:to-button-red-1 disabled:from-bg-gray-500 disabled:to-gray-500">
                    Submit
                </button>
            </div>
        </div>
    </div>
</template>

<script>
    
    import { required } from 'vuelidate/lib/validators'
    import formValidationMixin from '../../../../mixins/form-validation-mixin'
    import CardImage from './add-image.vue'


    export default {
        components: { CardImage },

        mixins: [formValidationMixin],

        computed: {
            isDisabled() {
                return this.disabled || !this.category.name || !this.category.description || !this.hasImage;
            }
        }, 

        data() {
            return {
                category: this.initializeCategoryObject(),
                formData: new FormData(),
                serverErrors: [],
                checkImage: false,
                hasImage: false,
                disabled: false,
                loading: false,
            }
        },

        methods: {

            async onSubmit() {
                if ( this.checkVuelidate() || this.checkForImage()) { return }
                for ( var key in this.category ) { this.formData.append(key, this.category[key]) }
                await axios.post('/categories', this.formData)
                .then( res => { 
                    this.$emit('update', res.data);
                })
            },
            addImage(image) {
                this.formData.append('image', image)
                this.hasImage = true
            },
            checkForImage() {
                if (!this.formData.has('image')) {
                    this.checkImage = true;
                    return true
                }
            },
            clearInfo() {
                this.$v.category.name.$touch;
                this.serverErrors = [];
            },
            initializeCategoryObject() {
                return {
                    name: null,
                    description: null,
                    remote: 0,
                }
            },
        },

        validations: {
            category: {
                name: {
                    required,
                    serverFailed(){ return !this.serverErrors.name },
                },
                description: {
                    required,
                },
            }
        },
    }

</script>