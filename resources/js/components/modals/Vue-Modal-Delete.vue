<template>
    <div class="modal delete">
        <div class="wrapper">
            <div class="header">
                <button @click="onClose">
                    <svg>
                        <use :xlink:href="`/storage/website-files/icons.svg#ri-close-line`" />
                    </svg>
                </button>
            </div>
            <div class="body">
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
                <div class="input">
                    <input
                        v-model="toDelete"
                        type="text">
                    <button
                        class="btn-borderless btn-login"
                        @click="onDelete"
                        :disabled='isDisabled'>
                        DELETE
                    </button>
                </div>
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
        props: ['item', 'strict', 'body'],

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