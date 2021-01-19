<template>
    <div class="login-index grid" :style="pageHeight">
        <div class="login-information">
            <div class="login-information__title">
                <h3>Reset Password</h3>
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
                    <p class="error" v-if="!$v.user.passwordConfirm.sameAsPassword">Passwords must be the same</p>
                    <p class="error" v-if="!$v.user.passwordConfirm.isRequiredRegister">The passwordConfirm is required</p>
                </div>
            </div>
            <div class="field">
                <button type="submit" :disabled="disabled" class="login-button" @click="onSubmit"> Reset </button>
            </div>
        </div>
        <div class="login-image">
            <picture>
                <img :style="pageHeight" src="/storage/website-files/login-image.jpg">
            </picture>
        </div>
        <PasswordForget @close="hideAlerts" v-if="alerts.message" :message="alerts.message"></PasswordForget>
    </div>
    
</template>

<script>
    import _ from 'lodash';
    import { required, requiredIf, maxLength } from 'vuelidate/lib/validators'
    import formValidationMixin from '../../mixins/form-validation-mixin'
    export default {

        props: ['token'],

        mixins: [formValidationMixin],

        computed: {
            passwordIsVisible() {
                return this.fieldType == 'password' ? false : true;
            },

            submitObject() {
                return {
                    email: this.user.email,
                    password: this.user.password,
                    password_confirmation: !this.isLogin ? this.user.passwordConfirm : '',
                    token: this.token,
                }
            },

            endPoint() {
                return '/password/reset'
            },
        }, 

        data() {
            return {
                user: this.initializeUserObject(),
                fieldType: 'password',
                pageHeight:0,
                active: '',
                serverErrors: [],
                alerts: [],
                disabled: false,
            }
        },


        methods: {

            initializeUserObject() {
                return {
                    email: '',
                    password: '',
                    passwordConfirm: '',
                }
            },

            onSubmit() {
                if (this.checkVuelidate()) { return false };
                console.log(this.submitObject);
                axios.post(this.endPoint, this.submitObject)
                .then(res => {  
                   this.isLogin ? location.reload() : this.onRegistered();
                })
                .catch(err => {
                    this.onErrors(err);
                });
            },

            onToggle(arr) {
                this.active = arr;
                arr == 'password' ? this.$v.user.password.$touch : '';
                this.serverErrors = [];
            },

            hideAlerts() {
                this.disabled = false;
                this.alerts = [];
            },

            handleResize() {
                this.pageHeight = `height:${window.innerHeight}px`;
            },

            togglePasswordVisible() {
                this.fieldType = this.fieldType === 'password' ? 'text' : 'password'
            },

            hasServerError(field) {
                return (field && _.has(this, 'serverErrors.' + field) && !_.isEmpty(this.serverErrors[field]));
            },

        },

        created() {
            window.addEventListener('resize', this.handleResize)
            this.handleResize();
        },

        destroyed() {
            window.removeEventListener('resize', this.handleResize)
        },

        validations: {
            user: {
                email: {
                    required,
                    serverFailed(){ return !this.hasServerError('email'); },
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