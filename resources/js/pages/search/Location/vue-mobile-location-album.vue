<template>
    <div class="min-h-[64vh] relative shadow-custom-1 px-8 bg-white w-full rounded-t-5xl z-[1002] trans">
        <div class="flex justify-center items-center h-32">
            <p v-if="items.data && items.data.length" >{{ items.total }} events</p>
            <p v-else>There are no events in for this search.</p>
        </div>
        <div class="whitespace-nowrap overflow-y-hidden overflow-x-auto mt-8 gap-x-6 scrolling-touch md:w-full md:overflow-visible md:mt-12">
            <template v-if="isReady">
                <div class="scroll-smooth overflow-auto w-full flex snap-mandatory scroll-p-4 md:m-[-0.8rem] flex-wrap md:overflow-visible md:whitespace-normal">
                    <div
                        v-for="(card) in items.data"
                        :key="card.id"
                        class="col relative flex w-full snap-start snap-always flex-[1_0_calc(100%-6rem)] flex-initial md:w-4/12 md:px-4 xl:w-3/12">
                        <div class="flex flex-col w-full bg-white break-words relative overflow-hidden">
                            <a 
                                :href="`/events/${card.slug}`" 
                                class="block h-full absolute top-0 left-0 w-full rounded-2xl z-10" />
                            <div 
                                style="padding-top: 65%;"
                                class="overflow-hidden relative rounded-2xl"> 
                                <picture>
                                    <source 
                                        type="image/webp" 
                                        :srcset="`${envImageUrl}${card.thumbImagePath}`"> 
                                    <img
                                        v-if="card.thumbImagePath"
                                        class="h-full w-full inset-0 absolute object-cover"
                                        loading="lazy" 
                                        :src="`${envImageUrl}${card.thumbImagePath.slice(0, -4)}jpg`" 
                                        :alt="`${card.name} Immersive Event`">
                                </picture>
                            </div>
                            <div class="mb-8 mt-2">
                                <div class="mt-4 overflow-hidden text-ellipsis m-h-16">
                                    <p class="font-medium">
                                        {{ card.name }}
                                    </p>
                                </div>
                                <template v-if="card.tag_line">
                                    <div class="mt-4 overflow-hidden text-ellipsis m-h-16">
                                        <p>
                                            {{ card.tag_line }} 
                                        </p>
                                    </div>
                                </template>
                                <div class="mt-4 overflow-hidden text-ellipsis m-h-16">
                                    <p>
                                        {{ card.price_range }}
                                    </p>
                                </div>
                            </div>
                            <Favorite 
                                :mobile="mobile"
                                :user="user"
                                :event="card" />
                        </div>
                    </div>
                </div>
            </template>
        </div>
    </div>
</template>

<script>
    import Favorite  from './Components/event-favorite.vue'
    export default {
        props: ['items', 'user', 'mobile'],

        components: { Favorite },

        data() {
            return {
                isReady: this.items.data && this.items.data.length,
                envImageUrl: process.env.MIX_IMAGE_URL,
            }
        },

        methods: {
            eventTags(card) {
                return card.genres.slice(0, 3);
            },
        }


    };
</script>