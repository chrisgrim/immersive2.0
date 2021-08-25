<template>
    <section>
        <div class="es__about element">
            <a :href="`/index/search-all?category=${event.category.id}`">
                <span class="es__title--category"> {{ event.category.name }} </span>
            </a>
            <span class="es__title--name"><h1> {{ event.name }} </h1></span>
            <span class="es__title--tag"><i> {{ event.tag_line }} </i></span>
            <div class="es__title--rating">
                <a 
                    href="#reviews" 
                    v-if="false">
                    <template v-if="event.ratingAmount">
                        <span class="es__star active"><IconSvg type="star" /></span><span style="font-weight:700;font-size:1.5rem"> {{ event.averageRating }} </span> <span>({{ event.ratingAmount }})</span>
                    </template>
                    <template v-else>
                        <span class="es__star"><IconSvg type="star" /></span><span style="font-weight:700;font-size:1.5rem"> (No Ratings) </span>
                    </template>
                </a>
                <template v-if="event.hasLocation">
                    <a href="#location">
                        <template v-if="event.location.city">
                            <p> {{ event.location.city }}</p><p v-if="event.location.region">,</p>
                        </template>
                        <template v-if="event.location.region">
                            <p> {{ event.location.region }} </p>
                        </template>
                    </a>
                </template>
                <template v-else>
                    <p>Available anywhere</p>
                </template>
            </div>
           
            <template v-if="event.staffpick && !isMobile">
                <a href="#staffpick">
                    <div class="es__staffpick--award">
                        <IconSvg type="award" />
                    </div>
                </a>
            </template>
        </div>

        <div class="es__description element">
            <h2>Experience hosted by 
                <a 
                    target="_blank" 
                    :href="`/organizer/${event.organizer.slug}`">
                    {{ event.organizer.name }}
                </a>
            </h2>
            <a 
                v-if="event.organizer.thumbImagePath"
                href="#organizer">
                <picture>
                    <source 
                        type="image/webp" 
                        :srcset="`/storage/${event.organizer.thumbImagePath}`"> 
                    <img 
                        :src="`/storage/${event.organizer.thumbImagePath.slice(0, -4)}jpg`" 
                        :alt="`Logo of ${event.organizer.name}`">
                </picture>
            </a>
            <ShowMore 
                :text="event.description"
                :limit="100" />
        </div>

        <template v-if="event.video">
            <div class="video">
                <VideoPlayer
                    :alt="`${event.name} Immersive Event`"
                    :src="`https://www.youtube.com/embed/${event.video}`" />
            </div>
        </template>
    </section>
</template>

<script>
    import ShowMore  from '../components/show-more.vue'
    import IconSvg from '../../../components/Svg-icon'
    import VideoPlayer  from './VideoPlayer.vue'
    export default {

        props: [ 'event'],

        components: { ShowMore, IconSvg, VideoPlayer },

        data() {
            return {
                isMobile: window.innerWidth < 768 ? true : false,
            }
        },

    }
</script>