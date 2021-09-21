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
                    class="btn-login btn-borderless" 
                    @click="onSubmit"
                    :disabled='isDisabled'>
                    Change anyways
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