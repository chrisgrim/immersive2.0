<template>
    <div class="modal">
        <div class="wrapper">
            <div class="header">
                <button @click="onClose">
                    <svg>
                        <use :xlink:href="`/storage/website-files/icons.svg#ri-close-line`" />
                    </svg>
                </button>
                <p>Add Remote Location</p>
            </div>
            <div class="body">
                <div class="field">
                    <input 
                        type="text" 
                        v-model="remoteLocation.name" 
                        placeholder="Remote Location"
                        @input="$v.remoteLocation.name.$touch">
                    <div v-if="$v.remoteLocation.name.$error" class="validation-error">
                        <p class="error" v-if="!$v.remoteLocation.name.required">Please Add Remote Location </p>
                    </div>
                </div>
                <div class="field">
                    <textarea
                        type="text" 
                        v-model="remoteLocation.description" 
                        placeholder="Remote location description" />
                    <div v-if="$v.remoteLocation.description.$error" class="validation-error">
                        <p class="error" v-if="!$v.remoteLocation.description.required">Please Add Remote Location </p>
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
                return this.disabled || !this.remoteLocation.name ||  !this.remoteLocation.description;
            }
        }, 

        data() {
            return {
                remoteLocation: {
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
                await axios.post('/remotelocations', this.remoteLocation)
                .then( res => { 
                    this.$emit('update', res.data);
                })
            },
            clearInfo() {
                this.$v.remoteLocation.name.$touch;
                this.serverErrors = [];
            },
            onClose() {
                this.$emit('close');
            }
        },

        validations: {
            remoteLocation: {
                name: {
                    required,
                },
                description: {
                    required,
                }
            },
        },
    }

</script>