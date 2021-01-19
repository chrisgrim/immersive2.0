<template>
    <div class="delete-box">
        <div class="delete-box__container">
            <div class="delete-box__close">
                <button @click="onClose">
                    <IconSvg type="delete" />
                </button>
            </div>
            <div class="delete-box__body">
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
            <div class="delete-box__input">
                <input 
                    v-model="toDelete"
                    type="text">
                <button 
                    @click="onDelete"
                    :disabled='isDisabled'>
                    DELETE
                </button>
            </div>
            <div class="delete-box__footer">
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