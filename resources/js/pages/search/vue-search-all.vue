<template>
    <div class="online search">
        <template v-if="mobile">
            <MobileSearchNav
                ref="search"
                type="all"
                @update="updateEvents"
                v-model="paginate"
                filter="all"
                :tags="tags" 
                :categories="categories" />
        </template>
        <template v-else>
            <SearchFilter
                type="all"
                @update="updateEvents"
                :tags="tags"
                filter="all"
                v-model="paginate"
                :categories="categories" />
        </template>
        <div class="search__list">
            <div class="results">
                <div class="title">
                    <h3>All Events</h3>
                </div>
                <SearchAlbum 
                    :items="events.data" 
                    :vertical="true" />
                <pagination 
                    :limit="1"
                    :list="events"
                    @selectpage="selectPage" />
            </div>
        </div>
    </div>
</template>

<script>
    import SearchFilter  from './vue-search-filter.vue'
    import Pagination  from './components/search-pagination.vue'
    import SearchAlbum from './components/album-search.vue'
    import MobileSearchNav  from './online-mobile-search-nav.vue'
    export default {
        components: { SearchFilter, Pagination, SearchAlbum, MobileSearchNav },

        props:['allevents','categories','user', 'tags'],

        data() {
            return {
                events: this.allevents,
                paginate: 1,
                mobile: window.innerWidth < 768,
            }
        },

        methods: {
            updateEvents(value) {
                this.events = value.data;
            },
            selectPage (page) {
                this.paginate = page
            },
        },
    };
</script>