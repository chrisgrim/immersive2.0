<template>
    <div 
        :class="{ 'vertical' : vertical }"
        class="album four" 
        ref="list">
        <template v-if="isReady">
            <div class="row">
                <div
                    v-for="(card) in items"
                    :key="card.id"
                    class="col" 
                    :class="{ 'dis': isDisabled, 'black' : color=='black' }"
                    :style="`width:${width}`">
                    <div class="card">
                        <a 
                            :href="url(card)" 
                            class="card-url" />
                        <div 
                            style="padding-top: 65%;"
                            class="card-img"> 
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
                        <div class="card-body">
                            <div class="name">
                                <p :class="{ black : color=='black' }">
                                    {{ card.name }}
                                </p>
                            </div>
                            <template v-if="card.tag_line">
                                <div class="blurb">
                                    <p>
                                        {{ card.tag_line }} 
                                    </p>
                                </div>
                            </template>
                            <template v-if="isMobile()">
                                <ul class="tags">
                                    <li 
                                        v-for="(itemTag, index) in eventTags(card)" 
                                        :key="itemTag.id">
                                        {{ itemTag.name }}<span v-if="index != '2'">•</span>
                                    </li>
                                </ul>
                            </template>
                            <div class="price">
                                <p :class="{ black : color=='black' }">
                                    {{ card.price_range }}
                                </p>
                            </div>
                        </div>
                        <favorite 
                            v-if="canFavorite" 
                            inputclass="heart" 
                            :event="card" />
                    </div>
                </div>
            </div>
        </template>
    </div>
</template>

<script>
    import mobile from '../../../mixins/mobile'
    export default {
        props: {
            items: {
                type: Array,
                default: ''
            },
            color: {
                type: String,
                default: ''
            },
            canFavorite: {
                type: Boolean,
                default: true
            },
            vertical: {
                type: Boolean,
                default: false
            },
        },

        mixins: [ mobile ],

        computed: {
            url() {
                return card => this.loadurl == 'admin' ? `/admin/events/${card.slug}/finalize` : `/events/${card.slug}`
            },

            eventTags() {
                return card => card.genres.slice(0, 3);
            },
        },

        data() {
            return {
                isDisabled: false,
                width: '',
                isReady: this.items && this.items.length,
            }
        },

        methods: {

        },

    };
</script>