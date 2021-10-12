<template>
    <div class="login-popup modal">
        <div class="wrapper" ref="wrapper">
            <div class="header">
                <button @click="closeWindow">
                    <svg>
                        <use :xlink:href="`/storage/website-files/icons.svg#ri-close-line`" />
                    </svg>
                </button>
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

        props: {
            page: {
                type: String,
                default: 'login'
            },
        },

        components: { LoginForm, RegisterForm },

        computed: {
        }, 

        data() {
            return {
                isLogin: this.page === 'register' ? false : true,
            }
        },

        methods: {
            update(val) {
                if (this.timeout) 
                    clearTimeout(this.timeout); 
                this.timeout = setTimeout(() => {
                    this.isLogin = val === 'login' ? true : false
                }, 200); // delay
            },
            closeWindow() {
                document.body.classList.remove('noscroll')
                this.$emit('close', false)
            },
            hideAlerts() {
                this.disabled = false;
                this.alerts = false;
            },
            onClickOutside(event) {
                let arr =  this.$refs.wrapper;
                if (!arr || arr.contains(event.target)) return 
                this.$emit('close', false);
            },

        },

        mounted() {
            setTimeout(() => document.addEventListener("click", this.onClickOutside), 200);
            document.body.classList.add('noscroll')
        },

        destroyed() {
            document.removeEventListener("click", this.onClickOutside);
            document.body.classList.remove('noscroll')
        },

    }
</script>