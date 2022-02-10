<template>
    <div class="login-form">
        <div class="field">
            <h3 class="font-medium">Welcome to Everything Immersive.</h3>
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
                :class="{'error': $v.user.password.$error || !$v.user.email.serverFailed }"
                @input="clear('password')"
                @keyup.enter="onSubmit"
                required
                placeholder="password">
            <div v-if="$v.user.password.$error" class="validation-error">
                <p class="error" v-if="!$v.user.password.required">The password is required</p>
            </div>
            <div v-if="$v.user.email.$error" class="validation-error">
                <p class="error" v-if="!$v.user.email.serverFailed">The login information doesn't match our records</p>
            </div>
            <div class="password">
                <svg 
                    class="w-8 h-8 absolute top-6 right-12"
                    @click="isVisible=!isVisible">
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
                        class="underline border-none underline text-xl mb-8"
                        :class="{ inprogress: disabled}">
                        Forgot your password?
                    </button>
                </template>
                <template v-else>
                    <div class="rounded-2xl text-white mb-4 p-4 bg-gradient-to-r from-cyan-500 to-blue-500">
                        <button 
                            class="top-1.5 right-1.5 absolute border-white rounded-full z-10 hover:bg-white"
                            @click="forget=false">
                            <svg class="w-8 h-8 fill-white hover:fill-black">
                                <use :xlink:href="`/storage/website-files/icons.svg#ri-close-line`" />
                            </svg>
                        </button>
                        <h3 class="text-white">We have emailed you a reset password link.</h3>
                        <p class="text-white">Please check your email.</p>
                    </div>
                </template>
            </transition>
        </div>
        <div class="field">
            <button 
                type="submit" 
                :disabled="isDisabled" 
                class="mb-4 font-medium py-6 px-4 rounded-2xl w-full border-none text-white float-right bg-gradient-to-r from-button-red-1 via-button-red-2 to-button-red-3 md:px-20 hover:from-button-red-2 hover:via-button-red-3 hover:to-button-red-1"
                @click="onSubmit">
                Continue
            </button>
        </div>
        <div>
            <p style="display:inline-block"> Don't have an account? </p>
            <button 
                class="underline border-none text-xl"
                @click.prevent="onRegister">
                Join Now
            </button>
        </div>
    </div>
</template>

<script>
    import { required } from 'vuelidate/lib/validators'
    import formValidationMixin from '../../../../mixins/form-validation-mixin'
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
                .catch( err => { this.onErrors(err) })
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