<template>
    <div>
        <div class="verify_bar" >
            <div class="panel" ref="panel">
                <div class="text">
                    <div @click="hide" class="close">
                        X
                    </div>
                    <img style="width: 10rem" src="/storage/website-files/email-logo.png" alt="">
                    <h3>We have emailed you a reset password link.</h3>
                    <p>Please check your email.</p>
                </div>
                <div class="submit">
                    <hr>
                    
                </div>
            </div>
        </div>
    </div>
</template>

<script>
    export default {

        computed: {

        },

        data() {
            return {
                body: '',
            }
        },

        methods: {

            onClickOutside(event) {
                let panel =  this.$refs.panel;
                if (!panel || panel.contains(event.target)) return console.log('one');;
                this.hide();
            },

            hide() {
                this.$emit('close');
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