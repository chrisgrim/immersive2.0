<template>
    <div class="modal">
        <div class="wrapper">
            <div class="header">
                <button @click="onSubmit">
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
                    @click="onSubmit">
                    Close
                </button>
            </div>
        </div>
    </div>
</template>

<script>    
    export default {
        props: ['user'],
        
        data() {
            return {
                newsletter: false,
                disabled: false
            }
        },

        methods: {
            async onSubmit(value) {
                this.$cookies.set("news", true, "Infinity");
                await axios.patch(`/users/${this.user.id}`, { newsletter: value })
                location.reload()
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