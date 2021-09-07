<template>
    <div class="div">
        <nav 
            ref="navdrop"
            class="nav"
            :class="navClass">
            <div 
                :class="{ open : searchOpen}"
                class="nav__bar">
                <div class="nav-logo">
                    <a href="/">
                        <svg viewBox="0 0 256 256">
                            <path 
                                id="EI" 
                                class="cls-1" 
                                d="M149.256,186.943H80.406V144.275h63.908V104.057H80.406V67.443h66.983V27.369H34.506V227.161h114.75V186.943ZM226.121,27.369h-45.9V227.161h45.9V27.369Z" />
                        </svg>
                        <p>(Beta)</p>
                    </a>
                </div>
                <div class="nav-search">
                    <div
                        v-if="!searchOpen"
                        class="cover" 
                        @click="openSearch" />
                    <template v-if="hideSearch">
                        <template v-if="searchOpen">
                            <div class="search-options">
                                <div class="tabs">
                                    <button 
                                        @click="searchOpen='l'"
                                        :class="{active : searchOpen==='l'}"
                                        class="btn-borderless">
                                        Locations
                                    </button>
                                    <button 
                                        @click="searchOpen='e'"
                                        :class="{active : searchOpen==='e'}"
                                        class="btn-borderless"> 
                                        Listings
                                    </button>
                                    <button 
                                        @click="searchOpen='t'"
                                        :class="{active : searchOpen==='t'}"
                                        class="btn-borderless"> 
                                        Tags 
                                    </button>
                                </div>
                            </div>
                        </template>
                        <SearchBar :open="searchOpen" />
                    </template>
                </div>
                <template v-if="hideMenuNav">
                    <div class="nav-menu">
                        <template v-if="user">
                            <div 
                                class="nav-menu-item" 
                                v-if="!user.hasCreatedOrganizers">
                                <a href="/events/create">
                                    <div>Submit Your Experience (Free)</div>
                                </a>
                            </div>
                            <div 
                                class="nav-menu-item" 
                                v-if="user.hasCreatedOrganizers">
                                <a href="/create/events/edit">
                                    <div>Your Events</div>
                                </a>
                            </div>
                            <div class="nav-menu-item">
                                <ProfileButton 
                                    :mobile="mobile" 
                                    :user="user" />
                            </div>
                        </template>
                        <template v-else>
                            <div class="nav-menu-item">
                                <a href="/register?create=true">
                                    <div>Submit Your Experience (Free)</div>
                                </a>
                            </div>
                            <div class="nav-menu-item">
                                <ProfileButton 
                                    :mobile="mobile" 
                                    :user="user" />
                            </div>
                        </template>
                    </div>
                </template>
            </div>
        </nav>
        <template v-if="searchOpen && this.navtype !== 'homepage'">
            <div class="modal-backdrop" />
        </template>
    </div>
</template>

<script>
    import ProfileButton from './profile-button.vue'
    import SearchBar from '../../../components/search-bars/search-dropdown.vue'

    export default {

        props:['user', 'passedclass', 'navtype'],

        components: { ProfileButton, SearchBar },

        computed: {
            hideMenuNav() {
                return this.navtype != 'create'
            },
            hideSearch() {
                return this.navtype != 'create' && this.navtype !='onlinesearch' && this.navtype !='searchpage'
            },
        },

        data() {
            return {
                mobile: false,
                page: this.navtype,
                searchOpen: this.navtype == 'homepage' ? 'l' : false,
                navClass:this.passedclass
            };
        },

        methods: {
            openSearch() {
                this.navClass.open = true;
                this.searchOpen = 'l'
            },
            onClickOutside(event) {
                let arr =  this.$refs.navdrop;
                if (!arr || arr.contains(event.target)) return;
                this.searchOpen = false;
                this.navClass.open = false;
            },
            handleScroll () {
                this.searchOpen = false;
                this.navClass.open = false;
            },
        },

        mounted() {
            this.$store.commit('adduser', this.user);
            document.addEventListener("click", this.onClickOutside);
            window.addEventListener('scroll', this.handleScroll);
        },

        beforeUnmount() {
            document.removeEventListener("click", this.onClickOutside);
            window.removeEventListener('scroll', this.handleScroll);
        }
    }
</script>



