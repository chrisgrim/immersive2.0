<template>
    <nav 
        class="nav mobile" 
        :class="{ fullmap: fullmap, active: bar }">
        <div class="nav-menu">
            <div v-if="!user" />
            <div class="nav-menu-item">
                <a href="/">
                    <div class="nav-icon">
                        <svg viewBox="0 0 256 256">
                            <path 
                                id="EI" 
                                class="cls-1" 
                                d="M149.256,186.943H80.406V144.275h63.908V104.057H80.406V67.443h66.983V27.369H34.506V227.161h114.75V186.943ZM226.121,27.369h-45.9V227.161h45.9V27.369Z" />
                        </svg>
                        <p>Home</p>
                    </div>
                </a>
            </div>
            <div 
                class="nav-menu-item" 
                v-if="user">
                <a href="/account-settings/favorited">
                    <div class="nav-icon">
                        <svg>
                            <use :xlink:href="`/storage/website-files/icons.svg#ri-heart-line`" />
                        </svg>
                        <p>Liked</p>
                    </div>
                </a>
            </div>
            <div 
                class="nav-menu-item" 
                v-else>
                <a href="/login">
                    <div class="nav-icon">
                        <svg>
                            <use :xlink:href="`/storage/website-files/icons.svg#ri-heart-line`" />
                        </svg>
                        <p>Liked</p>
                    </div>
                </a>
            </div>
            <div 
                class="nav-menu-item" 
                v-if="user && user.hasCreatedOrganizers">
                <a href="/create/events/edit">
                    <div class="nav-icon">
                        <svg>
                            <use :xlink:href="`/storage/website-files/icons.svg#ri-add-circle-line`" />
                        </svg>
                        <p>Events</p>
                    </div>
                </a>
            </div>
            <div 
                class="nav-menu-item" 
                v-if="!user">
                <a href="/login">
                    <div class="nav-icon">
                        <svg>
                            <use :xlink:href="`/storage/website-files/icons.svg#ri-user-line`" />
                        </svg>
                        <p>Login</p>
                    </div>
                </a>
            </div>
            <div 
                class="nav-menu-item" 
                v-if="user">
                <a href="/messages">
                    <div class="nav-icon">
                        <div 
                            v-if="user.unread" 
                            class="alert-dot">
                            <svg>
                                <circle cx="20.7" cy="3.7" r="3.1" />
                            </svg>
                        </div>
                        <svg>
                            <use :xlink:href="`/storage/website-files/icons.svg#ri-chat-2-line`" />
                        </svg>
                        <p>Inbox</p>
                    </div>
                </a>
            </div>
            <div 
                class="nav-menu-item" 
                v-if="user">
                <div class="nav-icon">
                    <svg>
                        <circle 
                            cx="12" 
                            cy="12" 
                            r="10.4" />
                    </svg>         
                    <ProfileButton 
                        :mobile="mobile" 
                        :user="user" />
                    <p>
                        Profile
                    </p>
                </div>     
            </div>
        </div>
    </nav>
</template>

<script>
    import ProfileButton from './profile-button.vue'

    export default {

        props:['user', 'navtype', 'fullmap'],

        components: { ProfileButton, },

        computed: {
        },

        data() {
            return {
                bar: true,
                lastScrollPosition: 0,
                mobile: true,
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

        },

        mounted() {
            this.$store.commit('adduser', this.user);
        },

        created () {
            window.addEventListener('scroll', this.handleScroll);
        },
        unmounted () {
            window.removeEventListener('scroll', this.handleScroll);
        }
    }
</script>