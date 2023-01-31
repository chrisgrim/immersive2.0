<template>
    <div class="h-screen relative overflow-hidden text-1xl font-medium">
        <Nav 
            @submit="onSubmit"
            v-model="info" />
        <ExperienceType 
            v-if="info.page==='EventType'"
            @submit="onSubmit"
            v-model="info" />
        <Category 
            v-if="info.page==='Category'"
            :categories="categories"
            @submit="onSubmit"
            v-model="info" />
        <Overview 
            v-if="info.page==='Overview'"
            v-model="info" />
        <Description 
            v-if="info.page==='Description'"
            v-model="info" />
        <EventHost 
            v-if="info.page==='EventHost'"
            v-model="info" />
        <Location 
            @submit="onSubmit"
            v-if="info.page==='Location'"
            v-model="info" />
        <GuestRequirements 
            v-if="info.page==='GuestRequirements'"
            v-model="info" />
        <Title 
            v-if="info.page==='Title'"
            v-model="info" />
        <Photo 
            v-if="info.page==='Photo'"
            v-model="info" />
        <Pricing 
            v-if="info.page==='Pricing'"
            v-model="info" />
        <Footer v-model="info" />
    </div>
</template>

<script>
    import Nav  from './nav.vue'
    import Footer  from './footer.vue'
    import ExperienceType from './Idea/experience-type.vue'
    import Category from './Idea/category.vue'
    import Overview from './Guidelines/overview.vue'
    import Description from './EventPage/description.vue'
    import EventHost from './EventPage/eventhost.vue'
    import Location from './EventPage/location.vue'
    import GuestRequirements from './EventPage/guestrequirements.vue'
    import Title from './EventPage/title.vue'
    import Photo from './EventPage/photo.vue'
    import Pricing from './Settings/pricing.vue'

    export default {

        components: { Nav, ExperienceType, Category, Overview, Description, EventHost, Location, GuestRequirements, Title, Photo, Pricing, Footer },

        props: ['event', 'tags', 'remote', 'categories', 'type'],

        data() {
            return {
                info: { 
                    page: 'Photo', 
                    category: '',
                    pages: this.initalizePages(),
                    event: this.event, 
                    submit: false},
            };
        },

        methods: {
            async onSubmit() {
                await axios.patch(`/experience-hosting/listing/${this.info.event.slug}`, 
                    {event: this.info.event})
                .then( res => { 
                    this.checkList()
                    console.log(res.data);
                });
            },
            checkList() {
                this.info.pages = this.info.event.hasLocation ? 
                ['EventType','Category','Overview','Description','EventHost','Location','GuestRequirements','Title','Photo','EventType','EventType', 'Pricing'] : 
                ['EventType','Category','Overview','Description','EventHost','GuestRequirements','Title','Photo','EventType','EventType', 'Pricing']
            },
            initalizePages() {
                return this.event.hasLocation ? 
                ['EventType','Category','Overview','Description','EventHost','Location','GuestRequirements','Title','Photo','EventType','EventType', 'Pricing'] : 
                ['EventType','Category','Overview','Description','EventHost','GuestRequirements','Title','Photo','EventType','EventType', 'Pricing']
            }
        },

    };

</script>