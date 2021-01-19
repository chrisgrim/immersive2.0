<template>
    <div class="events">
        <div class="create-title">
            <h2>Organizer Approval</h2>
            <p>These are the organizers that need approval</p>
        </div>
        
        <section>
            <div class="padded-width">
                <div 
                    class="event-index__eventlist" 
                    ref="list">
                    <div class="event-index__eventlist--middle vertical">
                        <div 
                            :key="organizer.id"
                            v-for="(organizer) in organizers" 
                            class="eventlist__element" 
                            :style="`width:${width}`">
                            <div class="card">
                                <div 
                                    itemprop="itemListElement" 
                                    itemscope="" 
                                    itemtype="http://schema.org/ListItem">
                                    <meta 
                                        :content="organizer.name" 
                                        itemprop="name">
                                    <meta 
                                        :content="`https://www.everythingimmersive.com/events/${organizer.slug}`" 
                                        itemprop="url">
                                    <a 
                                        :href="`/admin/organizers/${organizer.slug}/finalize`" 
                                        class="card-url" />
                                    <div class="card-image__top">
                                        <div 
                                            class="card-image__middle" 
                                            v-if="organizer.thumbImagePath" 
                                            style="padding-top: 65%;">
                                            <div class="card-image">
                                                <picture>
                                                    <source 
                                                        type="image/webp" 
                                                        :srcset="`/storage/${organizer.thumbImagePath}`"> 
                                                    <img 
                                                        style="object-fit:cover" 
                                                        loading="lazy" 
                                                        class="card-image__img" 
                                                        :src="`/storage/${organizer.thumbImagePath.slice(0, -4)}jpg`" 
                                                        :alt="`${organizer.name} Immersive Event`">
                                                </picture>
                                            </div>
                                        </div>
                                        <div 
                                            class="card-image__middle empty" 
                                            style="padding-top: 64.3%;" 
                                            v-else>
                                            <div class="card-image" />
                                        </div>
                                    </div>
                                    <div class="card-content">
                                        <div class="card-title">
                                            <h3>
                                                {{ organizer.name }}
                                            </h3>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>
    </div>
</template>

<script>

    export default {

        props: ['organizers', 'user'],

        data() {
            return {
                width: '',
            }
        },

        methods: {

            divWidth() {
                if (this.$refs.list.clientWidth > 1000) {
                    return this.width = '25%'
                }
                if (this.$refs.list.clientWidth > 600) {
                    return this.width = '33.3333%'
                }
                return this.width = '41%';
            }
        },

        mounted() {
            this.divWidth();
        }
    };
</script>