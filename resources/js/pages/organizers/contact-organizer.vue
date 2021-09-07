<template>
    <div class="organizer-show-contact">
        <div 
            v-if="canMessage " 
            class="contact">
            <button 
                class="default-border" 
                @click="isModalVisible=true">
                Contact
            </button>
        </div>
        <div 
            v-if="verify " 
            class="contact">
            <button 
                class="default-border" 
                @click="onSubmit('verify')">
                Contact
            </button>
        </div>
        <div 
            v-if="!user" 
            class="contact">
            <button 
                class="default-border" 
                @click="onSubmit('guest')">
                Contact
            </button>
        </div>
        <login-pop 
            v-if="isLoginVisible" 
            :visible="isLoginVisible" 
            @close="isLoginVisible=false" />
        <modal 
            v-if="isModalVisible" 
            @close="isModalVisible=false">
            <div slot="header" />
            <div slot="body"> 
                <h3>Ask {{ organizer.name }}</h3>
                <p>Send a question to the organizer or about an event.</p>
                <div class="field">
                    <textarea
                        v-model="message" 
                        rows="8"
                        class="message"
                        :class="{ 'error': $v.message.$error }"
                        @input="$v.message.$touch()"
                        type="text" />
                </div>
                <div v-if="$v.message.$error" class="validation-error">
                    <p class="error" v-if="!$v.message.required">Please include a message</p>
                    <p class="error" v-if="!$v.message.maxLength">The message is too long.</p>
                </div>
            </div>
            <div slot="footer">
                <button 
                    :class="{bspin : disabled}" 
                    :disabled="disabled" 
                    class="btn sub" 
                    @click="onSend()">
                    Submit
                </button>
            </div>
        </modal>
        <EmailVerificationModal
            v-if="isVerifyVisible"
            @close="isVerifyVisible = false"
            :user="user" 
            message="verify" />
    </div>
</template>
<script>
    
    import { required, maxLength } from 'vuelidate/lib/validators';
    import formValidationMixin from '../../mixins/form-validation-mixin'
    import EmailVerificationModal from '../profile/components/email-verification-modal.vue'

    export default {

        props: ['loadorganizer', 'user'],

        components: { EmailVerificationModal },

        mixins: [formValidationMixin],

        computed: {
            canMessage() {
                return this.user && this.user.id != this.loadorganizer.user_id && this.user.email_verified_at ? true : false;
            },

            verify() {
                return this.user && !this.user.email_verified_at ? true : false;
            },

            endPoint() {
                return `/message/organizer/${this.organizer.slug}/${this.user.id}`;
            }

        },

        data() {
            return {
                isModalVisible: false,
                isLoginVisible: false,
                isVerifyVisible: false,
                message: '',
                organizer: this.loadorganizer,
                close: false,
                disabled: false,
                serverErrors: [],
            }
        },

        methods: {
            onSubmit(arr){
                return arr == 'guest' ? this.isLoginVisible = true : this.isVerifyVisible = true;
            },

            toggleBodyClass(addRemoveClass, className) {
                const el = document.body;
                if (addRemoveClass === 'addClass') {
                    el.classList.add(className);
                } else {
                    el.classList.remove(className);
                }
            },

            onSend() {
                if (this.checkVuelidate()) { return false };
                axios.post(this.endPoint, {message:this.message})
                .then(res => {
                    console.log(res.data);
                    this.isModalVisible = false;
                    this.message = '';
                    this.disabled = false;
                })
                .catch(err => {
                    this.onErrors(err);
                });
            },
        },

        mounted() {

        },


        watch: {
            isModalVisible() {
                return this.isModalVisible ? this.toggleBodyClass('addClass', 'noscroll') : this.toggleBodyClass('removeClass', 'noscroll');
            },
            isLoginVisible() {
                return this.isLoginVisible ? this.toggleBodyClass('addClass', 'noscroll') : this.toggleBodyClass('removeClass', 'noscroll');
            },
            isVerifyVisible() {
                return this.isVerifyVisible ? this.toggleBodyClass('addClass', 'noscroll') : this.toggleBodyClass('removeClass', 'noscroll');
            },
        },

        validations: {
            message: {
                required,
                maxLength: maxLength(2000),
                sameUser() {
                    return this.user == this.loadorganizer.user_id ? false : true
                }
            },
        },

    }
    
</script>