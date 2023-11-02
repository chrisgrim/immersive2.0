<template>
    <nav 
        ref="navdrop"
        :class="[ 
            search.searchType ? 'open' : '',
            onClass.nav
        ]"
        class="nav w-full m-auto h-32 z-[1001]">
        <div 
            :class="[ 
                search.searchType ? 'open' : '',
                onClass.navBar
            ]"
            class="nav_bar m-auto relative h-full items-center grid gap-0 grid-cols-5 md:px-12 lg:px-32">
            <!--  -->
            <!-- Home Button Section -->
            <div class="inline-block relative leading-none col-span-1">
                <a 
                    aria-label="Home Button"
                    href="/">
                    <svg 
                        :class="onClass.brand"
                        class="w-10 h-10 inline-block" 
                        viewBox="0 0 256 256">
                        <path 
                            id="EI"
                            d="M149.256,186.943H80.406V144.275h63.908V104.057H80.406V67.443h66.983V27.369H34.506V227.161h114.75V186.943ZM226.121,27.369h-45.9V227.161h45.9V27.369Z" />
                    </svg>
                </a>
            </div>

            <!-- Search Section -->
            <div class="w-full inline-block relative min-w-[30rem] col-span-3">
                <!--  -->
                <!-- If user clicks search bar -->
                <template v-if="hassearch">
                    <template v-if="search.searchType">
                        <div class="w-full flex justify-center items-center">
                            <p class="text-xl font-semibold">Search By:</p>
                            <button 
                                @click="search.searchType='l'"
                                :class="{ active : search.searchType==='l'}"
                                class="tab relative border-none p-4 text-1xl hover:text-black hover:font-medium">
                                Location
                            </button>
                            <button 
                                @click="search.searchType='t'"
                                :class="{ active : search.searchType==='t'}"
                                class="tab relative border-none p-4 text-1xl hover:text-black hover:font-medium">
                                Genre
                            </button>
                            <button 
                                @click="search.searchType='e'"
                                :class="{ active : search.searchType==='e'}"
                                class="tab relative border-none p-4 text-1xl hover:text-black hover:font-medium">
                                Name
                            </button>
                            <div class="bg-black w-[.15rem] h-8"/>
                            <a 
                                class="border-none text-1xl p-4 hover:text-black hover:font-medium hover:border-b-black"
                                href="/index/search?&searchType=atHome">
                                At Home 
                            </a>
                            <a 
                                class="border-none text-1xl p-4 hover:text-black hover:font-medium"
                                href="/index/search?&category=23&searchType=atHome">
                                VR 
                            </a>
                        </div>
                        <SearchBar v-model="search" />
                    </template>
                    <template v-else>
                        <div
                            class="w-full absolute top-0 bottom-0 z-[500] cursor-pointer" 
                            @click="openSearch" />
                        <div class="p-4 border rounded-full flex justify-between items-center shadow-custom-3 w-3/4 m-auto">
                            <p class="text-gray-300 ml-4">{{ city ? city : 'Start your search'}}</p>
                            <div class="w-12 h-12 flex items-center justify-center rounded-full bg-default-red">
                                <svg class="w-8 h-8 fill-white">
                                    <use :xlink:href="`/storage/website-files/icons.svg#ri-search-line`" />
                                </svg>
                            </div>
                        </div>
                    </template>
                </template>
            </div>

            <!-- Menu Section -->
            <NavMenu 
                :on-class="onClass"
                :user="user" />
        </div>
        <div 
            v-if="search.dropdown"
            class="fixed top-0 left-0 w-full h-full bg-[#00000026] z-[-10]" />
    </nav>
</template>

<script>
    import SearchBar from './Nav-Desktop/nav-desktop-search.vue'
    import NavMenu from './Nav-Desktop/nav-desktop-menu.vue'

    export default {

        props:['user', 'hassearch', 'onClass'],

        components: { SearchBar, NavMenu },

        data() {
            return {
                search: {
                    searchType: null,
                    dropdown: false,
                },
                city: new URL(window.location.href).searchParams.get("city"),
            };
        },

        methods: {
            openSearch() {
                this.search.searchType = 'l'
            },
            onClickOutside(event) {
                let arr =  this.$refs.navdrop;
                if (!arr || arr.contains(event.target)) return;
                this.search.searchType = null;
            },
            handleScroll () {
                this.search.searchType = null;
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



