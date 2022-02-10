<template>
    <div>
        <Login 
            v-if="$store.state.loginModal"
            @close="closeModal" />
        <Notified 
            v-if="$store.state.notifyModal"
            :user="user" />
        <EmailVerification
            v-if="$store.state.verifyModal"
            @close="closeModal" />
        <Alert
            v-if="message"
            :message="message"
            @close="closeModal" />
    </div>
</template>

<script>
    import EmailVerification from './Components/email-verify-modal.vue'
    import Login from './Components/login-modal'
    import Notified from './Components/notify-modal'
    import Alert from './alert'

    export default {
        props: ['user', 'loadmessage'],

        components: { EmailVerification, Login, Notified, Alert },

        data() {
            return {
                message: this.loadmessage
            }
        },

        methods: {
            closeModal() {
                this.$store.commit('loginModal', false)
                this.$store.commit('verifyModal', false)
                this.message = null
            }
        },
    }
</script>