<template>
    <div class="my-8 md:mt-16 md:mb-24">
        <div class="flex items-center">
            <h2>Staff Picks</h2>
            <a href="/staffpicks/current">
                <button class="bg-black text-white rounded-lg ml-4 px-4 py-2 border-black hover:bg-white hover:text-black"> See all </button>
            </a>
        </div>
        <p>Our experienced staff is dedicated to finding you the best shows and events available.</p>
        <div class="whitespace-nowrap overflow-y-hidden overflow-x-auto mt-8 md:w-full md:m-auto md: whitespace-normal md:overflow-visible">
            <div 
                style="scroll-snap-type: x mandatory;" 
                class="flex w-full scroll-p-7 overflow-auto mt-8 scroll-smooth">
                <div 
                    v-for="(card) in staffpicks"
                    :key="card.id" 
                    class="relative w-full flex flex-[1_0_calc(100%-6rem)] snap-start snap-always px-4 first:ml-[-1rem] last:mr-[-1rem] md:flex-[0_1_33.3333333333%] md:w-4/12 lg:flex-[0_1_25%] lg:w-3/12">
                    <div class="flex w-full flex-col overflow-hidden relative">
                        <a 
                            :href="`/events/${card.event.slug}`" 
                            class="block h-full absolute w-full rounded-2xl top-0 left-0 z-50" />
                        <div class="w-full relative pt-[100%] relative rounded-xl overflow-hidden">
                            <picture v-if="card && card.event.thumbImagePath">
                                <source 
                                    type="image/webp" 
                                    :srcset="`${envImageUrl}${card.event.thumbImagePath}`"> 
                                <img 
                                    class="h-full w-full absolute object-cover align-bottom inset-0"
                                    loading="lazy" 
                                    :src="`${envImageUrl}${card.event.thumbImagePath.slice(0, -4)}jpg`" 
                                    :alt="`${card.event.name}`">
                            </picture>
                        </div>
                        <div class="mb-8 mt-2">
                            <div class="mt-4 overflow-hidden text-ellipsis max-h-16">
                                <p>{{ card.event.name }}</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script>

    export default {

        props:['staffpicks'],

        data() {
            return {
            };
        },
    };
</script>