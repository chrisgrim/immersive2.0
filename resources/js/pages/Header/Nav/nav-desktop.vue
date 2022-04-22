<template>
    <nav 
        ref="navdrop"
        :class="[ searchType ? 'open' : '', fullwidth ? 'fixed max-w-none px-8' : 'relative']"
        class="nav w-full m-auto h-32 z-[1001]">
        <div 
            :class="{ 'open' : searchType, 'md:px-0 lg:px-0' : fullwidth, 'max-w-screen-xl px-32' : narrow, }"
            class="nav_bar m-auto relative h-full grid gap-0 items-center grid-cols-3 md:px-12 lg:px-32">
            <!--  -->
            <!-- Home Button Section -->
            <div class="inline-block relative leading-none">
                <a 
                    aria-label="Home Button"
                    href="/">
                    <svg 
                        class="w-10 h-10 inline-block" 
                        viewBox="0 0 256 256">
                        <path 
                            id="EI"
                            d="M149.256,186.943H80.406V144.275h63.908V104.057H80.406V67.443h66.983V27.369H34.506V227.161h114.75V186.943ZM226.121,27.369h-45.9V227.161h45.9V27.369Z" />
                    </svg>
                </a>
            </div>

            <!-- Search Section -->
            <div class="w-full inline-block relative min-w-[22rem]">
                <!--  -->
                <!-- If user clicks search bar -->
                <template v-if="searchType">
                    <div class="w-full flex justify-center items-center">
                        <button 
                            @click="searchType='l'"
                            :class="{ active : searchType==='l'}"
                            class="tab relative border-none p-4 text-1xl hover:text-black hover:font-medium">
                            Locations
                        </button>
                        <button 
                            @click="searchType='t'"
                            :class="{ active : searchType==='t'}"
                            class="tab relative border-none p-4 text-1xl hover:text-black hover:font-medium">
                            Categories/Tags
                        </button>
                        <button 
                            @click="searchType='e'"
                            :class="{ active : searchType==='e'}"
                            class="tab relative border-none p-4 text-1xl hover:text-black hover:font-medium">
                            Listings
                        </button>
                        <a 
                            class="border-none text-1xl p-4 hover:text-black hover:font-medium"
                            href="/index/search-online?&category=6,12,21,18,3,10,19">
                            Remote 
                        </a>
                        <a 
                            class="border-none text-1xl p-4 hover:text-black hover:font-medium"
                            href="/index/search-online?&category=23">
                            VR 
                        </a>
                    </div>
                    <SearchBar :searchType="searchType" />
                </template>
                <template v-else>
                    <div
                        class="w-full absolute top-0 bottom-0 z-[500] cursor-pointer" 
                        @click="openSearch" />
                    <div class="p-4 border rounded-full flex justify-between items-center shadow-custom-3">
                        <p class="text-gray-300">{{ city ? city : 'Start your search'}}</p>
                        <div class="w-12 h-12 flex items-center justify-center rounded-full bg-default-red">
                            <svg class="w-8 h-8 fill-white">
                                <use :xlink:href="`/storage/website-files/icons.svg#ri-search-line`" />
                            </svg>
                        </div>
                    </div>
                </template>
            </div>

            <!-- Menu Section -->
            <NavMenu :user="user" />
        </div>
    </nav>
</template>

<script>
    import SearchBar from './Nav-Desktop/nav-desktop-search.vue'
    import NavMenu from './Nav-Desktop/nav-desktop-menu.vue'

    export default {

        props:['user', 'fullwidth', 'narrow'],

        components: { SearchBar, NavMenu },

        data() {
            return {
                searchType: null,
                city: new URL(window.location.href).searchParams.get("city")
            };
        },

        methods: {
            openSearch() {
                this.searchType = 'l'
            },
            onClickOutside(event) {
                let arr =  this.$refs.navdrop;
                if (!arr || arr.contains(event.target)) return;
                this.searchType = null;
            },
            handleScroll () {
                this.searchType = null;
            },
        },

        mounted() {
            document.addEventListener("click", this.onClickOutside);
            window.addEventListener('scroll', this.handleScroll);
        },

        beforeUnmount() {
            document.removeEventListener("click", this.onClickOutside);
            window.removeEventListener('scroll', this.handleScroll);
        }
    }
</script>



