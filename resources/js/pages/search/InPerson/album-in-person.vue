<template>
    <div>
        <div 
            v-for="(card) in items" 
            :key="card.id">
            <div class="flex border-b pb-4 mb-4">
                <div class="w-[29rem] relative">
                    <a 
                        :href="url(card)" 
                        class="absolute h-full w-full left-0 top-0 rounded-2xl z-20" />
                    <div class="rounded-2xl h-64 w-96 relative overflow-hidden">                            
                        <picture v-if="card.thumbImagePath">
                            <source 
                                type="image/webp" 
                                :srcset="`${$envImageUrl}${card.thumbImagePath}`"> 
                            <img 
                                loading="lazy" 
                                class="h-full w-full absolute left-0 right-0 top-0 bottom-0 object-cover"
                                :src="`${$envImageUrl}${card.thumbImagePath.slice(0, -4)}jpg`" 
                                :alt="`${card.name} Immersive Event`">
                        </picture>
                    </div>
                </div>
                <div class="relative m-4 w-full">
                    <div 
                        v-if="card.category"
                        class="category">
                        <a :href="`/index/search?category=${card.category.id}&searchType=allEvents`">
                            <button class="py-3 px-4 mb-4 rounded-full uppercase text-lg">
                                {{ card.category.name }}
                            </button>
                        </a>
                    </div>
                    <favorite 
                        :user="user"
                        :event="card" />
                    <a  
                        v-if="card.name"
                        :href="url(card)">
                        <p class="font-medium text-black">{{ card.name }}</p>
                    </a>
                    <template v-if="card.tag_line">
                        <div class="mb-4 block text-ellipsis line-clamp-2 w-full max-h-16 text-xl">
                            {{ card.tag_line }} 
                        </div>
                    </template>
                    <ul class="m-0 p-0 flex">
                        <li 
                            class="text-xl list-disc mr-4 ml-4 first:list-none first:ml-0" 
                            v-for="itemTag in eventTags(card)" 
                            :key="itemTag.id">
                            {{ itemTag.name }}
                        </li>
                    </ul>
                    <div class="absolute right-0 bottom-0 text-right font-extrabold">
                        {{ fixedprice(card) }}
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
    import Favorite  from './Components/event-favorite.vue'
    export default {
        props: ['items', 'user'],

        components: { Favorite },

        computed: {
            url() {
                return card => this.loadurl == 'admin' ? `/admin/events/${card.slug}/finalize` : `/events/${card.slug}`
            },
            eventTags() {
                return item => item.genres.slice(0, 3);
            },
            fixedprice() {
                return event => event.price_range.replace(/\d+(\.\d{1,2})?/g, dec => parseInt(dec));
            },
        },

        data() {
            return {
            };
        },

    };
</script>