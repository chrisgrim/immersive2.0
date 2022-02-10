<template>
    <section 
        id="organizer" 
        class="py-12 px-8 md:py-16 md:px-0">  
        <a :href="`/organizer/${event.organizer.slug}`">
            <div class="flex mb-8">
                <div 
                    :style="image"
                    class="mr-4 w-20 h-20 rounded-full flex justify-center items-end border bg-no-repeat bg-cover overflow-hidden">
                    <p 
                        v-if="!event.organizer.thumbImagePath" 
                        class="text-5xl text-white font-extrabold m-auto"> {{ event.organizer.name.charAt(0) }} </p>
                </div>
                <div>
                    <h3> {{ event.organizer.name }} </h3>
                    <p class="text-2xl md:text-xl text-gray-500"> Hosting immersive events on EI since {{ cleanDate(event.organizer.created_at) }}</p>
                </div>
            </div>
        </a>
        <ShowMore 
            :text="event.organizer.description"
            :limit="100" />
    </section>
</template>

<script>
    import ShowMore  from '../../../components/ShowMore.vue'
    export default {

        props: [ 'event' ],

        components: { ShowMore },

        data() {
            return {
                image: this.event.organizer.thumbImagePath ? `background-image:url('/storage/${this.event.organizer.thumbImagePath.slice(0, -4)}jpg')` : `background:${this.event.organizer.hexColor}`
            }
        },

        methods: {
            cleanDate(data) {
                return this.$dayjs(data).format("YYYY");
            }
        },

    }
</script>