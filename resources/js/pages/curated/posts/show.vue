<template>
    <div class="post-show">
        <div class="m-auto w-full md:px-12 md:py-8 lg:py-0 lg:px-32 max-w-screen-xl">
            <template v-if="mobile">
                <nav>
                    <button 
                        @click="onBack"
                        class="absolute flex p-1 top-8 left-8 z-10 rounded-full custom-shadow-1 border bg-white">
                        <svg class="w-12 h-12">
                            <use :xlink:href="`/storage/website-files/icons.svg#ri-arrow-left-s-line`" />
                        </svg>
                    </button>
                </nav>
            </template>
            <template v-else>
                <div class="py-12">
                    <p class="text-1xl">
                        <a 
                            class="underline" 
                            :href="`/`">Everything Immersive</a> > 
                        <a 
                            class="underline" 
                            :href=" curator ? `/communities/${community.slug}/edit` : `/communities/${community.slug}`">{{community.name}}</a> > {{post.name}}
                    </p>
                </div>
            </template>
            <template v-if="headerImage && value.type==='s'">
                <div class="relative w-full h-96 w-full overflow-hidden md:h-[40rem] md:rounded-2xl">
                    <picture>
                        <source 
                            type="image/webp" 
                            :srcset="`${envImageUrl}${headerImage}`"> 
                        <img 
                            loading="lazy"
                            class="object-cover w-full h-full" 
                            :src="`${envImageUrl}${headerImage.slice(0, -4)}`" 
                            :alt="`${post.name} Post`">
                    </picture>
                </div>
            </template>
        </div>
        <div 
            :class="{ 'mt-24' : !headerImage}"
            class="m-auto w-full px-8 md:px-12 md:py-8 lg:py-0 lg:px-32 max-w-screen-xl">
            <div class="flex flex-col md:flex-row">
                <div class="w-full inline-block md:w-4/12 md:py-8 md:pr-8">
                    <div class="sticky top-16">
                        <div class="mt-8">
                            <h2>{{ post.name }}</h2>
                            <div 
                                class="mt-4 relative"
                                v-html="post.blurb" />
                            <p class="text-xl mb-4">by {{ post.user.name }}</p>
                            <template v-if="curator">
                                <a 
                                    class="bg-black px-4 py-2 rounded-full text-white border hover:bg-white hover:text-black"
                                    :href="`/communities/${community.slug}/${value.slug}/edit`">
                                    edit
                                </a>
                            </template>
                        </div>
                    </div>
                </div>
                <div class="post-list inline-block w-full md:w-8/12">
                    <div class="w-full my-12 mx-auto max-w-7xl">
                        <div 
                            v-for="card in post.cards"
                            :key="card.id"
                            class="mt-12 relative">
                            <Card 
                                :mobile="mobile"
                                :card="card" />
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
    import Card  from './Cards/card.vue'
    export default {
        
        props: [ 'value', 'user', 'curator', 'community', 'mobile'],

        components: { Card },

        computed: {
            headerImage() {
                if (this.mobile) {
                    if (this.value.event_id) { return this.value.featured_event_image.thumbImagePath}
                    return this.value.thumbImagePath
                }
                if (this.value.event_id) { return this.value.featured_event_image.largeImagePath}
                return this.value.largeImagePath
            }
        },

        data() {
            return {
                post: this.value,
                envImageUrl: process.env.MIX_IMAGE_URL,
            }
        },

        methods: {
            onBack() {
                document.referrer == "" ? window.location.href = '/' : window.history.back()
            }
        },


    }
</script>