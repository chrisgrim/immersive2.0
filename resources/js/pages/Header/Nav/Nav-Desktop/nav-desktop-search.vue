<template>
    <div class="top-0 mt-32 px-10 fixed left-0 flex flex-col z-10 w-full transition origin-[50%_0]">
        <div class="w-full mx-auto flex relative max-w-5xl">
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