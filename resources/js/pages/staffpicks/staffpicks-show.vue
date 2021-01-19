<template>
    <div class="event-index-container">
        <div class="event-index">
            <header class="padded staffpicks">
                <div class="header-title">
                    <h3>StaffPicks for the week of {{ week }}</h3>
                </div>
            </header>
            <section class="padded">
                <div class="staffpicks__banner--top">
                    <div 
                        v-for="(staffpick) in staffpicks"
                        :key="staffpick.id">
                        <a :href="`/events/${staffpick.event.slug}`">
                            <div class="staffpicks__selection--main show">
                                <div class="staffpicks__image">
                                    <picture>
                                        <source 
                                            type="image/webp" 
                                            :srcset="`/storage/${staffpick.event.thumbImagePath}`"> 
                                        <img 
                                            :src="`/storage/${staffpick.event.thumbImagePath.slice(0, -4)}jpg`" 
                                            :alt="`${staffpick.event.name} Immersive Event`">
                                    </picture>
                                </div>
                                <div class="staffpicks__content">
                                    <div class="staffpicks__title">
                                        <h4>{{ staffpick.event.name }}</h4>
                                        <p>{{ staffpick.event.organizer.name }}</p>
                                        <p>{{ staffpick.event.price_range }}</p>
                                    </div>
                                    <div class="staffpicks__summary">
                                        <p>" {{ staffpick.comments }} "</p>
                                    </div>
                                    <div class="staffpicks__user">
                                        <div class="staffpicks__user--name">
                                            <i><p>-{{ staffpick.user.name }}</p></i>
                                        </div>
                                        <picture>
                                            <source 
                                                type="image/webp" 
                                                :srcset="`/storage/${staffpick.user.thumbImagePath}`"> 
                                            <img 
                                                :src="`/storage/${staffpick.user.thumbImagePath.slice(0, -4)}jpg`" 
                                                :alt="`${staffpick.user.name} Immersive Event`">
                                        </picture>
                                    </div>
                                </div>
                            </div>
                        </a>
                    </div>
                </div>
            </section>
        </div>
    </div>
</template>

<script>

    export default {

        props:['staffpicks', 'week'],

        computed: {
        },

        data() {
            return {
                value: '',
                list: [],
                price: '',
                eventName: '',

            }
        },

        methods: {

        },

        mounted() {

        }

    };
</script>