<template>
    <div class="online search">
        <SearchFilter
            @allevents="updateAllEvents"
            :allevents="allevents" 
            :tags="tags" 
            :categories="categories" />
        <div class="search__list">
            <div class="results">
                <div class="title">
                    <h3>All Events</h3>
                </div>
                <VueList 
                    :items="allEventList.data" 
                    :vertical="true" />
                <pagination 
                    :limit="1"
                    :list="allEventList"
                    @selectpage="selectPage" />
            </div>
        </div>
    </div>
</template>

<script>
    import SearchFilter  from './vue-search-filter-all.vue'
    import Pagination  from '../events/components/pagination.vue'
    import searchBasicsMixin from '../../mixins/search-basics-mixin'
    import VueList from '../../components/Vue-List.vue'

    export default {
        components: { SearchFilter, Pagination, VueList },

        mixins: [ searchBasicsMixin ],

        props:['allevents','categories','user', 'tags'],

        data() {
            return {
                allEventList: this.allevents,
            }
        },

        methods: {
            updateAllEvents(value) {
                this.allEventList = value.data;
            },

            selectPage (page) {
                this.$store.commit('filterPagination', page)
            },
        },
    };
</script>