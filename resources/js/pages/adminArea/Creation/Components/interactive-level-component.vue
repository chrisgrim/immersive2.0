<template>
    <div class="modal">
        <div class="wrapper">
            <div class="header">
                <button @click="onClose">
                    <svg>
                        <use :xlink:href="`/storage/website-files/icons.svg#ri-close-line`" />
                    </svg>
                </button>
                <p>Add Mobility Advisory</p>
            </div>
            <div class="body">
                <div class="field">
                    <input 
                        type="text" 
                        v-model="interactiveLevel.name"
                        :class="{ error : $v.interactiveLevel.name.$error }"
                        placeholder="Interactive Level"
                        @input="clearInfo">
                    <div v-if="$v.interactiveLevel.name.$error" class="validation-error">
                        <p class="error" v-if="!$v.interactiveLevel.name.required">Please add interactive level name </p>
                        <p class="error" v-if="!$v.interactiveLevel.name.serverFailed">This interactive level has already been created</p>
                    </div>
                </div>
                <div class="field">
                    <textarea
                        type="text" 
                        v-model="interactiveLevel.description"
                        @input="$v.interactiveLevel.description.$touch"
                        placeholder="Interactive level description" />
                    <div v-if="$v.interactiveLevel.description.$error" class="validation-error">
                        <p class="error" v-if="!$v.interactiveLevel.description.required">Please add interactive level description </p>
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
                return this.disabled || !this.interactiveLevel.name || !this.interactiveLevel.description;
            }
        }, 

        data() {
            return {
                interactiveLevel: {
                    name: '',
                    description: '',
                },
                serverErrors: [],
                disabled: false,
            }
        },

        methods: {
             async onSubmit() {
                if (this.checkVuelidate()) { return }
                await axios.post('/interactivelevels', this.interactiveLevel)
                .then( res => { 
                    this.$emit('update', res.data);
                })
                .catch(err => { this.onErrors(err) });
            },
            clearInfo() {
                this.$v.interactiveLevel.name.$touch;
                this.serverErrors = [];
            },
            onClose() {
                this.$emit('close');
            }
        },

        validations: {
            interactiveLevel: {
                name: {
                    required,
                    serverFailed(){ return !this.serverErrors.name },
                },
                description: {
                    required,
                }
            },
        },
    }

</script>