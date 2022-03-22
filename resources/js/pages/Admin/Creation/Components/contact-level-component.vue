<template>
    <div class="modal">
        <div class="wrapper">
            <div class="header">
                <button @click="onClose">
                    <svg>
                        <use :xlink:href="`/storage/website-files/icons.svg#ri-close-line`" />
                    </svg>
                </button>
                <p>Add Contact Level</p>
            </div>
            <div class="body">
                <div class="field">
                    <input 
                        type="text" 
                        v-model="contactLevel.level" 
                        placeholder="Contact Level Name"
                        @input="$v.contactLevel.level.$touch">
                    <div v-if="$v.contactLevel.level.$error" class="validation-error">
                        <p class="error" v-if="!$v.contactLevel.level.required">Please Add Contact Level </p>
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
    import formValidationMixin from '../../../../mixins/form-validation-mixin'
    export default {

        mixins: [formValidationMixin],

        computed: {
            isDisabled() {
                return this.disabled || !this.contactLevel.level ;
            }
        }, 

        data() {
            return {
                contactLevel: { 
                    level: null 
                },
                serverErrors: [],
                disabled: false,
            }
        },

        methods: {
             async onSubmit() {
                if (this.checkVuelidate()) { return }
                await axios.post('/contactlevels', this.contactLevel)
                .then( res => { 
                    this.$emit('update', res.data);
                })
            },
            clearInfo() {
                this.$v.contactLevel.level.$touch;
                this.serverErrors = [];
            },
            onClose() {
                this.$emit('close');
            }
        },

        validations: {
            contactLevel: {
                level: {
                    required,
                },
            }
        },
    }

</script>