<template>
    <div class="online search">
        <SearchFilter
            @onlineevents="updateOnlineEvents"
            :onlineevents="onlineevents" 
            :tags="tags" 
            :categories="categories" />
        <div class="search__list">
            <div class="results">
                <div class="title">
                    <h3>Online Events</h3>
                </div>
                <SearchAlbum 
                    :items="allEventList.data" 
                    :vertical="true" />
                <pagination 
                    :limit="1"
                    :list="onlineEventList"
                    @selectpage="selectOnlinePage" />
            </div>
        </div>
    </div>
</template>

<script>
    import SearchFilter  from './vue-search-filter.vue'
    import Pagination  from '../events/components/pagination.vue'
    import searchBasicsMixin from '../../mixins/search-basics-mixin'
    import SearchAlbum from './components/album-search.vue'

    export default {
        components: { SearchFilter, Pagination, SearchAlbum },

        mixins: [ searchBasicsMixin ],

        props:['searchedevents','onlineevents','categories','user', 'tags'],

        data() {
            return {
                onlineEventList: this.onlineevents,
            }
        },

        methods: {
            updateOnlineEvents(value) {
                this.onlineEventList = value.data;
            },

            selectOnlinePage (page) {
                 this.$store.commit('filterPagination', page)
            },
        },


    };
</script>