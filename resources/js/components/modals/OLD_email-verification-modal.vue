<template>
    <div 
        v-if="visible"
        class="email-verification modal">
        <div ref="verify" class="wrapper">
            <div class="header">
                <button @click="onClose">
                    <svg>
                        <use :xlink:href="`/storage/website-files/icons.svg#ri-close-line`" />
                    </svg>
                </button>
            </div>
            <div class="body">
                <h3>Please verify your email</h3>
                <p>Check your email and spam for our email.</p>
                <div class="submit">
                    <p>
                        Recieved nothing?
                        <button 
                            class="btn-borderless" 
                            @click="resend"> 
                            Click here to request another.
                        </button>
                    </p>
                </div>
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
                visible: true
            }
        },

        methods: {
            async resend() {
                await axios.post(`/email/verification-notification`)
                .then( setTimeout(() => this.onClose(), 300) )
            },
            onClickOutside(event) {
                let panel =  this.$refs.verify;
                if (!panel || panel.contains(event.target)) return;
                this.onClose()
            },
            onClose() {
                this.visible = false
            }
        },

        mounted() {
            setTimeout(() => this.onClose(), 20000);
            setTimeout(() => document.addEventListener("click", this.onClickOutside), 200);
        },

        destroyed() {
            document.removeEventListener("click", this.onClickOutside);
        },
    }
</script>