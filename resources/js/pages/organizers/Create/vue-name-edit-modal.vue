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
                <h3>Changing the name?</h3>
                <p>Changing the organization name will break any links you have sent out. This is not advised.</p>
                <button 
                    class="border-none bg-gradient-to-r from-button-red-1 via-button-red-2 to-button-red-3 text-white"
                    @click="onSubmit"
                    :disabled='disabled'>
                    Change anyways
                </button>
            </div>
            <div class="footer">
                <button 
                    class="border-none" 
                    @click="onClose">
                    Close
                </button>
            </div>
        </div>
    </div>
</template>

<script>
    export default {
        data() {
            return {
                disabled: false,
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