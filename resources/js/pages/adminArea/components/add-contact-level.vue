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