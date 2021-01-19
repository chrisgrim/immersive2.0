<template>
    <div v-if="visible">
        <div class="verify_bar" v-if="submitted">
            <div class="panel" ref="panel">
                <div class="text">
                    <div @click="visible = false" class="close">
                        ✓
                    </div>
                    <img style="width: 10rem" src="/storage/website-files/email-logo.png" alt="">
                    <h3>Thanks for submitting your event</h3>
                    <p>Events take 1-3 business days to review. Once approved, you use the dashboard to update your event at any time! Change your dates and ticket prices whenever you like without having to resubmit.</p>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
    export default {

        props: {
            message: { type:String },
            user: {type:Object},
        },

        computed: {

        },

        data() {
            return {
                body: '',
                visible: true,
                submitted: this.message && this.message == 'submitted' ? true : false,
            }
        },

        methods: {
            resend() {
                axios.post(`/email/resend`)
                .then(response => {
                    this.visible = false;
                    console.log(response.data)
                })
                .catch(errorResponse => { 
                    console.log(errorResponse.data);
                })
            },

            onClickOutside(event) {
                console.log('test2');
                let panel =  this.$refs.panel;
                if (!panel || panel.contains(event.target)) return console.log('one');;
                this.visible = false;
                console.log('test');
            },

            hide() {
                this.visible = false;
            }
        },

        mounted() {
            setTimeout(() => this.hide(), 20000);
            setTimeout(() => document.addEventListener("click", this.onClickOutside), 200);
        },

        beforeDestroy() {
            document.removeEventListener("click", this.onClickOutside);
        },

    }
</script>