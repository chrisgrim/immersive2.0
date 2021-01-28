<template>
    <div class="modal-box">
        <div class="modal-box__container">
            <div class="modal-box__close">
                <button @click="onClose">
                    <IconSvg type="delete" />
                </button>
            </div>
            <div class="modal-box__body">
                <h5>
                    Are you sure?
                </h5>
                <h3>
                    {{ item.name }}
                </h3>
                <p>
                    {{ body }}
                </p>
                <p><b> To permanently delete {{ item.name }}, type DELETE. </b></p>
            </div>
            <div class="modal-box__input">
                <input 
                    class="delete" 
                    v-model="toDelete"
                    type="text">
                <button 
                    class="delete" 
                    @click="onDelete"
                    :disabled='isDisabled'>
                    DELETE
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

        props: ['item', 'strict', 'body'],

        components: { IconSvg },

        data() {
            return {
                toDelete: null,
                isDisabled: true,
            }
        },

        methods: {
            onDelete() {
                this.$emit('ondelete', true);
            },

            onClose() {
                this.$emit('close', true);
            }
        },

        watch: {
            toDelete() {
                this.toDelete.trim().toLowerCase() === 'delete' ? this.isDisabled = false : this.isDisabled = true
            }
        }

    }
</script>