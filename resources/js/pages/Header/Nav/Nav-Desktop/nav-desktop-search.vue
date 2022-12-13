<template>
    <div class="top-0 mt-32 px-10 fixed left-0 flex flex-col z-10 w-full transition origin-[50%_0]">
        <div class="w-full mx-auto flex relative max-w-7xl">
            <div class="absolute z-10 self-center right-4 w-12 h-12 flex items-center justify-center rounded-full bg-default-red">
                <svg class="w-8 h-8 fill-white">
                    <use :xlink:href="`/storage/website-files/icons.svg#ri-search-line`" />
                </svg>
            </div>
            <SearchLocation 
                v-if="value.searchType==='l'" 
                v-model="value" />
            <SearchEvents 
                v-if="value.searchType==='e'" 
                v-model="value" />
            <SearchTags 
                v-if="value.searchType==='t'" 
                v-model="value" />
        </div>
    </div>
</template>

<script>
import SearchLocation from './Components/location-google.vue'
import SearchEvents from './Components/events.vue'
import SearchTags from './Components/tags.vue'
export default {

    props: ['value'],

    components: { SearchLocation, SearchEvents, SearchTags },


};
</script>