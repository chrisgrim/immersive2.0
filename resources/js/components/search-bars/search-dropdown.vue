<template>
    <div 
        :class="{open : open}"
        class="h-full flex relative z-50">
        <template v-if="open">
            <div class="dropdown__wrapper">
                <div class="search-bar">
                    <div class="icon">
                        <svg>
                            <use :xlink:href="`/storage/website-files/icons.svg#ri-search-line`" />
                        </svg>
                    </div>
                    <template v-if="open==='l'">
                        <SearchLocation />
                    </template>
                    <template v-if="open==='e'">
                        <SearchEvents />
                    </template>
                    <template v-if="open==='t'">
                        <SearchTags />
                    </template>
                </div>
            </div>
        </template>
        <template v-if="!open">
            <div class="search-bar">
                <div class="icon">
                    <svg>
                        <use :xlink:href="`/storage/website-files/icons.svg#ri-search-line`" />
                    </svg>
                </div>
                <v-select 
                    :class="{ active : placeholder!=='Start your search'}"
                    :placeholder="placeholder" />
            </div>
        </template>
    </div>
</template>

<script>
import SearchLocation from './components/location.vue'
import SearchEvents from './components/events.vue'
import SearchTags from './components/tags.vue'
export default {

    props: ['page', 'open'],

    components: { SearchLocation, SearchEvents, SearchTags },


    data() {
        return {
            searchInput: null,
            searchOptions: [],
            isLoading: false,
            cover: true,
            placeholder: new URL(window.location.href).searchParams.get("city") ? new URL(window.location.href).searchParams.get("city") : 'Start your search',
        }
    },

    methods: {
    },
};
</script>