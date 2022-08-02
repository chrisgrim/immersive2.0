<template>
    <div class="min-h-[10rem]">
        <div class="relative mt-20 mx-32">
            <div class="absolute right-0 top-[-1rem]">
                <div class="flex justify-end relative z-50">
                    <button 
                        @click="active=!active"
                        class="border-none w-20 h-20 flex p-0 rounded-full justify-center items-center hover:bg-slate-300" 
                        :class="{'bg-black hover:bg-slate-300': active}">
                        <svg 
                            :class="{'fill-white rotate-45': active}"
                            class="w-16 h-16">
                            <use :xlink:href="`/storage/website-files/icons.svg#ri-add-fill`" />
                        </svg>
                    </button>
                    <template v-if="active">
                        <div class="p-4 rounded-2xl shadow-custom-1 absolute right-0 bg-white top-[115%]">
                            <div class="mt-2 px-4 py-2">
                                <p class="text-lg">Create</p>
                            </div>
                            <a 
                                class="px-4 py-2 font-semibold text-3xl block rounded-xl hover:bg-gray-400 hover:text-white"
                                href="/communities/create">
                                Community
                            </a>
                        </div>
                    </template>
                </div>
            </div>
            <h2>My Communities</h2>
        </div>
        <div>
            <div
                v-for="(community) in communities"
                :key="community.id">
                <section class="max-w-screen-2xl relative h-full m-auto px-8 md:px-12 lg:px-32">
                    <div class="my-8 md:mt-16 md:mb-24">
                        <div class="w-full relative block overflow-hidden mb-8 rounded-xl md:flex md:h-[45rem]">
                            <div class="flex items-center justify-center p-8 bg-black md:justify-start md:w-2/5 md:px-24 md:py-0">
                                <div class="w-full">
                                    <h2 class="text-white text-6xl mb-4">{{ community.name }}</h2>
                                    <p class="text-white mb-16">{{ community.blurb }}</p>
                                    <template v-if="isPublic(community)">
                                        <a :href="`/communities/${community.slug}/edit`">
                                            <button class="bg-white border-none p-4"> Edit </button>
                                        </a>
                                        <a :href="`/communities/${community.slug}`">
                                            <button class="bg-white border-none p-4"> View </button>
                                        </a>
                                    </template>
                                    <template v-else>
                                        <div class="h-full mt-4">
                                            <p class="text-white">-- Community under review --</p>
                                        </div>
                                    </template>
                                </div>
                            </div>
                            <div class="relative inline-block bg-slate-400 md:w-3/5 md:h-[45rem] after:absolute after:left-0 after:top-0 after:inline-block after:h-full after:w-full after:bg-gradient-to-r from-black via-transparent to-transparent">
                                <picture>
                                    <source 
                                        type="image/webp" 
                                        :srcset="`${envImageUrl}${community.largeImagePath}?timestamp=${community.updated_at}`"> 
                                    <img 
                                        loading="lazy" 
                                        class=" object-cover align-bottom w-full h-full"
                                        :src="`${envImageUrl}${community.largeImagePath.slice(0, -4)}jpg?timestamp=${community.updated_at}`" 
                                        :alt="`${community.name} Community`">
                                </picture>
                            </div>
                        </div>
                    </div>
                </section>
            </div>
        </div>
    </div>
</template>

<script>
    
    export default {
        
        props: ['user', 'value' ],

        data() {
            return {
                communities: this.value,
                active: null,
                envImageUrl: process.env.MIX_IMAGE_URL,
            }
        },

        methods: {
            // async deleteCommunity(community) {
            //     await axios.delete(`/communities/${community.slug}`)
            //     .then( res => {
            //         console.log(res.data);
            //     })
            // },
            isPublic(community) {
                if (community.status === 'p') return true;
            },
        },


    }
</script>