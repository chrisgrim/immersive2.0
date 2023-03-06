<template>
    <div class="online search relative mt-36 md:mt-0 md:min-h-[calc(100vh-16rem)]">
        <Nav
            ref="childNav"
            type="online"
            @update="updateEvents"
            @clear="clear"
            v-model="searchData"
            :tags="tags" 
            :searched-categories="searchedCategories"
            :at-home-categories="atHomeCategories" 
            :categories="categories" />
        <div class="w-full">
            <div class="relative px-8 md:px-32 md:py-8">
                <h3>Remote Events</h3>
                <Events 
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
    import Pagination  from './AtHome/Components/pagination.vue'
    import Events from './AtHome/album-at-home.vue'
    import Nav  from './AtHome/nav-at-home-mobile.vue'

    export default {
        components: { Pagination, Events, Nav },

        props:['searchedEvents','categories','user', 'tags', 'atHomeCategories', 'searchedCategories'],

        data() {
            return {
                events: this.searchedEvents,
                searchData: this.initializeSearchData(),
            }
        },

        methods: {
            updateEvents(value) {
                this.events = value;
                this.pagination = value.current_page;
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
                    currentTab: 'genre',
                    searchType:'atHome',
                    searchDates: this.initializeDates(),
                    naturalDate: this.initializeNaturalDates(),
                    tag: this.searchedTag ? this.searchedTag : [],
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