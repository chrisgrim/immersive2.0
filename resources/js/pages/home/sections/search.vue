<template>
    <div 
        class="relative bg-black mt-[-8rem] h-[32rem] flex flex-col items-center z-50">
        <Transition>
            <div 
                v-if="detailedSearch"
                class="w-full absolute top-[16rem] flex flex-col justify-center items-center">
                <div 
                    @click="onToggle"
                    class="rounded-full bg-white w-11/12 md:w-[85rem] flex flex-row p-4 justify-between items-center shadow-custom-1 h-28">
                    <p class="font-bold text-xl pl-4">Where</p>
                    <div class="w-12 h-12 flex items-center justify-center rounded-full bg-default-red">
                        <svg class="w-8 h-8 fill-white">
                            <use :xlink:href="`/storage/website-files/icons.svg#ri-search-line`" />
                        </svg>
                    </div>
                </div>
                <div class="absolute bg-white h-[40rem] w-11/12 md:w-[85rem] top-[9rem] rounded-[5rem] shadow-custom-1">
                    
                </div>
            </div>
        </Transition>
        <div v-if="!detailedSearch">
            <div class="w-full flex flex-col items-center w-[60rem] mb-24">
                <h1 class="text-white text-6xl mt-8 mb-6">Everything. Immersive. </h1>
                <p class="text-white">Discover immersive events from around the world and online. Discover immersive events from around the world and online.Discover immersive events from around the world and online</p>
            </div>
            <div class="w-full flex justify-center mb-16">
                <div 
                    @click="onToggle"
                    class="rounded-full bg-white w-[30rem] flex flex-row p-4 justify-between items-center">
                    <p class="font-bold text-xl pl-4">Start your search</p>
                    <div class="w-12 h-12 flex items-center justify-center rounded-full bg-default-red">
                        <svg class="w-8 h-8 fill-white">
                            <use :xlink:href="`/storage/website-files/icons.svg#ri-search-line`" />
                        </svg>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
    export default {
        data() {
            return {
                detailedSearch: false
            };
        },

        methods: {
            onToggle() {
                this.detailedSearch =! this.detailedSearch
            },
            onClickOutside(event) {
                let arr =  this.$refs.searchdrop;
                if (!arr || arr.contains(event.target)) return;
                this.detailedSearch = false;
            },
            handleScroll () {
                this.detailedSearch = false;
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