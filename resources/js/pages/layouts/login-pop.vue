<template>
    <div class="login-popup">
        <div 
            ref="myDiv" 
            class="login-index popup">
            <div 
                class="login-information" 
                v-if="isLogin">
                <div 
                    class="login-close-button" 
                    @click="closeWindow">
                    <IconSvg type="delete" />
                </div>
                <div class="login-information__title">
                    <h3>Login</h3>
                </div>
                <div class="">
                    <div class="field">
                        <input 
                            id="email" 
                            type="email" 
                            class="email" 
                            v-model="user.email" 
                            :class="{ active: active == 'email','error': $v.user.email.$error }"
                            @click="onToggle('email')"
                            @blur="active = null" 
                            @input="$v.user.email.$touch"
                            required
                            placeholder="email" 
                            autofocus>
                        <div v-if="$v.user.email.$error" class="validation-error">
                            <p class="error" v-if="!$v.user.email.serverFailed">The login doesn't match our records</p>
                            <p class="error" v-if="!$v.user.email.required">The email is required</p>
                        </div>
                    </div>
                    <div class="field">
                        <input 
                            id="password" 
                            :type="fieldType" 
                            class="pass" 
                            v-model="user.password"
                            :class="{ active: active == 'password','error': $v.user.password.$error ,'error': $v.user.email.$error }"
                            @click="onToggle('password')"
                            @blur="active = null" 
                            @input="$v.user.password.$touch"
                            required
                            placeholder="password">
                        <div v-if="$v.user.password.$error" class="validation-error">
                            <p class="error" v-if="!$v.user.email.serverFailed">The login doesn't match our records</p>
                            <p class="error" v-if="!$v.user.password.required">The password is required</p>
                        </div>
                        <div class="login-information__showpass">
                            <img 
                                v-if="passwordIsVisible" 
                                @click="togglePasswordVisible" 
                                src="/storage/website-files/password-eye.png" 
                                alt="">
                            <img 
                                v-else 
                                @click="togglePasswordVisible" 
                                src="/storage/website-files/password-eye-closed.png" 
                                alt="">
                        </div>
                    </div>
                    <div class="field">
                        <p 
                            @click="onForget" 
                            :class="{ inprogress: disabled}" 
                            class="login-information__forgot-password">
                            Forgot your password?
                        </p>
                    </div>
                    <div class="field">
                        <button 
                            type="submit" 
                            :disabled="disabled" 
                            class="login-button" 
                            @click="onSubmit">
                            Sign In
                        </button>
                    </div>
                    <div class="login-information__switch">
                        <p style="display:inline-block">
                            Don't have an account?
                        </p>
                        <button 
                            class="switch_login" 
                            @click.prevent="isLogin = !isLogin">
                            Join Now
                        </button>
                    </div>
                </div>
            </div>

            <div 
                v-else 
                class="login-information">
                <div 
                    class="login-close-button" 
                    @click="closeWindow">
                    <IconSvg type="delete" />
                </div>
                <div class="login-information__title">
                    <h3>Create Account</h3>
                </div>
                <div class="">
                    <div class="field">
                        <input 
                            id="name" 
                            type="name" 
                            v-model="user.name" 
                            required
                            :class="{ active: active == 'name','error': $v.user.name.$error }"
                            @click="active = 'name'"
                            @blur="active = null" 
                            @input="$v.user.name.$touch"
                            placeholder="Name" 
                            autofocus>
                        <div v-if="$v.user.name.$error" class="validation-error">
                            <p class="error" v-if="!$v.user.name.required">The name is required</p>
                            <p class="error" v-if="!$v.user.name.maxLength">The name too long</p>
                        </div>
                    </div>
                    <div class="field">
                        <input 
                            id="email" 
                            type="email" 
                            v-model="user.email" 
                            required 
                            :class="{ active: active == 'email','error': $v.user.email.$error }"
                            @click="onToggle('email')"
                            @blur="active = null" 
                            @input="$v.user.email.$touch"
                            placeholder="Email" 
                            autofocus>
                        <div v-if="$v.user.email.$error" class="validation-error">
                            <p class="error" v-if="!$v.user.email.required">The email is required</p>
                            <p class="error" v-if="!$v.user.email.serverFailed">{{serverErrors.email[0]}}</p>
                        </div>
                    </div>
                    <div class="field">
                        <input 
                            id="password" 
                            type="password" 
                            class="pass" 
                            v-model="user.password" 
                            :class="{ active: active == 'password','error': $v.user.password.$error }"
                            @click="onToggle('password')"
                            @blur="active = null" 
                            @input="onToggle('password')"
                            @keyup.enter="onSubmit"
                            required
                            placeholder="Password">
                        <div v-if="$v.user.password.$error" class="validation-error">
                            <p class="error" v-if="!$v.user.password.serverFailed">Must be at least 8 characters</p>
                            <p class="error" v-if="!$v.user.password.required">The password is required</p>
                        </div>
                    </div>
                    <div class="field">
                        <input 
                            id="password" 
                            type="password" 
                            class="pass" 
                            v-model="user.passwordConfirm"
                            :class="{ active: active == 'passwordConfirm','error': $v.user.passwordConfirm.$error }"
                            @click="active = 'passwordConfirm'"
                            @blur="active = null" 
                            @input="active = 'passwordConfirm'"
                            @keyup.enter="onSubmit"
                            required
                            placeholder="Confirm Password">
                        <div v-if="$v.user.passwordConfirm.$error" class="validation-error">
                            <p class="error" v-if="!$v.user.passwordConfirm.serverFailed">{{serverErrors.password[0]}}</p>
                            <p class="error" v-if="!$v.user.passwordConfirm.isRequiredRegister">The passwordConfirm is required</p>
                        </div>
                    </div>
                    <div class="field">
                        <button 
                            type="submit" 
                            :disabled="disabled" 
                            class="save" 
                            @click="onSubmit">
                            Register
                        </button>
                    </div>
                    <div class="login-information__switch">
                        <p style="display:inline-block">Already have an account?</p>
                        <button 
                            class="switch_login" 
                            @click.prevent="isLogin = !isLogin">
                            Sign In
                        </button>
                    </div>
                </div>
            </div>
        </div>
        <PasswordForget 
            @close="hideAlerts" 
            v-if="alerts.message" 
            :message="alerts.message" />
    </div>
</template>
<script>
    import _ from 'lodash';
    import { required, maxLength } from 'vuelidate/lib/validators'
    import PasswordForget  from './forgotPassword.vue'
    import formValidationMixin from '../../mixins/form-validation-mixin'
    import IconSvg from '../../components/Svg-icon'
    export default {

        props: ['page', 'redirect'],

        mixins: [formValidationMixin],

        components: { PasswordForget, IconSvg },

        computed: {
            passwordIsVisible() {
                return this.fieldType == 'password' ? false : true;
            },

            submitObject() {
                return {
                    email: this.user.email,
                    password: this.user.password,
                    remember: true,
                    name: this.user.name,
                    password_confirmation: !this.isLogin ? this.user.passwordConfirm : ''
                }
            },

            endPoint() {
                return this.isLogin ? '/login' : '/register'
            },
        }, 

        data() {
            return {
                user: this.initializeUserObject(),
                fieldType: 'password',
                active: '',
                serverErrors: [],
                alerts: false,
                disabled: false,
                isLogin: this.page === 'register' ? false : true,
            }
        },

        methods: {

            initializeUserObject() {
                return {
                    name: '',
                    email: '',
                    password: '',
                    passwordConfirm: '',
                    rememberMe: false,
                }
            },

            async onSubmit() {
                if (this.checkVuelidate()) { return false }
                await axios.post(this.endPoint, this.submitObject)
                .then( res => { 
                    location.reload()
                })
                .catch(err => { this.onErrors(err) });
            },

            async onForget() {
                if (!this.user.email) { return false }
                this.disabled = true;
                await axios.post('/forgot-password', {email:this.user.email})
                .then( res => { this.alerts = res.data })
                .catch( err => { this.onErrors(err) });
            },

            closeWindow() {
                this.conditionalBodyClass(false, 'noscroll')
                this.$emit('close', false)
            },

            hideAlerts() {
                this.disabled = false;
                this.alerts = false;
            },

            togglePasswordVisible() {
                this.fieldType = this.fieldType === 'password' ? 'text' : 'password'
            },

            onAlternateLogin(value) {
                window.location.href = `/login/${value}`
            },

            onToggle(arr) {
                this.active = arr;
                arr == 'password' ? this.$v.user.password.$touch : '';
                this.serverErrors = [];
            },
            hasServerError(field) {
                return (field && _.has(this, 'serverErrors.' + field) && !_.isEmpty(this.serverErrors[field]));
            },
            conditionalBodyClass(bool, className) {
                if (bool) {
                    document.body.classList.add(className)
                    this.$store.commit('showmap', true);
                } else {
                    document.body.classList.remove(className)
                    this.$store.commit('showmap', false);
                }
            },
            // onClickOutside(event) {
            //     let arr =  this.$refs.myDiv;
            //     if (!arr || arr.contains(event.target)) return;
            //     this.$emit('close', false);
            // },

        },

        mounted() {
            // setTimeout(() => document.addEventListener("click", this.onClickOutside), 200);
            this.conditionalBodyClass(true, 'noscroll')
        },

        unmounted() {
            // document.removeEventListener("click", this.onClickOutside);
            this.conditionalBodyClass(false, 'noscroll')
        },

        watch: {
            isLogin() {
                this.$v.$reset();
                this.serverErrors = [];
            }
        },

        validations: {
            user: {
                email: {
                    required,
                    serverFailed(){ return !this.hasServerError('email'); },
                },
                name: {
                    requiredIfRegister() { 
                        return !this.isLogin ? this.user.name ? true : false : true
                    },
                    maxLength: maxLength(50),
                },
                password: {
                    serverFailed(){ return !this.hasServerError('password'); },
                    required
                },
                passwordConfirm: {
                   sameAsPassword() {
                        return !this.isLogin ? this.user.password == this.user.passwordConfirm ? true : false : true
                    }
                }
            }
        },

    }
</script>