<template>
    <NavDesktop
        :narrow="isNarrow"
        :fullwidth="isFullWidth"
        :user="user" />
</template>

<script>
    import NavDesktop from './Nav/nav-desktop.vue'
    export default {

        props:['user', 'navtype'],

        components: { NavDesktop },

        computed: {
            hasMenu() {
                return this.navtype != 'show' && this.navtype != 'messageshow' 
            },
            isNarrow() {
                return this.navtype == 'show' || this.navtype == 'org' || this.navtype == 'post'
            },
            isFullWidth() {
                return this.navtype ==='searchlocation'
            }
        },
    }
</script>



