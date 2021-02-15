<template>
    <div 
        :class="{ 'vertical' : vertical }"
        class="list" 
        ref="list">
        <template v-if="isReady">
            <div class="list__wrapper vertical">
                <div
                    v-for="(item) in items"
                    :key="item.id"
                    class="list__element" 
                    :class="{ 'dis': isDisabled, 'black' : color=='black' }"
                    :style="`width:${width}`">
                    <div 
                        itemprop="itemListElement" 
                        itemtype="http://schema.org/ListItem">
                        <meta 
                            :content="item.name" 
                            itemprop="name">
                        <meta 
                            :content="`https://www.everythingimmersive.com/events/${item.slug}`" 
                            itemprop="url">
                        <a 
                            :href="url(item)" 
                            class="list__url" />
                        <div class="image__top">
                            <div class="image__middle">
                                <div class="image">
                                    <picture>
                                        <source 
                                            type="image/webp" 
                                            :srcset="`/storage/${item.thumbImagePath}`"> 
                                        <img
                                            loading="lazy" 
                                            :src="`/storage/${item.thumbImagePath.slice(0, -4)}jpg`" 
                                            :alt="`${item.name} Immersive Event`">
                                    </picture>
                                </div>
                            </div>
                        </div>
                        <div class="content">
                            <div class="title">
                                <h3 :class="{ black : color=='black' }">
                                    {{ item.name }}
                                </h3>
                            </div>
                            <template v-if="item.tag_line">
                                <div class="tagline">
                                    <p>
                                        {{ item.tag_line }} 
                                    </p>
                                </div>
                            </template>
                            <template v-if="isMobile()">
                                <ul class="tags">
                                    <li 
                                        v-for="(itemTag, index) in eventTags(item)" 
                                        :key="itemTag.id">
                                        {{ itemTag.name }}<span v-if="index != '2'">•</span>
                                    </li>
                                </ul>
                            </template>
                            <div class="price">
                                <h4 :class="{ black : color=='black' }">
                                    {{ item.price_range }}
                                </h4>
                            </div>
                        </div>
                        <favorite 
                            v-if="canFavorite" 
                            inputclass="heart" 
                            :event="item" />
                    </div>
                </div>
            </div>
        </template>
    </div>
</template>

<script>
    import mobile from '../mixins/mobile'
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
                return item => this.loadurl == 'admin' ? `/admin/events/${item.slug}/finalize` : `/events/${item.slug}`
            },

            eventTags() {
                return item => item.genres.slice(0, 3);
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