<template>
    <NavMobile
        :navtype="navtype"
        :user="user" />
</template>

<script>
    import NavMobile from './Nav/nav-mobile.vue'
    export default {

        props:['user', 'navtype'],

        components: { NavMobile },

        computed: {
            showMenu() {
                return this.navtype != 'show' && this.navtype != 'messageshow' 
            },
            isFixed() {
                return this.navtype ==='searchlocation'
            },
            isAbsolute() {
                return this.navtype == 'create'
            },
            isNarrow() {
                return this.navtype == 'show' || this.navtype == 'org' || this.navtype == 'post'
            },
            isFullWidth() {
                return this.navtype ==='searchlocation'
            }
        },
        data() {
            return {
            };
        },
    }
</script>



