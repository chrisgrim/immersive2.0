<template>
    <div class="modal">
        <div class="wrapper">
            <div class="header">
                <button @click="onClose">
                    <svg>
                        <use :xlink:href="`/storage/website-files/icons.svg#ri-close-line`" />
                    </svg>
                </button>
                <p>Add Content Advisory</p>
            </div>
            <div class="body">
                <div class="field">
                    <input 
                        type="text" 
                        v-model="advisory.advisories" 
                        :class="{ error : $v.advisory.advisories.$error }"
                        placeholder="Content Advisory"
                        @input="clearInfo">
                    <div v-if="$v.advisory.advisories.$error" class="validation-error">
                        <p class="error" v-if="!$v.advisory.advisories.required">Please Add Content Advisories </p>
                        <p class="error" v-if="!$v.advisory.advisories.serverFailed">This Advisory has already been created</p>
                    </div>
                </div>
                <button 
                    :disabled="isDisabled" 
                    @click.prevent="onSubmit"
                    class="mb-4 font-medium py-6 px-4 rounded-2xl w-full border-none text-white float-right bg-gradient-to-r from-button-red-1 via-button-red-2 to-button-red-3 md:px-20 hover:from-button-red-2 hover:via-button-red-3 hover:to-button-red-1 disabled:from-bg-gray-500 disabled:to-gray-500">
                    Submit
                </button>
            </div>
            <div class="footer">
                <button @click="onClose">
                    Close
                </button>
            </div>
        </div>
    </div>
</template>

<script>
    
    import { required } from 'vuelidate/lib/validators'
    import formValidationMixin from '../../../../mixins/form-validation-mixin'
    export default {

        mixins: [formValidationMixin],

        computed: {
            isDisabled() {
                return this.disabled || !this.advisory.advisories;
            }
        }, 

        data() {
            return {
                advisory: {
                    advisories: null
                },
                serverErrors: [],
                disabled: false,
            }
        },

        methods: {
             async onSubmit() {
                if (this.checkVuelidate()) { return }
                await axios.post('/contentadvisories', this.advisory)
                .then( res => { this.$emit('update', res.data) })
                .catch(err => { this.onErrors(err) });
            },
            clearInfo() {
                this.$v.advisory.advisories.$touch;
                this.serverErrors = [];
            },
            onClose() {
                this.$emit('close');
            }
        },

        validations: {
            advisory: {
                advisories: {
                    required,
                    serverFailed(){ return !this.serverErrors.advisories },
                },
            },
        },
    }

</script>