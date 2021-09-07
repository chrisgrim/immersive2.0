<template>
    <div class="online search">
        <SearchFilter
            @update="updateEvents"
            :tags="tags"
            filter="all"
            v-model="paginate"
            :categories="categories" />
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

    export default {
        components: { SearchFilter, Pagination, SearchAlbum },

        props:['allevents','categories','user', 'tags'],

        data() {
            return {
                events: this.allevents,
                paginate: 1,
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