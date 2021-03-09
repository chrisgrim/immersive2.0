<template>
    <div class="modal-box">
        <div class="modal-box__container">
            <div class="modal-box__close">
                <button @click="onClose">
                    <IconSvg type="delete" />
                </button>
            </div>
            <div class="modal-box__body">
                <slot></slot>
                <textarea
                    v-model="data"
                    :placeholder="placeholder" />
                <div v-if="$v.data.$error" class="validation-error">
                    <p class="error" v-if="!$v.data.required">Please add request.</p>
                    <p class="error" v-if="!$v.data.maxLength">Please write a little less.</p>
                </div>
                <button 
                    @click="onSubmit"
                    :disabled='isDisabled'>
                    Submit
                </button>
            </div>
            <div class="modal-box__footer">
                <button @click="onClose">Close</button>
            </div>
        </div>
    </div>
</template>

<script>
    import IconSvg from './Svg-icon'
    import { required, maxLength } from 'vuelidate/lib/validators';
    
    export default {

        props: ['item', 'placeholder'],

        components: { IconSvg },

        computed: {
        },

        data() {
            return {
                isDisabled: false,
                data: null,
            }
        },

        methods: {
            onSubmit() {
                this.$v.$touch()
                if (this.$v.$invalid) return;
                this.$emit('onSubmit', this.data);
            },

            onClose() {
                this.$emit('close', true);
            },
        },

        validations: { 
            data: {
                required,
                maxLength: maxLength(2000),
            },
        },

    }
</script>