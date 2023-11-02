<template>
    <NavDesktop
        :on-class="onClass"
        :hassearch="hasSearch"
        :user="user" />
</template>

<script>
    import NavDesktop from './Nav/nav-desktop.vue'
    export default {

        props:['user', 'navtype'],

        components: { NavDesktop },

        computed: {
            hasSearch() {
                return true
                // return this.navtype !=='homepage'
            }
        },
        data() {
            return {
                onClass: {
                    nav: this.getNavCss(),
                    navBar: this.getNavBarCss(),
                    brand: this.navtype === 'homepage' ? 'fill-black' : 'fill-black',
                    links: this.navtype === 'homepage' ? 'text-black hover:text-default-red text-xl font-medium' : 'text-black hover:text-default-red text-xl font-medium',
                }
            };
        },

        methods: {
            getNavCss() {
                if (this.navtype === 'homepage') { return 'absolute after:bg-transparent' }
                if (this.navtype === 'searchlocation') { return 'fixed max-w-none px-8 after:bg-white' }
                return 'relative after:bg-white'
            },
            getNavBarCss() {
                if (this.navtype === 'show' || this.navtype === 'org' || this.navtype === 'post') { return 'max-w-screen-xl px-32' }
                if (this.navtype === 'homepage') { return '' }
                if (this.navtype === 'searchlocation') { return 'md:px-0 lg:px-0' }
                return ''
            }
        }
    }
</script>



