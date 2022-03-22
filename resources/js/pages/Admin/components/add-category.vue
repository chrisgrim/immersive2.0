<template>
    <div class="modal">
        <div class="wrapper">
            <div class="header">
                <button @click="onClose">
                    <svg>
                        <use :xlink:href="`/storage/website-files/icons.svg#ri-close-line`" />
                    </svg>
                </button>
                <p>Add Category</p>
            </div>
            <div class="body">
                <div>
                    <CardImage
                        :height="800"
                        :width="800"
                        :external-submit="checkImage"
                        @addImage="addImage" />
                </div>                
                <div class="text">
                    <div class="field">
                        <input 
                            type="text" 
                            :class="{'error' : $v.category.name.$error}"
                            v-model="category.name" 
                            placeholder="Category Name"
                            @input="clearInfo">
                        <div v-if="$v.category.name.$error" class="validation-error">
                            <p class="error" v-if="!$v.category.name.required">Please Add Category Name </p>
                            <p class="error" v-if="!$v.category.name.serverFailed">This category has already been created</p>
                        </div>
                    </div>
                    <div class="field">
                        <textarea
                            type="text"
                            rows="6"
                            :class="{'error' : $v.category.description.$error}"
                            v-model="category.description" 
                            placeholder="Category Description"
                            @input="$v.category.description.$touch" />
                        <div v-if="$v.category.description.$error" class="validation-error">
                            <p class="error" v-if="!$v.category.description.required">Please Add Category Description </p>
                        </div>
                    </div>
                    <div class="field">
                        <div style="text-align:left;">Is this a remote category?</div>
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
                    </div>
                    <button 
                        :disabled="isDisabled" 
                        @click.prevent="onSubmit" 
                        class="btn-login btn-borderless">
                        Submit
                    </button>
                </div>
            </div>
            <div class="footer">
                <button 
                    class="btn-borderless" 
                    @click="onClose">
                    Close
                </button>
            </div>
        </div>
    </div>
</template>

<script>
    
    import { required } from 'vuelidate/lib/validators'
    import formValidationMixin from '../../../mixins/form-validation-mixin'
    import CardImage from '../../../components/Upload-Image.vue'


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
            onClose() {
                this.$emit('close');
            }
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