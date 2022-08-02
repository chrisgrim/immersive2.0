<template>
    <section>
        <div class="border-b py-8 px-8 md:py-8 md:px-0 md:py-12">
            <h1 class="mb-4 leading-[3rem]"> {{ event.name }} </h1>
            <p class="mb-4 italic"> {{ event.tag_line }} </p>
            <a 
                v-if="event.hasLocation"
                class="flex underline text-xl font-semibold"
                href="#location">
                <span v-if="event.location.city"> {{ event.location.city }}</span>
                <span v-if="event.location.city && event.location.region">, &nbsp;</span>
                <span v-if="event.location.region"> {{ event.location.region }} </span>
            </a>
            <p v-else> Available anywhere </p>
        </div>

        <div class="py-12 px-8 md:py-16 md:px-0 border-b">
            <div class="flex gap-4 justify-between items-center mb-4">
                <h2 class="inline-block text-3xl md:text-4xl">Experience hosted by 
                    <a :href="`/organizer/${event.organizer.slug}`">
                        {{ event.organizer.name }}
                    </a>
                </h2>
                <a 
                    v-if="event.organizer.thumbImagePath"
                    class="min-w-[5rem]" 
                    :href="`/organizer/${event.organizer.slug}`">
                    <picture>
                        <source 
                            type="image/webp" 
                            :srcset="`${envImageUrl}${event.organizer.thumbImagePath}`"> 
                        <img 
                            class="w-20 h-20 rounded-full float-right"
                            :src="`${envImageUrl}${event.organizer.thumbImagePath.slice(0, -4)}jpg`" 
                            :alt="`Logo of ${event.organizer.name}`">
                    </picture>
                </a>
            </div>
            <ShowMore 
                :text="event.description"
                :limit="100" />
        </div>

        <template v-if="event.video">
            <div class="w-full p-8 md:py-16 md:px-0">
                <VideoPlayer
                    :alt="`${event.name} Immersive Event`"
                    :src="`https://www.youtube.com/embed/${event.video}`" />
            </div>
        </template>
    </section>
</template>

<script>
    import ShowMore  from '../../../components/ShowMore.vue'
    import VideoPlayer  from './VideoPlayer.vue'
    export default {

        props: [ 'event'],

        components: { ShowMore, VideoPlayer },

        data() {
            return {
                envImageUrl: process.env.MIX_IMAGE_URL,
            };
        },

    }
</script>