<template>
    <div class="login-home modal">
        <div class="wrapper">
            <div class="header">
                <p>{{ isLogin ? `Login` : `Register` }}</p>
            </div>
            <div class="body">
                <template v-if="isLogin">
                    <LoginForm @update="update" />
                </template>
                <template v-else>
                    <RegisterForm @update="update" />
                </template>
            </div>
        </div>
    </div>
</template>

<script>
    import LoginForm from './components/login-form'
    import RegisterForm from './components/register-form'
    export default {

        components: { LoginForm, RegisterForm },


        data() {
            return {
                isLogin: location.pathname == '/login' ? true : false,
                createEvent: new URL(window.location.href).searchParams.get("create"),
            }
        },


        methods: {
            update(val) {
                this.isLogin = val === 'login' ? true : false
            },
            hideAlerts() {
                this.disabled = false;
                this.alerts = false;
            },
        },


    }
</script>