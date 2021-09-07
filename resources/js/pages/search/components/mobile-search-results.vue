<template>
    <div class="search-results">
        <div class="wrapper">
            <template v-if="events.data && events.data.length">
                <p>{{ events.total }} events</p>
            </template>
        </div>
        <VueEventIndex 
            align="vertical"
            :events="events.data" />
    </div>
</template>

<script>
    import VueEventIndex from '../../events/components/index-item.vue'
    export default {
        components: { VueEventIndex},
        props:['events'],
    };
</script>