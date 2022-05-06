<template>
    <div class="h-screen relative overflow-hidden text-1xl font-medium">
        <Nav 
            :page="page"
            :event="event"/>
        <ExperienceType 
            v-if="page='I-T'"
            :event="event"/>
    </div>
</template>

<script>
    import Nav  from './nav.vue'
    import ExperienceType from './Idea/experience-type.vue'

    export default {

        components: { Nav, ExperienceType },

        props: ['event', 'tags', 'remote', 'categories', 'type'],

        data() {
            return {
                page: this.type
            };
        },

    };

</script>