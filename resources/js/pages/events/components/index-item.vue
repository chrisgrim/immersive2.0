<template>
    <div 
        class="event-index__eventlist" 
        ref="list">
        <div 
            class="event-index__eventlist--middle vertical" 
            v-if="events && events.length">
            <div
                v-for="(event) in events"
                :key="event.id"
                class="eventlist__element" 
                :class="{'is-past': isShowing(event)}"
                :style="`width:${width}`">
                <div 
                    :class="{ 'dis': isDisabled, black : color=='black' }" 
                    class="card">
                    <div 
                        itemprop="itemListElement" 
                        itemscope="" 
                        itemtype="http://schema.org/ListItem">
                        <meta 
                            :content="event.name" 
                            itemprop="name">
                        <meta 
                            :content="`https://www.everythingimmersive.com/events/${event.slug}`" 
                            itemprop="url">
                        <a 
                            :href="url(event)" 
                            class="card-url" />
                        <div class="card-image__top">
                            <div 
                                class="card-image__middle" 
                                style="padding-top: 65%;">
                                <div class="card-image">
                                    <picture>
                                        <source 
                                            type="image/webp" 
                                            :srcset="`/storage/${event.thumbImagePath}`"> 
                                        <img 
                                            style="object-fit:cover" 
                                            loading="lazy" 
                                            class="card-image__img" 
                                            :src="`/storage/${event.thumbImagePath.slice(0, -4)}jpg`" 
                                            :alt="`${event.name} Immersive Event`">
                                    </picture>
                                </div>
                            </div>
                        </div>
                        <div class="card-content">
                            <div class="card-title">
                                <h3 :class="{ black : color=='black' }">
                                    {{ event.name }}
                                </h3>
                            </div>
                            <template v-if="event.organizer">
                                <div class="card-organizer">
                                    <h3 :class="{ black : color=='black' }">
                                        {{ event.organizer.name }}
                                    </h3>
                                </div>
                            </template>
                            <div class="card-price">
                                <h4 :class="{ black : color=='black' }">
                                    {{ event.price_range }} <span v-if="isShowing(event)"> (Event passed) </span>
                                </h4>
                            </div>
                        </div>
                        <favorite 
                            v-if="canFavorite" 
                            :inputclass="showEventClass" 
                            :event="event" />
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
    export default {
        props:['events', 'loadurl', 'color', 'favorite', 'past'],

        computed: {
            url() {
                return event => this.loadurl == 'admin' ? `/admin/events/${event.slug}/finalize` : `/events/${event.slug}`
            },
            isShowing(event) {
                return event => this.past && !event.isShowing
            }
        },

        data() {
            return {
                showEventClass: 'heart',
                isDisabled: false,
                canFavorite: this.favorite == 'hidden' ? false : true,
                width: '',
            }
        },

        methods: {
            divWidth() {
                if (this.$refs.list.clientWidth > 1000) {
                    return this.width = '25%'
                }
                if (this.$refs.list.clientWidth > 600) {
                    return this.width = '33%'
                }
                return this.width = '90%';
            }
        },

        mounted() {
            this.divWidth();
        }


    };
</script>