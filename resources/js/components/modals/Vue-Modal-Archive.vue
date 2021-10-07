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
                <p><b> To archive {{ item.name }}, type ARCHIVE. </b></p>
                <div class="input">
                    <input
                        v-model="toDelete"
                        type="text">
                    <button
                        class="btn-borderless btn-login"
                        @click="onArchive"
                        :disabled='isDisabled'>
                        Archive
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

        computed: {
        },

        data() {
            return {
                toDelete: null,
                isDisabled: true,

            }
        },

        methods: {
            onArchive() {
                this.$emit('archive', true);
            },

            onClose() {
                this.$emit('close', true);
            }
        },

        watch: {
            toDelete() {
                this.toDelete.trim().toLowerCase() === 'archive' ? this.isDisabled = false : this.isDisabled = true
            }
        }

    }
</script>