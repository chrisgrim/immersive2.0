<template>
    <div class="online search relative mt-36 md:mt-0 md:min-h-[calc(100vh-7rem)]">
        <template v-if="mobile">
            <MobileSearchNav
                ref="search"
                type="all"
                @update="updateEvents"
                v-model="paginate"
                filter="all"
                :mobile="mobile"
                :tags="tags" 
                :categories="categories" />
        </template>
        <template v-else>
            <SearchNav
                @update="updateEvents"
                type="all"
                :mobile="mobile"
                :tags="tags"
                filter="all"
                v-model="paginate"
                :categories="categories" />
        </template>
        <div class="w-full">
            <div class="relative px-8 md:px-32 md:py-8">
                <h3>All Events</h3>
                <SearchAlbum 
                    :user="user"
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
    import SearchNav  from './Online/vue-online-desktop-search-nav.vue'
    import Pagination  from './Online/Components/pagination.vue'
    import SearchAlbum from './Online/vue-online-album.vue'
    import MobileSearchNav  from './Online/vue-online-mobile-search-nav.vue'
    export default {
        components: { SearchNav, Pagination, SearchAlbum, MobileSearchNav },

        props:['allevents','categories','user', 'tags','mobile'],

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
                window.scrollTo(0,0);
            },
        },
    };
</script>