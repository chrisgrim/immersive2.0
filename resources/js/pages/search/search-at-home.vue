<template>
    <div class="online search relative mt-0 min-h-[calc(100vh-16rem)]">
        <Nav
            @update="updateEvents"
            @clear="clear"
            ref="childNav"
            v-model="searchData"
            :tags="tags"
            filter="online"
            :searched-categories="searchedCategories"
            :at-home-categories="atHomeCategories"
            :categories="categories" />
        <div class="w-full">
            <div class="relative px-8 md:px-32 md:py-8">
                <h3>{{title}}</h3>
                <EventList 
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
    import Nav  from './AtHome/nav-at-home.vue'
    import Pagination  from './AtHome/Components/pagination.vue'
    import EventList from './AtHome/album-at-home.vue'

    export default {
        components: { Nav, Pagination, EventList, },

        props:['searchedEvents','atHomeCategories','searchedCategories', 'searchedTags', 'user', 'tags', 'categories'],

        data() {
            return {
                events: this.searchedEvents,
                searchData: this.initializeSearchData(),
                title: 'At Home Events',
            }
        },

        methods: {
            updateEvents(value) {
                this.events = value;
                this.searchData.pagination = value.current_page;
            },
            selectPage (page) {
                this.searchData.paginate = page
                this.$refs.childNav.onNext();
                window.scrollTo(0,0);
            },
            clear() {
                this.searchData = this.initializeSearchData()
            },
            initializeSearchData() {
                return {
                    paginate: 1,
                    currentTab: '',
                    searchType:'atHome',
                    searchDates: this.initializeDates(),
                    dates: this.initializeDates(),
                    naturalDate: this.initializeNaturalDates(),
                    tag: this.searchedTags ? this.searchedTags : [],
                    category: this.searchedCategories ? this.searchedCategories : [],
                    price:this.initializePrice(),
                }
            },
            initializePrice() {
                return new URL(window.location.href).searchParams.get("price0") ? [Number(new URL(window.location.href).searchParams.get("price0")), Number(new URL(window.location.href).searchParams.get("price1"))] : [0,100]
            },
            initializeDates() {
                return new URL(window.location.href).searchParams.get("start") ? [new URL(window.location.href).searchParams.get("start"), new URL(window.location.href).searchParams.get("end")] : []
            },
            initializeNaturalDates() {
                if (new URL(window.location.href).searchParams.get("start")) {
                    return [this.$dayjs(new URL(window.location.href).searchParams.get("start")).format("MMM D"), this.$dayjs(new URL(window.location.href).searchParams.get("end")).format("MMM D")] 
                }
                return []
            },
        },


    };
</script>