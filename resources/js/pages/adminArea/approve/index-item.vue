<template>
    <div 
        :class="[col, align]"
        class="album" 
        ref="list">
        <div 
            class="row" 
            v-if="events && events.length">
            <div
                v-for="(event) in events"
                :key="event.id"
                class="col" 
                :class="{'is-past': isShowing(event)}">
                <div 
                    :class="{ 'dis': isDisabled, black : color=='black' }" 
                    class="card">
                    <a 
                        :href="url(event)" 
                        class="card-url" />
                    <div 
                        style="padding-top: 65%;"
                        class="card-img"> 
                        <picture>
                            <source 
                                type="image/webp" 
                                :srcset="`/storage/${event.thumbImagePath}`"> 
                            <img 
                                style="object-fit:cover" 
                                loading="lazy" 
                                :src="`/storage/${event.thumbImagePath.slice(0, -4)}jpg`" 
                                :alt="`${event.name} Immersive Event`">
                        </picture>
                    </div>
                    <div class="card-body">
                        <div class="name">
                            <p :class="{ black : color=='black' }">
                                {{ event.name }}
                            </p>
                        </div>
                        <template v-if="event.organizer">
                            <div class="org">
                                <p :class="{ black : color=='black' }">
                                    {{ event.organizer.name }}
                                </p>
                            </div>
                        </template>
                        <div class="price">
                            <p :class="{ black : color=='black' }">
                                {{ event.price_range }} <span v-if="isShowing(event)"> (Event passed) </span>
                            </p>
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
</template>

<script>
    export default {
        props: {
            events: Array,
            loadurl: String,
            color: String,
            favorite: String,
            past: Boolean,
            col: {
              type: String,
              default: 'four'
            },
            align: {
              type: String,
              default: null
            },
        },

        computed: {
            url() {
                return event => this.loadurl == 'admin' ? `/admin/events/${event.slug}/finalize` : `/events/${event.slug}`
            },
            isShowing() {
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
        },



    };
</script>