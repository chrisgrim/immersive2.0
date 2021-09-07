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
                <h3>Get Notified?</h3>
                <p>Would you like to recieve an email whenever the organizers of your favorited events have updates or releases new events? (This can be edited in your profile at any time)</p>
                <br>
                <div class="field">
                    <div id="cover">
                        <input 
                            v-model="newsletter" 
                            type="checkbox" 
                            id="checkbox">
                        <div id="bar" />
                        <div id="knob">
                            <p v-if="newsletter"> Yes </p>
                            <p v-else> No </p>
                        </div>
                    </div>
                </div>
                <br>
                <button 
                    class="btn-login btn-borderless" 
                    @click="onSubmit"
                    :disabled='disabled'>
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

        data() {
            return {
                newsletter: false,
                disabled: false
            }
        },

        methods: {
            onSubmit() {
                this.$emit('update', this.newsletter);
            },
            onClose() {
                this.$emit('close', true);
            },
        },

        mounted() {
            document.body.classList.add('noscroll')
        },

        destroyed() {
            document.body.classList.remove('noscroll')
        },

    }
</script>