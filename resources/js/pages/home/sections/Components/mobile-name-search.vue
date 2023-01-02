<template>
    <div>
        <!-- content -->
        <div class="relative mb-96 mt-16">
            <div class="px-8">
                or search by name
            </div>
            <div 
                v-if="hasName"
                class="m-8 p-8 rounded-3xl bg-white shadow-custom-1">
                <div 
                    @click="toggleName"
                    class="flex justify-between cursor-pointer">
                    <div class="text-2xl">
                        Search by Name
                    </div>
                </div>
            </div>
            <div 
                v-else
                class="m-8 p-8 rounded-3xl bg-white shadow-custom-1">
                <div 
                    @click="toggleName"
                    class="font-semibold text-4xl flex justify-between items-center">
                    <span>Event or Organization</span>
                    <svg class="w-8 h-8">
                        <use :xlink:href="`/storage/website-files/icons.svg#ri-arrow-up-s-line`" />
                    </svg>
                </div>
                <!-- search -->
                <div class="flex justify-between w-full items-center border border-neutral-300 rounded-2xl mt-4">
                    <button class="border-none w-20 h-20 items-center justify-center flex py-4">
                        <div class="fill-default-red">
                            <svg class="w-8 h-8">
                                <use :xlink:href="`/storage/website-files/icons.svg#ri-search-line`" />
                            </svg>
                        </div>
                    </button>
                    <div class="relative w-full border-none hide-dropdown">
                        <input 
                            ref="name"
                            class="relative border-none focus:border-none p-8 pl-6 w-full bg-transparent"
                            v-model="searchInput"
                            placeholder="Search by Name"
                            @input="searchName"
                            @focus="dropdown=true"
                            autocomplete="false"
                            onfocus="value = ''" 
                            type="text">
                    </div>
                </div>
                <!-- list -->
            </div>
            <div 
                v-if="dropdown"
                ref="name1"
                class="absolute w-full z-50 top-[17rem]">
                <ul class="bg-white mx-8 px-8 pb-8 rounded-b-2xl min-h-[16rem]">
                    <li
                        @click="selectEvent(item)"
                        class="flex items-center border-none cursor-pointer mt-4"
                        :key="item.model.id + item.index_name"
                        v-for="item in searchOptions">
                        <span class="border rounded-xl mr-8 flex h-16 w-16 items-center justify-center overflow-hidden">
                            <picture
                                v-if="item.model.thumbImagePath">       
                                <source 
                                    type="image/webp" 
                                    :srcset="`${envImageUrl}${item.model.thumbImagePath}`"> 
                                <img :src="`${envImageUrl}${item.model.thumbImagePath.slice(0, -4)}jpg`">
                            </picture>
                            <p v-else>o</p>
                        </span>
                        <p>{{item.model.name}}</p>
                    </li>
                </ul>
            </div>
        </div>
    </div>
</template>

<script>
    
    export default {

        props: ['value'],

        components: {  },

        computed: {
            inputVal: {
                get() { return this.value },
                set(val) { this.$emit('input', val) }
            },
        },

        data() {
            return {
                searchInput: '',
                searchOptions: [],
                hasName: true,
                dropdown: false,
                envImageUrl: process.env.MIX_IMAGE_URL,
            }
        },

        methods: {
            async searchName() {
                await axios.get('/api/search/navbar/eventorganizer', { params: { keywords: this.searchInput } })
                .then( res => {
                    this.searchOptions = res.data;
                })
            },
            selectEvent(item) {
                window.location.href = `${item.index_name==='events' ? 'events' : 'organizer'}/${item.model.slug}`;
            },
            toggleName() {
                this.hasName=!this.hasName
            },
            onClickOutside(event) {
                let arr =  this.$refs.list;
                if (!arr || arr.contains(event.target)) return;
                this.dropdown = false;
            },
        },
        mounted() {
            document.addEventListener("click", this.onClickOutside);
        },

        beforeUnmount() {
            document.removeEventListener("click", this.onClickOutside);
        },

}
    
</script>