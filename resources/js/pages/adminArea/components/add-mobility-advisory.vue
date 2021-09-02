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
                        v-model="mobility.mobilities" 
                        :class="{ error : $v.mobility.mobilities.$error }"
                        placeholder="mobilities"
                        @input="clearInfo">
                    <div v-if="$v.mobility.mobilities.$error" class="validation-error">
                        <p class="error" v-if="!$v.mobility.mobilities.required">Please Add Mobility </p>
                        <p class="error" v-if="!$v.mobility.mobilities.serverFailed">This Mobility has already been created</p>
                    </div>
                </div>
                <button 
                    :disabled="isDisabled" 
                    @click.prevent="onSubmit" 
                    class="btn-login btn-borderless">
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
    import formValidationMixin from '../../../mixins/form-validation-mixin'

    export default {

        mixins: [formValidationMixin],

        computed: {
            isDisabled() {
                return this.disabled || !this.mobility.mobilities;
            }
        }, 

        data() {
            return {
                mobility: {
                    mobilities: null
                },
                serverErrors: [],
                disabled: false,
            }
        },

        methods: {
             async onSubmit() {
                if (this.checkVuelidate()) { return }
                await axios.post('/mobilities', this.mobility)
                .then( res => { 
                    this.$emit('update', res.data);
                })
                .catch(err => { this.onErrors(err) });
            },
            clearInfo() {
                this.$v.mobility.mobilities.$touch;
                this.serverErrors = [];
            },
            onClose() {
                this.$emit('close');
            }
        },

        validations: {
            mobility: {
                mobilities: {
                    required,
                    serverFailed(){ return !this.serverErrors.mobilities },
                }
            },
        },
    }

</script>