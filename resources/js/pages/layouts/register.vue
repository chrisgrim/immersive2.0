<template>
    <div 
        class="login-index grid" 
        :style="pageHeight">
        <div 
            v-if="isLogin" 
            class="login-information">
            <div class="login-information__title">
                <h3>Everything Immersive</h3>
            </div>
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
                    @keyup.enter="onSubmit"
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
                    :class="{ active: active == 'password','error': $v.user.password.$error, 'error': $v.user.email.$error }"
                    @click="onToggle('password')"
                    @blur="active = null" 
                    @keyup.enter="onSubmit"
                    @input="onToggle('password')"
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
            <!--
            <div class="login-information__line">
                <hr>
                <span>or</span>
            </div>
            <div class="login-information__social-login grid">
                <button @click="onAlternateLogin('Facebook')" class="social-login">
                    <p>Facebook</p>
                </button>
               <button @click="onAlternateLogin('Google')" class="social-login">
                    <p>Google</p>
               </button>
            </div> 
            -->
            <div class="login-information__switch">
                <p>Don't have an account? 
                    <button 
                        @click.prevent="onPageSwitch" 
                        class="switch_login">
                        Join now for free
                    </button>
                </p>
            </div>
        </div>
        <div 
            v-else 
            class="login-information">
            <div class="login-information__title">
                <h3 v-if="!createEvent">
                    Everything Immersive
                </h3>
                <h3 v-else>
                    Register now to submit your experience to Everything Immersive
                </h3>
            </div>
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
                    @keyup.enter="onSubmit"
                    placeholder="Name" 
                    autofocus>
                <div v-if="$v.user.name.$error" class="validation-error">
                    <p class="error" v-if="!$v.user.name.requiredIfRegister">The name is required</p>
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
                    @keyup.enter="onSubmit"
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
                    id="passwordConfirm" 
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
                    <p class="error" v-if="!$v.user.passwordConfirm.sameAsPassword">Passwords must be the same</p>
                    <p class="error" v-if="!$v.user.passwordConfirm.isRequiredRegister">The passwordConfirm is required</p>
                </div>
            </div>
            <div class="field">
                <button type="submit" :disabled="disabled" class="login-button" @click="onSubmit"> Register (Free)</button>
            </div>
            <!--
            <div class="login-information__line">
                <hr>
                <span>or</span>
            </div>
            <div class="login-information__social-login grid">
                <button @click="onAlternateLogin('Facebook')" class="social-login">
                    <p>Facebook</p>
                </button>
               <button @click="onAlternateLogin('Google')" class="social-login">
                    <p>Google</p>
               </button>
            </div>
            -->
            <div class="login-information__switch">
                <p>
                    Already have an account? 
                    <button 
                        @click.prevent="onPageSwitch" 
                        class="switch_login">
                        Sign In Now
                    </button>
                </p>
            </div>
        </div>
        <div class="login-image">
            <picture>
                <img 
                    :style="pageHeight" 
                    src="/storage/website-files/login-image.jpg">
            </picture>
        </div>
        <PasswordForget 
            @close="hideAlerts" 
            v-if="alerts" />
    </div>
</template>

<script>
    import _ from 'lodash';
    import { required, maxLength } from 'vuelidate/lib/validators'
    import CubeSpinner  from '../layouts/loading.vue'
    import PasswordForget  from './forgotPassword.vue'
    import formValidationMixin from '../../mixins/form-validation-mixin'
    export default {

        mixins: [formValidationMixin],

        components: { CubeSpinner, PasswordForget },

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
                pageHeight:0,
                active: '',
                serverErrors: [],
                alerts: false,
                disabled: false,
                isLogin: location.pathname == '/login' ? true : false,
                createEvent: new URL(window.location.href).searchParams.get("create"),
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
                .catch(err => { this.onErrors(err) });
                this.isLogin ? location.reload() : this.onRegistered();
            },

            async onForget() {
                if (!this.user.email) { return false }
                this.disabled = true;
                await axios.post('/forgot-password', {email:this.user.email})
                .then( res => { this.alerts = res.data })
                .catch( err => { this.onErrors(err) });
            },

            onToggle(arr) {
                this.active = arr;
                arr == 'password' ? this.$v.user.password.$touch : '';
                this.serverErrors = [];
            },

            hideAlerts() {
                this.disabled = false;
                this.alerts = false;
            },

            handleResize() {
                this.pageHeight = `height:${window.innerHeight}px`;
            },

            onPageSwitch() {
                this.serverErrors = [];
                this.$v.$reset();
                this.isLogin = !this.isLogin;
            },

            togglePasswordVisible() {
                this.fieldType = this.fieldType === 'password' ? 'text' : 'password'
            },

            onAlternateLogin(value) {
                window.location.href = `/login/${value}`
            },

            hasServerError(field) {
                return (field && _.has(this, 'serverErrors.' + field) && !_.isEmpty(this.serverErrors[field]));
            },

        },

        created() {
            window.addEventListener('resize', this.handleResize)
            this.handleResize();
        },

        unmounted() {
            window.removeEventListener('resize', this.handleResize)
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