<template>
    <div>
        <a :href="'/create/'+ this.event.slug +'/title'">
        <div class="checklist-section">
            Title
            <svg class="checkbox" v-if="event.name" viewBox="0 0 52 52"><path d="m19.1 25.2 4.7 6.2 12.1-11.2"></path></svg>
        </div>
        </a>

        <a :href="'/create/'+ this.event.slug +'/location'">
        <div class="checklist-section" >
            Location 
                <svg class="checkbox" v-if="event.location_latlon" viewBox="0 0 52 52"><path d="m19.1 25.2 4.7 6.2 12.1-11.2"></path></svg>
        </div>
        </a>
        <a :href="'/create/'+ this.event.slug +'/category'">
        <div class="checklist-section">
            Category
            <svg class="checkbox" v-if="event.category_id" viewBox="0 0 52 52"><path d="m19.1 25.2 4.7 6.2 12.1-11.2"></path></svg>
        </div>
        </a>

        <a :href="'/create/'+ this.event.slug +'/shows'">
        <div class="checklist-section">
            Dates and Tickets
            <svg class="checkbox" v-if="event.show_times" viewBox="0 0 52 52"><path d="m19.1 25.2 4.7 6.2 12.1-11.2"></path></svg>
        </div>
        </a>

        <a :href="'/create/'+ this.event.slug +'/description'">
        <div class="checklist-section">
            Event Description
            <svg class="checkbox" v-if="event.description" viewBox="0 0 52 52"><path d="m19.1 25.2 4.7 6.2 12.1-11.2"></path></svg>
        </div>
        </a>

        <a :href="'/create/'+ this.event.slug +'/expect'">
        <div class="checklist-section">
            Advisories and Restrictions
            <svg class="checkbox" v-if="event.expectation_id" viewBox="0 0 52 52"><path d="m19.1 25.2 4.7 6.2 12.1-11.2"></path></svg>
        </div>
        </a>

        <a :href="'/create/'+ this.event.slug +'/images'">
        <div class="checklist-section">
            Photo
            <svg class="checkbox" v-if="event.largeImagePath" viewBox="0 0 52 52"><path d="m19.1 25.2 4.7 6.2 12.1-11.2"></path></svg>
        </div>
        </a>

        
    </div>
</template>

<script>

    export default {
        props: {
            event: {
                type:Object
            },
        },
    }

</script>