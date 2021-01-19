<template>
    <div class="event-search">
        <div 
            class="event__filter" 
            v-if="!mobile">
            <EventFilter
                @onlineevents="updateOnlineEvents"
                :onlinepage="onlinePagination"
                :onlineevents="onlineevents" 
                :maxprice="maxprice" 
                :tags="tags" 
                :categories="categories" />
            <div class="event__results">
                <div class="event-search-list">
                    <div class="title">
                        <h3>Online Events</h3>
                    </div>
                    <template>
                        <vue-event-index :events="onlineEventList.data" />
                    </template>
                    <div>
                        <pagination 
                            :limit="2"
                            :list="onlineEventList"
                            @selectpage="selectOnlinePage" />
                    </div>
                </div>
            </div>
        </div>
        <template v-else>
            <div class="filterBar__relative">
                <MobileFilterBar />
            </div>
            <EventFilter
                @onlineevents="updateOnlineEvents"
                :onlinepage="onlinePagination"
                :onlineevents="onlineevents" 
                :maxprice="maxprice" 
                :tags="tags" 
                :categories="categories" />
            <div class="event-search-list">
                <div class="event__results">
                    <div class="title">
                        <h3>Online Events</h3>
                    </div>
                    <template v-if="onlineEventList.data">
                        <div>
                            <a 
                                :href="`/events/${event.slug}`" 
                                v-for="(event) in onlineEventList.data" 
                                :key="event.id"
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
                                                :key="itemTag.id">
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
                    <div>
                        <pagination 
                            :limit="1"
                            :list="onlineEventList"
                            @selectpage="selectOnlinePage" />
                    </div>
                </div>
            </div>
        </template>
    </div>
</template>

<script>
    import EventFilter  from './components/filter.vue'
    import Pagination  from './components/pagination.vue'
    import MobileFilterBar  from './components/mobile-filter-bar.vue'
    import searchBasicsMixin from '../../mixins/search-basics-mixin'
    import vueEventIndex from './components/index-item.vue'

    export default {
        components: { EventFilter, Pagination, MobileFilterBar, vueEventIndex },

        mixins: [ searchBasicsMixin ],

        props:['searchedevents','onlineevents','categories','user', 'tags', 'maxprice'],

        computed: {
            loadedCat() {
                return this.categories.slice(0,7);
            },

            multipleCategory() {
                return this.categories.filter(cat => cat.checked).map(o => o.id)
            },

            eventTags() {
                return event => event.genres.slice(0, 3);
            },

            fixedprice() {
                return event => event.price_range.replace(/\d+(\.\d{1,2})?/g, dec => parseInt(dec));
            },
        }, 

        data() {
            return {
                onlineEventList: this.onlineevents,
                mobile: window.innerWidth < 768,
                pagination: 1,
                onlinePagination: 1,
            }
        },

        methods: {

            updateOnlineEvents(value) {
                this.onlinePagination = value.current_page;
                this.onlineEventList = value;
            },

            selectOnlinePage (page) {
                this.onlinePagination = page
            },

            onLoad() {
                this.categories.forEach((cat) => cat.checked = false);
            },

            conditionalBodyClass(bool, className) {
                if (bool) {
                    console.log('no scroll added to page');
                    document.body.classList.add(className)
                } else {
                    console.log('no scroll removed from page');
                    document.body.classList.remove(className)
                }
            },
        },

        mounted() {
            this.onLoad();
        },
    };
</script>