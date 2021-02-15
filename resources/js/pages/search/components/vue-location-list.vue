<template>
    <div ref="list">
        <a 
            v-for="(event) in items" 
            :key="event.id"
            :href="`/events/${event.slug}`" 
            class="event__horizontal-card">
            <div class="event__horizontal-card--element">
                <div class="event__horizontal-card--image">
                    <div class="event__horizontal-card--lock" />                              
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
                <div class="event__horizontal-card--content">
                    <div>
                        <div class="event__horizontal-card--category">
                            {{ event.category ? event.category.name : '' }}
                        </div>
                        <div class="event__horizontal-card--heart">
                            <favorite 
                                inputclass="heart visible" 
                                :event="event" />
                        </div>
                    </div>
                    <div class="event__horizontal-card--title">
                        {{ event.name }}
                    </div>
                    <div class="event__horizontal-card--tagline">
                        {{ event.tag_line }}
                    </div>
                    <ul class="event__horizontal-card--tags">
                        <li 
                            v-for="(itemTag, index) in eventTags(event)" 
                            :key="itemTag.id" >
                            {{ itemTag.name }}<span v-if="index != '2'">•</span>
                        </li>
                    </ul>
                    <div class="event__horizontal-card--price">
                        {{ fixedprice(event) }}
                    </div>
                </div>
            </div>
        </a>
    </div>
</template>

<script>
    export default {
        props: ['items'],

        computed: {
            eventTags() {
                return item => item.genres.slice(0, 3);
            },

            fixedprice() {
                return event => event.price_range.replace(/\d+(\.\d{1,2})?/g, dec => parseInt(dec));
            },
        },

        data() {
            return {
                isDisabled: false,
                width: '',
            }
        },

        methods: {
            findWidth() {
                if (this.$refs.list.clientWidth > 1000)  return this.width = '25%'
                if (this.$refs.list.clientWidth > 600)  return this.width = '33%'
                if (this.vertical) return this.width = '100%'
                return this.width = '90%';
            }
        },

        mounted() {
            this.findWidth();
        }
    };
</script>