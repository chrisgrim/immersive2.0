<template>
    <div class="h-screen relative overflow-hidden text-1xl font-medium">
        <Nav 
            v-model="page"
            :event="event" />
        <ExperienceType 
            v-if="page==='E-T'"
            :event="event" />
        <Category 
            v-if="page==='C'"
            :event="event" />
        <Overview 
            v-if="page==='O'"
            :event="event" />
    </div>
</template>

<script>
    import Nav  from './nav.vue'
    import ExperienceType from './Idea/experience-type.vue'
    import Category from './Idea/category.vue'
    import Overview from './Guidelines/overview.vue'

    export default {

        components: { Nav, ExperienceType, Category, Overview },

        props: ['event', 'tags', 'remote', 'categories', 'type'],

        data() {
            return {
                page: 'E-T'
            };
        },

    };

</script>