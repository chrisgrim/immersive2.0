<template>
    <div class="events">
        <div class="create-title">
            <h2>Community Approval</h2>
            <p>These are the communities that need approval</p>
        </div>
        
        <section>
            <div class="padded-width">
                <div 
                    class="event-index__eventlist" 
                    ref="list">
                    <div class="event-index__eventlist--middle vertical">
                        <div
                            v-for="(card) in value.data"
                            :key="card.id"
                            class="eventlist__element" 
                            :style="`width:25%`">
                            <div class="card">
                                <div>
                                    <a 
                                        :href="`/admin/communities/${card.slug}/show`" 
                                        class="card-url" />
                                    <div class="card-image__top">
                                        <div 
                                            class="card-image__middle" 
                                            style="padding-top: 65%;">
                                            <div class="card-image">
                                                <picture>
                                                    <source 
                                                        type="image/webp" 
                                                        :srcset="`/storage/${card.thumbImagePath}`"> 
                                                    <img 
                                                        style="object-fit:cover" 
                                                        loading="lazy" 
                                                        class="card-image__img" 
                                                        :src="`/storage/${card.thumbImagePath.slice(0, -4)}jpg`">
                                                </picture>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="card-content">
                                        <div class="card-title">
                                            <h3>
                                                {{ card.name }}
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

        props:[ 'value' ],

        data() {
            return {
                width: '',
            }
        },

        methods: {
            onLoad() {
                // axios.get(`/admin/events/finalize/fetch?timestamp=${new Date().getTime()}`)
                // .then( res => { this.eventsLoaded = res.data })
            },

        },

        created() {
            // this.onLoad()
        },

    };
</script>