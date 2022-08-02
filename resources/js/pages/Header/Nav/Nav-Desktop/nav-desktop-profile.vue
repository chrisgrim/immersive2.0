<template>
    <div ref="dropdown">
        <div 
            :style="`background:${hex}`" 
            @click="show" 
            v-click-outside="hide"
            :class="{ 'shadow-custom-2' : onToggle }"
            class="cursor-pointer overflow-hidden flex justify-center items-center w-12 h-12 rounded-full hover:shadow-custom-2">
            <!-- -->
            <!-- If user is logged in -->
            <template v-if="user">
                <div 
                    class="rounded-full bg-default-red w-4 h-4 absolute top-0 right-0 border border-white"
                    v-if="user.unread" />
                <template v-if="user.largeImagePath">
                    <picture>
                        <source 
                            type="image/webp" 
                            :srcset="`${envImageUrl}${user.thumbImagePath}`"> 
                        <img 
                            class="w-12 h-12"
                            :src="`${envImageUrl}${user.thumbImagePath.slice(0, -4)}jpg?timestamp=${new Date().getTime()}`" 
                            :alt="user.name + `'s account`">
                    </picture>
                </template>
                <template v-else-if="user.gravatar">
                    <img 
                        :src="user.gravatar" 
                        class="w-12 h-12"
                        :alt="user.name + `'s account`">
                </template>
                <template v-else>
                    <h2 class="text-white text-2xl uppercase">
                        {{ user.name.charAt(0) }}
                    </h2>
                </template>
            </template>
            <!-- If user is a guest -->
            <template v-else>
                <svg class="w-10 h-10 fill-white">
                    <use :xlink:href="`/storage/website-files/icons.svg#ri-user-line`" />
                </svg>
            </template>
        </div>

        <ul 
            v-if="onToggle" 
            class="z-10 mt-8 min-w-[24rem] rounded-3xl overflow-hidden block shadow-custom-1 absolute right-0 top-full bg-white py-4">
            <template v-if="user">
                <a 
                    v-if="user.hasMessages"
                    class="font-semibold p-6 cursor-pointer flex whitespace-nowrap w-full items-center hover:bg-slate-100"
                    href="/messages">
                    Inbox
                    <div 
                        class="ml-2 rounded-full bg-default-red w-4 h-4 top-0 right-0"
                        v-if="user.unread" />
                </a>
                <a 
                    v-if="user.isCurator"
                    class="font-semibold p-6 cursor-pointer flex whitespace-nowrap w-full items-center hover:bg-slate-100"
                    href="/communities">
                    Communities
                </a>
                <a 
                    class="font-semibold p-6 cursor-pointer flex whitespace-nowrap w-full items-center hover:bg-slate-100"
                    :href="`/users/${user.id}`">
                    Profile
                </a>
                <a 
                    class="font-semibold p-6 cursor-pointer flex whitespace-nowrap w-full items-center hover:bg-slate-100"
                    href="/account-settings">
                    Account
                </a>
                <a 
                    v-if="user.isCurator"
                    class="font-semibold p-6 cursor-pointer flex whitespace-nowrap w-full items-center hover:bg-slate-100"
                    href="/admin/dashboard">
                    Admin Dashboard
                </a>
                <div 
                    class="font-semibold p-6 cursor-pointer flex whitespace-nowrap w-full items-center hover:bg-slate-100"
                    @click="logout()">
                    Logout
                </div>
            </template>
            <template v-else>
                <div 
                    class="font-semibold p-6 cursor-pointer flex whitespace-nowrap w-full items-center hover:bg-slate-100"
                    @click="onLogin('register')">
                    Register
                </div>
                <div 
                    class="font-semibold p-6 cursor-pointer flex whitespace-nowrap w-full items-center hover:bg-slate-100"
                    @click="onLogin('login')">
                    Login
                </div>
            </template>
        </ul>
        <LoginPopup 
            v-if="loginType"
            @close="loginType = null"
            :page="loginType" />
    </div>
</template>

<script>
    import LoginPopup from '../../../../components/modals/Components/login-modal'
    import clickOutside from '../../../../Directives/clickOutside'
    export default {

        props: ['user'],

        components: { LoginPopup },

        data() {
            return {
                onToggle:false,
                loginType: null,
                hex: this.user ? this.user.hexColor : `#717171`,
                envImageUrl: process.env.MIX_IMAGE_URL,
            };
        },

        methods: {
            async logout() {
                await axios.post('/logout')
                location.reload()
            }, 
            onLogin(value) {
                this.onToggle = false;
                this.loginType = value;
            },
            show() {
                this.onToggle = !this.onToggle
            },
            hide() {
                this.onToggle = false
            },
        },
    }
</script>



