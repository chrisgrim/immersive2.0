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
                <input
                    v-if="input"
                    v-model="inputData"
                    :placeholder="inputPlaceholder">
                <div v-if="$v.inputData.$error" class="validation-error">
                    <p class="error" v-if="!$v.inputData.required">The new title is required.</p>
                    <p class="error" v-if="!$v.inputData.maxLength">The title is too long.</p>
                </div>
                <textarea
                    v-if="textarea"
                    v-model="textareaData"
                    :placeholder="textareaPlaceholder" />
                <div v-if="$v.textareaData.$error" class="validation-error">
                    <p class="error" v-if="!$v.textareaData.required">Please include a reason.</p>
                    <p class="error" v-if="!$v.textareaData.maxLength">The reason is too long.</p>
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
    
    export default {

        props: ['item', 'placeholder', 'input', 'textarea', 'inputPlaceholder', 'textareaPlaceholder'],

        components: { IconSvg },

        computed: {
            data() {
                return {
                    input: this.inputData,
                    textarea: this.textareaData
                }
            }
        },

        data() {
            return {
                isDisabled: false,
                inputData: null,
                textareaData: null,
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
            inputData: {
                required() { return this.input ? this.inputData ? true : false : true },
                maxLength() { return this.input ? this.inputData && this.inputData.length < 100 ? true : false : true },
            },
            textareaData: {
                required() { return this.textarea ? this.textareaData ? true : false : true },
                maxLength() { return this.textarea ? this.textareaData && this.textareaData.length < 2000 ? true : false : true },
            }
        },

    }
</script>