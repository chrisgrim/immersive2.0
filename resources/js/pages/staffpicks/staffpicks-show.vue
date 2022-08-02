<template>
    <div class="staffpicks">
        <div class="section-a">
            <div class="section-a__wrapper">
                <h2> Current Staff Picks </h2>
                <div class="album-a">
                    <a 
                        v-for="(card) in staffpicks" 
                        :key="card.id"
                        class="row">
                        <div class="card">
                            <div class="col">
                                <a 
                                    :href="`/events/${card.event.slug}`" 
                                    class="card-url" />
                                <div class="card-img">                            
                                    <picture>
                                        <source 
                                            type="image/webp" 
                                            :srcset="`${envImageUrl}${card.event.thumbImagePath}`"> 
                                        <img 
                                            loading="lazy" 
                                            :src="`${envImageUrl}${card.event.thumbImagePath.slice(0, -4)}jpg`" 
                                            :alt="`${card.event.name} Immersive Event`">
                                    </picture>
                                </div>
                            </div>
                            <div class="col">
                                <div class="card-body">
                                    <template v-if="card.event.name">
                                        <div class="name">
                                            <p>{{ card.event.name }}</p>
                                        </div>
                                    </template>
                                    <template v-if="card.event.blurb">
                                        <div class="blurb">
                                            <p>{{ card.event.tag_line }}</p>
                                        </div>
                                    </template>
                                    <div class="price">
                                        {{ fixedprice(card.event) }}
                                    </div>
                                    <template v-if="card.comments">
                                        <div class="summary">
                                            <p>"{{ card.comments }}"</p>
                                        </div>
                                    </template>
                                    <template v-if="card.user">
                                        <div class="user">
                                            <p>{{card.user.name}}</p>
                                            <picture v-if="card.user.thumbImagePath">
                                                <source 
                                                    type="image/webp" 
                                                    :srcset="`${envImageUrl}${card.user.thumbImagePath}`"> 
                                                <img 
                                                    :src="`${envImageUrl}${card.user.thumbImagePath.slice(0, -4)}jpg`" 
                                                    :alt="`${card.user.name} Immersive Event`">
                                            </picture>
                                        </div>
                                    </template>
                                </div>
                            </div>
                        </div>
                    </a>
                </div>
            </div>
        </div>
    </div>
</template>

<script>

    export default {

        props:['staffpicks', 'week'],

        computed: {
            fixedprice() {
                return event => event.price_range.replace(/\d+(\.\d{1,2})?/g, dec => parseInt(dec));
            },
        },

        data() {
            return {
                value: '',
                list: [],
                price: '',
                eventName: '',
                envImageUrl: process.env.MIX_IMAGE_URL,
            }
        },

        methods: {

        },

        mounted() {

        }

    };
</script>