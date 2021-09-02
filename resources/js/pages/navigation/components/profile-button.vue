<template>
    <div ref="dropdown">
        <div 
            :style="`background:${hex}`" 
            @click="toggle"
            :class="{active : onToggle}"
            class="nav-dropdown--icon">
            <template v-if="user && user.largeImagePath">
                <picture>
                    <source 
                        height="28" 
                        width="28" 
                        type="image/webp" 
                        :srcset="`/storage/${user.thumbImagePath}`"> 
                    <img 
                        height="28" 
                        width="28" 
                        :src="`/storage/${user.thumbImagePath.slice(0, -4)}jpg`" 
                        :alt="user.name + `'s account`">
                </picture>
            </template>
            <template v-else-if="user && user.gravatar">
                <img 
                    :src="user.gravatar" 
                    height="28" 
                    width="28" 
                    :alt="user.name + `'s account`">
            </template>
            <template v-else-if="user">
                <h2>
                    {{ user.name.charAt(0) }}
                </h2>
            </template>
            <template v-else>
                <svg>
                    <use :xlink:href="`/storage/website-files/icons.svg#ri-user-line`" />
                </svg>
            </template>
        </div>
        <ul 
            v-show="onToggle" 
            class="sub-dropdown">
            <template v-if="user">
                <li v-if="user.hasMessages">
                    <a href="/messages">
                        <div 
                            v-if="user.unread" 
                            class="alert-dot">
                            <svg><circle cx="20.7" cy="3.7" r="3.1" /></svg>
                        </div>
                        Inbox
                    </a>
                </li>
                <li v-if="isModerator">
                    <a href="/communities">
                        Communities
                    </a>
                </li>
                <li>
                    <a :href="`/users/${user.id}`">
                        Profile
                    </a>
                </li>
                <li>
                    <a href="/account-settings">
                        Account
                    </a>
                </li>
                <li v-if="isModerator">
                    <a href="/admin/dashboard">
                        Admin Dashboard
                    </a>
                </li>
                <li>
                    <a 
                        href="#" 
                        @click="logout()">
                        Logout
                    </a>
                </li>
            </template>
            <template v-else>
                <li>
                    <a @click="onSubmit('register')">
                        Register
                    </a>
                </li>
                <li>
                    <a @click="onSubmit('login')">
                        Login
                    </a>
                </li>
            </template>
        </ul>
        <div v-if="login">
            <LoginPopup 
                @close="login = false"
                :page="loginType" />
        </div>
    </div>
</template>

<script>
    import LoginPopup from '../../profile/login-modal'
    export default {

        props: ['user', 'mobile'],

        components: { LoginPopup },

        data() {
            return {
                avatar: this.user && this.user.thumbImagePath ? `/storage/${this.user.thumbImagePath}` : null,
                onToggle:false,
                isModerator: this.user && this.user.type == 'a' || this.user && this.user.type == 'm',
                login: false,
                loginType: 'register',
                hex: this.user ? this.user.hexColor : `#717171`,
            };
        },

        methods: {
            onSubmit(value) {
                this.onToggle = false;
                this.loginType = value;
                this.login = true;

            },
            async logout(){
                await axios.post('/logout');
                location.reload();
            }, 
            toggle() {
                if (this.mobile) {
                    return window.location.href = `/account-settings`;
                }
                return this.onToggle = !this.onToggle;
            },

            onClickOutside(event) {
                let arr =  this.$refs.dropdown;
                if (!arr || arr.contains(event.target)) return;
                this.onToggle = false;
            },
        },

        mounted() {
            document.addEventListener("click", this.onClickOutside);
        },

        beforeUnmount() {
            document.removeEventListener("click", this.onClickOutside);
        }
    }
</script>



