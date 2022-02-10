<template>
    <nav 
        v-if="bar"
        class="nav absolute z-[2002]">
        <div
            class="nav-menu fixed inset-x-0 bottom-0 border-t-[1px] bg-white w-full z-[1001] h-28 flex justify-evenly items-center px-[5%] text-center">
            <!--  -->
            <!-- Home -->
            <div class="w-24 h-20 flex items-center justify-center">
                <a href="/">
                    <div class="flex flex-col items-center">
                        <svg class="w-10 h-10 fill-gray-400" viewBox="0 0 256 256">
                            <path 
                                id="EI" 
                                class="cls-1" 
                                d="M149.256,186.943H80.406V144.275h63.908V104.057H80.406V67.443h66.983V27.369H34.506V227.161h114.75V186.943ZM226.121,27.369h-45.9V227.161h45.9V27.369Z" />
                        </svg>
                        <p class="text-base">Home</p>
                    </div>
                </a>
            </div>

            <!-- Liked -->
            <div class="w-24 h-20 flex items-center justify-center relative">
                <a :href="user ? '/account-settings/favorited' : '/login'">
                    <div class="flex flex-col items-center">
                        <svg class="w-10 h-10 fill-gray-400">
                            <use :xlink:href="`/storage/website-files/icons.svg#ri-heart-line`" />
                        </svg>
                        <p class="text-base">Liked</p>
                    </div>
                </a>
            </div>

            <template v-if="user">
                <div 
                    class="w-24 h-20 flex items-center justify-center relative"
                    v-if="user.hasCreatedOrganizers">
                    <a href="/create/events/edit">
                        <div class="flex flex-col items-center">
                            <svg class="w-10 h-10 fill-gray-400">
                                <use :xlink:href="`/storage/website-files/icons.svg#ri-add-circle-line`" />
                            </svg>
                            <p class="text-base">Events</p>
                        </div>
                    </a>
                </div>
                <div class="w-24 h-20 flex items-center justify-center relative">
                    <a href="/messages">
                        <div class="flex flex-col items-center">
                            <div 
                                v-if="user.unread" 
                                class="alert-dot">
                                <svg class="w-4 h-4 fill-red-400">
                                    <circle cx="20.7" cy="3.7" r="3.1" />
                                </svg>
                            </div>
                            <svg class="w-10 h-10 fill-gray-400">
                                <use :xlink:href="`/storage/website-files/icons.svg#ri-chat-2-line`" />
                            </svg>
                            <p class="text-base">Inbox</p>
                        </div>
                    </a>
                </div>
                <div class="w-24 h-20 flex items-center justify-center relative">
                    <div class="flex flex-col items-center">
                        <ProfileButton :user="user" />
                        <p class="text-base">Profile</p>
                    </div>     
                </div>
            </template>

            <template v-else>
                <div class="w-24 h-20 flex items-center justify-center relative">
                    <a href="/login">
                        <div class="flex flex-col items-center">
                            <svg class="w-10 h-10 fill-gray-400">
                                <use :xlink:href="`/storage/website-files/icons.svg#ri-user-line`" />
                            </svg>
                            <p class="text-base">Login</p>
                        </div>
                    </a>
                </div>
            </template>
        </div>
    </nav>
</template>

<script>
    import ProfileButton from './Nav-Mobile/nav-mobile-profile.vue'
    export default {

        props:['user', 'navtype'],

        components: { ProfileButton },

        data() {
            return {
                bar: true,
                lastScrollPosition: 0,
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

        created() {
            window.addEventListener('scroll', this.handleScroll);
        },

        destroyed() {
            window.removeEventListener('scroll', this.handleScroll);
        }
    }
</script>