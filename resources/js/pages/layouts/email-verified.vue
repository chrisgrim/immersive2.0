<template>
    <div v-if="visible">
        <div class="verify_bar">
            <div 
                class="panel" 
                ref="panel">
                <div class="text">
                    <div 
                        @click="visible = false" 
                        class="close">
                        X
                    </div>
                    <img 
                        style="width: 10rem" 
                        src="/storage/website-files/email-logo.png" 
                        alt="">
                    <h3>Please verify your email</h3>
                    <p>Check your email and spam for our email.</p>
                </div>
                <div class="submit">
                    <hr>
                    <p>Recieved nothing?<span class="another" @click="resend"> Click here to request another.</span></p>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
    export default {

        props: ['message'],

        computed: {

        },

        data() {
            return {
                body: '',
                visible: true,
            }
        },

        methods: {
            resend() {
                axios.post(`/email/verification-notification`)
                .then(response => {
                    this.visible = false;
                })
                .catch(errorResponse => { 
                    console.log(errorResponse.data);
                })
            },

            onClickOutside(event) {
                let panel =  this.$refs.panel;
                if (!panel || panel.contains(event.target)) return console.log('one');
                this.visible = false;
                this.$emit('close');
            },

            hide() {
                this.visible = false;
                this.$emit('close');
            }
        },

        mounted() {
            setTimeout(() => this.hide(), 20000);
            setTimeout(() => document.addEventListener("click", this.onClickOutside), 200);
        },

        unmounted() {
            document.removeEventListener("click", this.onClickOutside);
        },
    }
</script>