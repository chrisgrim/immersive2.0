<template>
    <section 
        id="organizer" 
        class="es__organizer element">  
        <a :href="`/organizer/${event.organizer.slug}`">
            <div class="es__organizer--details">
                <div 
                    :style="image"
                    class="es__organizer--image">
                    <template v-if="!event.organizer.thumbImagePath">
                        <p> {{ event.organizer.name.charAt(0) }} </p>
                    </template>
                </div>
                <div class="es__organizer--name subtext">
                    <h3> {{ event.organizer.name }} </h3>
                    <p> Hosting immersive events on EI since {{ cleanDate(event.organizer.created_at) }}</p>
                </div>
            </div>
        </a>
        <div 
            v-if="event.organizer.showRating.count"
            class="es__organizer--rating">
            <template v-if="event.organizer.showRating.count > 1">
                <p> {{ event.organizer.showRating.count }} Event reviews</p>
            </template>
            <template v-else-if="event.organizer.showRating.count === 1">
                <p> {{ event.organizer.showRating.count }} Event review</p>
            </template>
        </div>
        <ShowMore 
            :text="event.organizer.description"
            :limit="100" />
    </section>
</template>

<script>
    import ShowMore  from '../components/show-more.vue'
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