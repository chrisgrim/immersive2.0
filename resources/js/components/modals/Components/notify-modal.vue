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
                    <div 
                        class="relative w-40"
                        id="cover">
                        <input 
                            class="m-0 w-full h-16 cursor-pointer relative z-10 opacity-0" 
                            v-model="newsletter" 
                            type="checkbox" 
                            id="checkbox">
                        <div 
                            class="abosolute inset-0 rounded-full border"
                            id="bar" />
                        <div 
                            class="abosolute top-2 left-2 w-14 h-14 bg-white rounded-full transition border shadow-custom-1" 
                            id="knob">
                            <p v-if="newsletter"> Yes </p>
                            <p v-else> No </p>
                        </div>
                    </div>
                </div>
                <br>
                <button 
                    class="mb-4 font-medium py-6 px-4 rounded-2xl w-full border-none text-white float-right bg-gradient-to-r from-button-red-1 via-button-red-2 to-button-red-3 md:px-20 hover:from-button-red-2 hover:via-button-red-3 hover:to-button-red-1"
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