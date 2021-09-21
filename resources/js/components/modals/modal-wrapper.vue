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
    import EmailVerification from '../../pages/profile/components/email-verification-modal.vue'
    import Login from '../../pages/profile/login-modal'
    import Notified from '../../pages/profile/components/notified-modal'
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