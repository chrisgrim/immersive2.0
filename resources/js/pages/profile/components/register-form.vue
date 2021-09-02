<template>
    <div class="login-form">
        <div class="field">
            <input 
                id="name" 
                type="name" 
                v-model="user.name" 
                required
                :class="{ 'error': $v.user.name.$error }"
                @input="$v.user.name.$touch"
                @keyup.enter="onSubmit"
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
                :class="{ 'error': $v.user.email.$error }"
                @keyup.enter="onSubmit"
                @input="clear('email')"
                placeholder="Email" 
                autofocus>
            <div v-if="$v.user.email.$error" class="validation-error">
                <p class="error" v-if="!$v.user.email.required">The email is required</p>
                <p class="error" v-if="!$v.user.email.email">Must be a valid email</p>
                <p class="error" v-if="!$v.user.email.serverFailed">{{serverErrors.email[0]}}</p>
            </div>
        </div>
        <div class="field">
            <input 
                id="password" 
                type="password" 
                v-model="user.password" 
                :class="{ 'error': $v.user.password.$error }"
                @input="clear('password')"
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
                v-model="user.password_confirmation"
                :class="{ 'error': $v.user.password_confirmation.$error }"
                @keyup.enter="onSubmit"
                required
                placeholder="Confirm Password">
            <div v-if="$v.user.password_confirmation.$error" class="validation-error">
                <p class="error" v-if="!$v.user.password_confirmation.serverFailed">Passwords must match</p>
            </div>
        </div>
        <div class="field">
            <button 
                type="submit" 
                :disabled="disabled" 
                class="btn-login submit" 
                @click="onSubmit">
                Continue
            </button>
        </div>
        <div>
            <p style="display:inline-block"> Already have an account? </p>
            <button 
                class="btn underline btn-borderless forget"
                :class="{ inprogress: disabled}"
                @click.prevent="onLogin">
                Sign In
            </button>
        </div>
    </div>
</template>

<script>
    import { required, maxLength, email } from 'vuelidate/lib/validators'
    import formValidationMixin from '../../../mixins/form-validation-mixin'
    export default {

        mixins: [ formValidationMixin ],

        computed: {
            isDisabled() {
                return this.disabled || Object.keys(this.serverErrors).length !== 0;
            }
        }, 

        data() {
            return {
                user: this.initializeUserObject(),
                serverErrors: [],
                disabled: false,
            }
        },

        methods: {
            async onSubmit() {
                console.log(this.user);
                if (this.checkVuelidate()) { return false }
                await axios.post('/register', this.user)
                .then( res => {  location.reload() })
                .catch(err => { this.onErrors(err) });
            },
            onLogin() {
                this.$emit('update', 'login')
            },
            clear(val) {
                val==='email' ? this.$v.user.email.$touch() : this.$v.user.password.$touch()
                this.serverErrors = []
            },
            initializeUserObject() {
                return {
                    name: '',
                    email: '',
                    password: '',
                    password_confirmation: '',
                    rememberMe: false,
                }
            },
        },

        validations: {
            user: {
                email: {
                    required,
                    email,
                    serverFailed(){ return !this.serverErrors.email },
                },
                name: {
                    required,
                    maxLength: maxLength(128),
                },
                password: {
                    required,
                    maxLength: maxLength(128),
                },
                password_confirmation: {
                    sameAsPassword() { return this.user.password === this.user.password_confirmation }
                }
            }
        },

    }
</script>