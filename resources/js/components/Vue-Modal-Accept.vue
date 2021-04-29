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
                <template v-if="accept">
                    <button 
                        style="display:block;" 
                        @click="onSubmit"
                        :disabled='isDisabled'>
                        Accept
                    </button>
                </template>
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

        props: ['accept'],

        components: { IconSvg },

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