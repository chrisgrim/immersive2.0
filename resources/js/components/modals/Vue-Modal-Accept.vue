<template>
    <div class="modal accept">
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
                <template v-if="accept">
                    <button 
                        class="btn-login btn-borderless" 
                        @click="onSubmit"
                        :disabled='isDisabled'>
                        Accept
                    </button>
                </template>
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

        props: ['accept'],

        data() {
            return {
                isDisabled: false,
            }
        },

        methods: {
            onSubmit() {
                this.$emit('onSubmit', true);
            },

            onClose() {
                this.$emit('close', true);
            },
        },

    }
</script>