<template>
    <div class="login-form">
        <div class="field">
            <input 
                id="email" 
                type="email" 
                class="email" 
                v-model="user.email" 
                :class="{ 'error': $v.user.email.$error }"
                @input="clear('email')"
                @keyup.enter="onSubmit"
                required
                placeholder="email" 
                autofocus>
            <div v-if="$v.user.email.$error" class="validation-error">
                <p class="error" v-if="!$v.user.email.required">The email is required</p>
            </div>
        </div>
        <div class="field">
            <input 
                id="password" 
                :type="isVisible ? 'text' : 'password'" 
                v-model="user.password"
                :class="{'error': $v.user.password.$error || $v.user.email.serverFailed.$error }"
                @input="clear('password')"
                @keyup.enter="onSubmit"
                required
                placeholder="password">
            <div v-if="$v.user.password.$error" class="validation-error">
                <p class="error" v-if="!$v.user.password.required">The password is required</p>
            </div>
            <div v-if="$v.user.email.serverFailed.$error" class="validation-error">
                <p class="error" v-if="!$v.user.email.serverFailed">The login information doesn't match our records</p>
            </div>
            <div class="password">
                <svg @click="isVisible=!isVisible">
                    <use 
                        v-if="isVisible" 
                        :xlink:href="`/storage/website-files/icons.svg#ri-eye-off-line`" />
                    <use 
                        v-else 
                        :xlink:href="`/storage/website-files/icons.svg#ri-eye-line`" />
                </svg>
            </div>
        </div>
        <div class="field">
            <transition name="fade" mode="out-in">
                <template v-if="!forget">
                    <button 
                        @click="onForget" 
                        class="btn underline btn-borderless forget"
                        :class="{ inprogress: disabled}">
                        Forgot your password?
                    </button>
                </template>
                <template v-else>
                    <div class="notification-a">
                        <button @click="forget=false">
                            <svg>
                                <use :xlink:href="`/storage/website-files/icons.svg#ri-close-line`" />
                            </svg>
                        </button>
                        <h3>We have emailed you a reset password link.</h3>
                        <p>Please check your email.</p>
                    </div>
                </template>
            </transition>
        </div>
        <div class="field">
            <button 
                type="submit" 
                :disabled="isDisabled" 
                class="btn-login submit" 
                @click="onSubmit">
                Continue
            </button>
        </div>
        <div>
            <p style="display:inline-block"> Don't have an account? </p>
            <button 
                class="btn underline btn-borderless forget"
                :class="{ inprogress: disabled}"
                @click.prevent="onRegister">
                Join Now
            </button>
        </div>
    </div>
</template>

<script>
    import { required } from 'vuelidate/lib/validators'
    import formValidationMixin from '../../../mixins/form-validation-mixin'
    export default {

        mixins: [formValidationMixin],

        computed: {
            isDisabled() {
                return this.disabled || Object.keys(this.serverErrors).length !== 0;
            }
        }, 

        data() {
            return {
                user: this.initializeUserObject(),
                serverErrors: [],
                forget: false,
                alerts: false,
                disabled: false,
                isVisible: false,       
            }
        },

        methods: {
            async onSubmit() {
                if (this.checkVuelidate()) { return false }
                await axios.post(`/login`, this.user)
                .then( res => {  location.reload() })
                .catch(err => { this.onErrors(err) });
            },
            async onForget() {
                if (!this.user.email) { return false }
                this.disabled = true;
                await axios.post('/forgot-password', { email:this.user.email })
                .then( res => { 
                    this.disabled = false
                    this.forget = true;
                    setTimeout(() => this.forget = false, 10000);
                })
                .catch( err => { this.onErrors(err) });
            },
            onRegister() {
                this.$emit('update', 'register')
            },
            hideAlerts() {
                this.disabled = false;
                this.alerts = false;
            },
            clear(val) {
                val==='email' ? this.$v.user.email.$touch() : this.$v.user.password.$touch()
                this.serverErrors = [];
            },
            initializeUserObject() {
                return {
                    name: '',
                    email: '',
                    password: '',
                    passwordConfirm: '',
                    rememberMe: false,
                }
            },
        },

        validations: {
            user: {
                email: {
                    required,
                    serverFailed(){ return !this.serverErrors.email},
                },
                password: {
                    serverFailed(){ return !this.serverErrors.password },
                    required
                },
            }
        },

    }
</script>