<template>
    <section class="organizer-show lay-a">
        <div class="wrapper">
            <template v-if="!mobile">
                <div class="sidebar">
                    <div class="sticky">
                        <template v-if="organizer.largeImagePath">
                            <div class="logo">
                                <picture>
                                    <source 
                                        type="image/webp" 
                                        :srcset="`/storage/${organizer.thumbImagePath}`"> 
                                    <img 
                                        :src="`/storage/${organizer.thumbImagePath.slice(0, -4)}jpg`" 
                                        :alt="`${organizer.name} organizer`">
                                </picture>
                            </div>
                        </template>
                        <Social :organizer="organizer" />
                    </div>
                </div>
            </template>
            <div class="content">
                <div class="intro">
                    <div class="name">
                        <h2>{{ organizer.name }}</h2>
                    </div>
                    <div class="joined">
                        <p>Joined EI in {{ cleanDate(organizer.created_at) }}</p>
                    </div>
                </div>
                <template v-if="mobile">
                    <template v-if="organizer.largeImagePath">
                        <div class="logo">
                            <picture>
                                <source 
                                    type="image/webp" 
                                    :srcset="`/storage/${organizer.thumbImagePath}`"> 
                                <img 
                                    :src="`/storage/${organizer.thumbImagePath.slice(0, -4)}jpg`" 
                                    :alt="`${organizer.name} organizer`">
                            </picture>
                        </div>
                    </template>
                    <Social :organizer="organizer" />
                </template>
                <div class="description">
                    <p>{{ organizer.description }}</p>
                </div>
                <template v-if="events">
                    <div>
                        <h4>Events by {{ organizer.name }}</h4>
                        <Album 
                            align="vertical"
                            col="three"
                            :events="events.data"
                            :past="true" />
                    </div>
                    <pagination 
                        :limit="1"
                        :list="events"
                        @selectpage="onLoad" />
                </template>
            </div>
        </div>
        <!-- <ContactOrganizer :user="user" :loadorganizer="organizer"></ContactOrganizer> -->
    </section>
</template>
<script>
    import ContactOrganizer from '../organizers/contact-organizer.vue'
    import Pagination  from '../../components/pagination.vue'
    import Social  from './components/social.vue'
    import Album from '../events/components/index-item.vue'

    export default {

        props: ['organizer', 'user'],

        components: { ContactOrganizer, Pagination, Album, Social },

        computed: {
        },

        data() {
            return {
                events: [],
                mobile: window.innerWidth < 768 ? true : false,
            }
        },

        methods: {
            async onLoad(page) {
                try {
                    let {data} = await axios.get(`/organizer/${this.organizer.slug}/events?page=${page}` )
                    this.events = data
                } 
                catch(err) { err => {err.data}}
            },
            cleanDate(data) {
                return this.$dayjs(data).format("YYYY");
            }
        },

        mounted() {
            this.onLoad();
        },
    };
</script>