<template>
    <div class="favorite-button">
        <div :class="inputclass">
            <template :class="isApproved">
                <button 
                    aria-label="favorite"
                    type="button"
                    class="heart-btn" 
                    @click.prevent="toggle">
                    <svg 
                        viewBox="0 0 32 32" 
                        :class="{ hearted : isFavorited }">
                        <path d="m23.99 2.75c-.3 0-.6.02-.9.05-1.14.13-2.29.51-3.41 1.14-1.23.68-2.41 1.62-3.69 2.94-1.28-1.32-2.46-2.25-3.69-2.94-1.12-.62-2.27-1-3.41-1.14a7.96 7.96 0 0 0 -.9-.05c-1.88 0-7.26 1.54-7.26 8.38 0 7.86 12.24 16.33 14.69 17.95a1 1 0 0 0 1.11 0c2.45-1.62 14.69-10.09 14.69-17.95 0-6.84-5.37-8.38-7.26-8.38" />
                    </svg>
                </button>
            </template>
        </div>
        <LoginPopup 
            v-if="login"
            @close="login = false" />
        <Notified 
            v-if="notify"
            @close="notify = false"
            @update="updateNewsletter" />
        <EmailVerificationModal
            v-if="isVerifyVisible"
            @close="isVerifyVisible = false"
            :user="user" 
            message="verify" />
    </div>
</template>

<script>
    import EmailVerificationModal from './components/email-verification-modal.vue'
    import LoginPopup from './login-modal'
    import Notified from './components/notified-modal'

    export default {
        props: ['event', 'inputclass'],

        components: { EmailVerificationModal, LoginPopup, Notified },

        data() {
            return {
                user: this.$store.state.user ? this.$store.state.user : null,
                isFavorited: this.event.isFavorited,
                isApproved: this.event.status !== 'p' ? 'dis' : '',
                notify: false,
                login: false,
                isVerifyVisible: false,
                canFavorite: this.$store.state.user && this.$store.state.user.email_verified_at ? true : false,
                hasCookie: this.$cookies.isKey('news'),
            }
        },

        methods: {
            toggle() {
                if (this.canFavorite && this.hasCookie) { return this.isFavorited ? this.destroy() : this.create() }
                if (this.canFavorite) { 
                    this.$store.commit('notifyModal', true)
                    return this.isFavorited ? this.destroy() : this.create();
                }
                if (this.user) { return this.$store.commit('verifyModal', true) }
                return this.$store.commit('loginModal', true)
            },
            async create() {
                await axios.post(`/favorite/${this.event.slug}/favorites`);
                this.isFavorited = true;
            },
            async destroy() {
                await axios.delete(`/favorite/${this.event.slug}/favorites`);
                this.isFavorited = false;
            },
        },
    }
</script>