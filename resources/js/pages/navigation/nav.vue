<template>
    <div>
        <template v-if="!mobile">
            <NavDesktop
                :navtype="navtype"
                :user="user"
                :passedclass="{ 'fixed' : isFixed, 'absolute' : isAbsolute, 'narrow' : isNarrow, 'fullwidth' : isFullWidth }" />
        </template>
        <template v-if="mobile && showMenu && !fullmap">
            <NavMobile
                :navtype="navtype"
                :user="user"
                :fullmap="fullmap" />
        </template>
        <template v-else>
            <nav />
        </template>
    </div>
</template>

<script>
    import NavDesktop from './components/nav-desktop.vue'
    import NavMobile from './components/nav-mobile.vue'

    export default {

        props:['user', 'navtype'],

        components: { NavDesktop, NavMobile },

        computed: {
            fullmap() {
                return this.$store.state.map || this.$store.state.mobilelocation || this.$store.state.mobiledates || this.$store.state.filter;
            },
            showMenu() {
                return this.navtype != 'show' && this.navtype != 'messageshow' 
            },
            isFixed() {
                return this.navtype ==='searchlocation'
            },
            isAbsolute() {
                return this.navtype == 'homepage' || this.navtype == 'create'
            },
            isNarrow() {
                return this.navtype == 'show' || this.navtype == 'org'
            },
            logoColor() {
                return this.navtype == 'homepage' ? 'white' : '#404040'
            },
            isFullWidth() {
                return this.navtype ==='searchlocation'
            }
        },

        data() {
            return {
                mobile: window.innerWidth < 768 ? true : false,
            };
        },

        methods: {

        },

        mounted() {
            this.$store.commit('adduser', this.user);
        },
    }
</script>



