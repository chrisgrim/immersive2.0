<template>
    <div class="modal">
        <div class="wrapper">
            <div class="header">
                <button @click="onClose">
                    <svg>
                        <use :xlink:href="`/storage/website-files/icons.svg#ri-close-line`" />
                    </svg>
                </button>
            </div>
            <div class="body">
                <slot></slot>
                <div class="field">
                    <input
                        v-if="input"
                        v-model="inputData"
                        :class="{error : $v.inputData.$error}"
                        :placeholder="inputPlaceholder">
                    <div v-if="$v.inputData.$error" class="validation-error">
                        <p class="error" v-if="!$v.inputData.required">The new title is required.</p>
                        <p class="error" v-if="!$v.inputData.maxLength && inputData">The title is too long.</p>
                    </div>
                </div>
                <div class="field">
                    <textarea
                        v-if="textarea"
                        :class="{error : $v.textareaData.$error}"
                        v-model="textareaData"
                        :placeholder="textareaPlaceholder" />
                    <div v-if="$v.textareaData.$error" class="validation-error">
                        <p class="error" v-if="!$v.textareaData.required">Please include a reason.</p>
                        <p class="error" v-if="!$v.textareaData.maxLength && textareaData">The reason is too long.</p>
                    </div>
                </div>
                <button 
                    @click="onSubmit"
                    :disabled='isDisabled'>
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
    export default {

        props: ['item', 'placeholder', 'input', 'textarea', 'inputPlaceholder', 'textareaPlaceholder'],

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