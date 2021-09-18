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
    </div>
</template>

<script>
    import EmailVerification from '../../pages/profile/components/email-verification-modal.vue'
    import Login from '../../pages/profile/login-modal'
    import Notified from '../../pages/profile/components/notified-modal'

    export default {
        props: ['user'],

        components: { EmailVerification, Login, Notified },

        data() {
            return {
                
            }
        },

        methods: {
            closeModal() {
                this.$store.commit('loginModal', false)
                this.$store.commit('verifyModal', false)
            }
        },
    }
</script>