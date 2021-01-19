<template>
    <div>
        <nav 
            v-if="!mobileDevice" 
            class="nav" 
            :class="{ 'fixed' : isFixed, 'absolute' : isAbsolute, 'darkmenu' : blackText }">
            <div class="nav__bar">
                <div class="nav-logo">
                    <a href="/">
                        <svg 
                            xmlns="http://www.w3.org/2000/svg" 
                            xmlns:xlink="http://www.w3.org/1999/xlink" 
                            width="25" 
                            height="25"
                            :fill="logoColor"
                            viewBox="0 0 256 256">
                            <path 
                                id="EI" 
                                class="cls-1" 
                                d="M149.256,186.943H80.406V144.275h63.908V104.057H80.406V67.443h66.983V27.369H34.506V227.161h114.75V186.943ZM226.121,27.369h-45.9V227.161h45.9V27.369Z" />
                        </svg>
                        <p>(Beta)</p>
                    </a>
                </div>
                <div class="nav-search">
                    <vue-nav-search 
                        searchtype="event"
                        v-if="hideSearch" />
                </div>
                <template v-if="hideMenuNav">
                    <div class="nav-menu">
                        <template v-if="user">
                            <div 
                                class="nav-menu-item" 
                                v-if="user.hasMessages ">
                                <a 
                                    class="menu-link" 
                                    href="/messages">
                                    <div 
                                        v-if="user.unread" 
                                        class="active-dot">
                                        <p />
                                    </div>
                                    <div>Inbox</div>
                                </a>
                            </div>
                            <div 
                                class="nav-menu-item" 
                                v-if="!user.hasCreatedOrganizers">
                                <a 
                                    class="menu-link" 
                                    href="/events/create">
                                    <div>Submit Your Experience (Free)</div>
                                </a>
                            </div>
                            <div 
                                class="nav-menu-item" 
                                v-if="user.hasCreatedOrganizers">
                                <a 
                                    class="menu-link" 
                                    href="/create/events/edit">
                                    <div>Your Events</div>
                                </a>
                            </div>
                            <div class="nav-menu-item">
                                <vue-profile-button 
                                    :screenwidth="mobileDevice" 
                                    :user="user" />
                            </div>
                        </template>
                        <template v-else>
                            <div class="nav-menu-item">
                                <a 
                                    class="menu-link" 
                                    href="/register?create=true">
                                    <div>Submit Your Experience (Free)</div>
                                </a>
                            </div>
                            <div class="nav-menu-item">
                                <a 
                                    class="menu-link" 
                                    href="/register">
                                    <div>Register</div>
                                </a>
                            </div>
                            <div class="nav-menu-item">
                                <a 
                                    class="menu-link" 
                                    href="/login">
                                    <div>Login</div>
                                </a>
                            </div>
                        </template>
                    </div>
                </template>
            </div>
        </nav>

        <nav 
            v-if="mobileDevice && showMenu && !fullmap" 
            class="nav mobile" 
            :class="{ fullmap: fullmap, active: bar }">
            <div class="nav-menu">
                <div v-if="!user" />
                <div class="nav-menu-item">
                    <a href="/">
                        <div class="nav-icon">
                            <svg 
                                xmlns="http://www.w3.org/2000/svg" 
                                xmlns:xlink="http://www.w3.org/1999/xlink" 
                                width="25" 
                                height="25"
                                fill="#404040"
                                viewBox="0 0 256 256">
                                <path 
                                    id="EI" 
                                    class="cls-1" 
                                    d="M149.256,186.943H80.406V144.275h63.908V104.057H80.406V67.443h66.983V27.369H34.506V227.161h114.75V186.943ZM226.121,27.369h-45.9V227.161h45.9V27.369Z" />
                            </svg>
                            <p class="mobile-nav-footer__text">Home</p>
                        </div>
                    </a>
                </div>
                <div 
                    class="nav-menu-item" 
                    v-if="user">
                    <a 
                        class="menu-link" 
                        href="/account-settings/favorited">
                        <div class="nav-icon">
                            <svg 
                                viewBox="0 0 24 24" 
                                fill="currentColor" 
                                fill-opacity="0" 
                                stroke="currentColor" 
                                stroke-width="1.5" 
                                focusable="false" 
                                aria-hidden="true" 
                                role="presentation" 
                                stroke-linecap="round" 
                                stroke-linejoin="round" 
                                style="height: 100%; width: 100%; display: inline-block; overflow: visible;">
                                <path 
                                    d="m17.5 2.9c-2.1 0-4.1 1.3-5.4 2.8-1.6-1.6-3.8-3.2-6.2-2.7-1.5.2-2.9 1.2-3.6 2.6-2.3 4.1 1 8.3 3.9 11.1 1.4 1.3 2.8 2.5 4.3 3.6.4.3 1.1.9 1.6.9s1.2-.6 1.6-.9c3.2-2.3 6.6-5.1 8.2-8.8 1.5-3.4 0-8.6-4.4-8.6" 
                                    stroke-linejoin="round" />
                            </svg>
                            <p class="mobile-nav-footer__text">Liked</p>
                        </div>
                    </a>
                </div>
                <div 
                    class="nav-menu-item" 
                    v-else>
                    <a 
                        class="menu-link" 
                        href="/login">
                        <div class="nav-icon">
                            <svg 
                                viewBox="0 0 24 24" 
                                fill="currentColor" 
                                fill-opacity="0" 
                                stroke="currentColor" 
                                stroke-width="1.5" 
                                focusable="false" 
                                aria-hidden="true" 
                                role="presentation" 
                                stroke-linecap="round" 
                                stroke-linejoin="round" 
                                style="height: 100%; width: 100%; display: inline-block; overflow: visible;">
                                <path 
                                    d="m17.5 2.9c-2.1 0-4.1 1.3-5.4 2.8-1.6-1.6-3.8-3.2-6.2-2.7-1.5.2-2.9 1.2-3.6 2.6-2.3 4.1 1 8.3 3.9 11.1 1.4 1.3 2.8 2.5 4.3 3.6.4.3 1.1.9 1.6.9s1.2-.6 1.6-.9c3.2-2.3 6.6-5.1 8.2-8.8 1.5-3.4 0-8.6-4.4-8.6" 
                                    stroke-linejoin="round" /></svg>
                            <p class="mobile-nav-footer__text">Liked</p>
                        </div>
                    </a>
                </div>
                <div 
                    class="nav-menu-item" 
                    v-if="user && user.hasCreatedOrganizers">
                    <a 
                        class="menu-link" 
                        href="/create/events/edit">
                        <div class="nav-icon">
                            <svg 
                                viewBox="0 0 24 24" 
                                fill="currentColor" 
                                fill-opacity="0" 
                                stroke="currentColor" 
                                stroke-width="1.5" 
                                focusable="false" 
                                aria-hidden="true" 
                                role="presentation" 
                                stroke-linecap="round" 
                                stroke-linejoin="round" 
                                style="height: 100%; width: 100%; display: inline-block; overflow: visible;">
                                <circle 
                                    class="st0" 
                                    cx="12" 
                                    cy="12.2" 
                                    r="10.2" />
                                <line 
                                    class="st0" 
                                    x1="12" 
                                    y1="8.6" 
                                    x2="12" 
                                    y2="15.9" />
                                <line 
                                    class="st0" 
                                    x1="8.3" 
                                    y1="12.2" 
                                    x2="15.7" 
                                    y2="12.2" />
                            </svg>
                            <p class="mobile-nav-footer__text">Events</p>
                        </div>
                    </a>
                </div>
                <div 
                    class="nav-menu-item" 
                    v-if="user && !user.hasCreatedOrganizers">
                    <a 
                        class="menu-link" 
                        href="/events/create">
                        <div class="nav-icon">
                            <svg 
                                viewBox="0 0 24 24" 
                                fill="currentColor" 
                                fill-opacity="0" 
                                stroke="currentColor" 
                                stroke-width="1.5" 
                                focusable="false" 
                                aria-hidden="true" 
                                role="presentation" 
                                stroke-linecap="round" 
                                stroke-linejoin="round" 
                                style="height: 100%; width: 100%; display: inline-block; overflow: visible;">
                                <circle 
                                    class="st0" 
                                    cx="12" 
                                    cy="12.2" 
                                    r="10.2" />
                                <line 
                                    class="st0" 
                                    x1="12" 
                                    y1="8.6" 
                                    x2="12" 
                                    y2="15.9" />
                                <line 
                                    class="st0" 
                                    x1="8.3" 
                                    y1="12.2" 
                                    x2="15.7" 
                                    y2="12.2" />
                            </svg>
                            <p class="mobile-nav-footer__text">Events</p>
                        </div>
                    </a>
                </div>
                <div 
                    class="nav-menu-item" 
                    v-if="!user">
                    <a 
                        class="menu-link" 
                        href="/login">
                        <div class="nav-icon">
                            <svg 
                                viewBox="0 0 24 24" 
                                fill="currentColor" 
                                fill-opacity="0" 
                                stroke="currentColor" 
                                stroke-width="1.5" 
                                focusable="false" 
                                aria-hidden="true" 
                                role="presentation" 
                                stroke-linecap="round" 
                                stroke-linejoin="round" 
                                style="height: 100%; width: 100%; display: inline-block; overflow: visible;">
                                <path 
                                    class="st0" 
                                    d="M18.6,22.7c0.5,0,0.9-0.3,0.9-0.8c0,0,0-0.1,0-0.1v-4.3c0-2.8-2.3-5.1-5.1-5.1H9.4c-2.8,0-5.1,2.3-5.1,5.1
                v4.3c0,0.5,0.3,0.9,0.8,0.9" />
                                <path 
                                    class="st0" 
                                    d="M11.9,10.7c2.3,0,4.2-1.9,4.2-4.3c0-2.4-1.9-4.3-4.2-4.3C9.6,2.1,7.7,4,7.7,6.4c0,0,0,0,0,0
                C7.7,8.7,9.6,10.6,11.9,10.7z" />
                            </svg>
                            <p class="mobile-nav-footer__text">Login</p>
                        </div>
                    </a>
                </div>
                <div 
                    class="nav-menu-item" 
                    v-if="user">
                    <a 
                        class="menu-link" 
                        href="/messages">
                        <div class="nav-icon">
                            <div 
                                v-if="user.unread" 
                                class="nav-icon active">
                                <svg 
                                    viewBox="0 0 24 24" 
                                    fill="red" 
                                    fill-opacity="1" 
                                    stroke="red" 
                                    stroke-width="1.5" 
                                    focusable="false" 
                                    aria-hidden="true" 
                                    role="presentation" 
                                    stroke-linecap="round" 
                                    stroke-linejoin="round" 
                                    style="height: 24px; width: 24px; display: inline-block; overflow: visible;">
                                    <circle 
                                        cx="20.7" 
                                        cy="3.7" 
                                        r="3.1" />
                                </svg>
                            </div>
                            <svg 
                                viewBox="0 0 24 24" 
                                fill="currentColor" 
                                fill-opacity="0" 
                                stroke="currentColor" 
                                stroke-width="1.5" 
                                focusable="false" 
                                aria-hidden="true" 
                                role="presentation" 
                                stroke-linecap="round" 
                                stroke-linejoin="round" 
                                style="height: 24px; width: 24px; display: inline-block; overflow: visible;">
                                <path d="M18,19c2.6,0,4.6-1.2,4.5-3.5v-9C22.5,4,20.5,2,18,2H6C3.5,2,1.5,4,1.5,6.5v9c0,2.3,2,3.5,4.5,3.5h2.6 c0.6,0,1.1,0.3,1.5,0.8l1.9,2.7l1.4-2.3c0.2-0.3,0.5-0.6,0.8-0.8c0.4-0.2,0.9-0.4,1.3-0.4H18z" />
                            </svg>
                            <p class="mobile-nav-footer__text">Inbox</p>
                        </div>
                    </a>
                </div>
                <div 
                    class="nav-menu-item" 
                    v-if="user">
                    <div class="nav-icon">
                        <svg 
                            viewBox="0 0 24 24" 
                            fill="currentColor" 
                            fill-opacity="0" 
                            stroke="currentColor" 
                            stroke-width="1.5" 
                            focusable="false" 
                            aria-hidden="true" 
                            role="presentation" 
                            stroke-linecap="round" 
                            stroke-linejoin="round" 
                            style="height: 100%; width: 100%; display: inline-block; overflow: visible;">
                            <circle 
                                cx="12" 
                                cy="12" 
                                r="10.4" />
                        </svg>         
                        <vue-profile-button 
                            :screenwidth="mobileDevice" 
                            :user="user" />
                        <p class="mobile-nav-footer__text">
                            Profile
                        </p>
                    </div>     
                </div>
            </div>
        </nav>
        <nav v-else />
    </div>
</template>

<script>

    import vueProfileButton from './profile-button.vue'
    import vueNavSearch from './nav-search.vue'

	export default {

        props:['user', 'navtype'],

        components: { vueProfileButton, vueNavSearch },

        computed: {
            fullmap() {
                return this.$store.state.map || this.$store.state.mobilelocation || this.$store.state.mobiledates || this.$store.state.filter;
            },
            showMenu() {
                return this.navtype != 'show' && this.navtype != 'messageshow' 
            },
            hideMenuNav() {
                return this.navtype != 'create'
            },
            hideSearch() {
                return this.navtype != 'homepage' && this.navtype != 'create' && this.navtype !='onlinesearch' && this.navtype !='searchpage'
            },
            isFixed() {
                return this.navtype == 'searchpage'
            },
            isAbsolute() {
                return this.navtype == 'homepage' || this.navtype == 'org' || this.navtype == 'create'
            },
            blackText() {
                return this.navtype == 'org'
            },
            logoColor() {
                return this.navtype == 'homepage' || this.navtype == 'org' || this.navtype == 'searchpage' ? 'white' : '#404040'
            }
        },

		data() {
			return {
                bar: true,
                lastScrollPosition: 0,
                mobileDevice: false,
                page: this.navtype,
			};
		},

		methods: {

            handleScroll () {
                const currentScrollPosition = window.pageYOffset || document.documentElement.scrollTop;
                if (currentScrollPosition < 100) {
                    return this.bar = true;
                }
                if (Math.abs(currentScrollPosition - this.lastScrollPosition) < 60) {
                    return
                }
                this.bar = currentScrollPosition < this.lastScrollPosition;
                this.lastScrollPosition = currentScrollPosition ;
            },

            handleResize() {
                window.innerWidth < 768 ? this.mobileDevice = true : false;
                window.innerWidth > 768 ? this.mobileDevice = false : true;
            },

            goBack() {
                window.history.back();
            }
		},

        mounted() {
            this.$store.commit('adduser', this.user);
        },

        created () {
            window.addEventListener('resize', this.handleResize);
            window.addEventListener('scroll', this.handleScroll);
            this.handleResize();
        },
        unmounted () {
            window.removeEventListener('resize', this.handleResize)
            window.removeEventListener('scroll', this.handleScroll);
        }
	}
</script>



