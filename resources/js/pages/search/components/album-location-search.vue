<template>
    <div ref="list">
        <div class="album-a">
            <a 
                v-for="(card) in items" 
                :key="card.id"
                class="row">
                <div class="card">
                    <div class="col">
                        <a 
                            :href="url(card)" 
                            class="card-url" />
                        <div class="card-img">                            
                            <picture>
                                <source 
                                    type="image/webp" 
                                    :srcset="`/storage/${card.thumbImagePath}`"> 
                                <img 
                                    loading="lazy" 
                                    :src="`/storage/${card.thumbImagePath.slice(0, -4)}jpg`" 
                                    :alt="`${card.name} Immersive Event`">
                            </picture>
                        </div>
                    </div>
                    <div class="col">
                        <div class="card-body">
                            <template v-if="card.category">
                                <div class="category">
                                    <button class="btn btn-outline caps rounded">
                                        <p>{{ card.category.name }}</p>
                                    </button>
                                </div>
                            </template>
                            <div class="heart">
                                <favorite 
                                    inputclass="heart visible" 
                                    :event="card" />
                            </div>
                            <template v-if="card.name">
                                <div class="name">
                                    <p>{{ card.name }}</p>
                                </div>
                            </template>
                            <template v-if="card.blurb">
                                <div class="blurb">
                                    <p>{{ card.tag_line }}</p>
                                </div>
                            </template>
                            <ul class="tags">
                                <li 
                                    v-for="(itemTag, index) in eventTags(card)" 
                                    :key="itemTag.id" >
                                    {{ itemTag.name }}<span v-if="index != '2'">•</span>
                                </li>
                            </ul>
                            <div class="price">
                                {{ fixedprice(card) }}
                            </div>
                        </div>
                    </div>
                </div>
            </a>
        </div>
    </div>
</template>

<script>
    export default {
        props: ['items'],

        computed: {
            url() {
                return card => this.loadurl == 'admin' ? `/admin/events/${card.slug}/finalize` : `/events/${card.slug}`
            },
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

        },

    };
</script>