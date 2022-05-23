<template>
    <section class="m-auto w-full md:px-8 md:py-8 md:px-12 lg:py-0 lg:px-32 lg:max-w-screen-xl lg:pt-24">
        <div class="flex flex-col md:flex-row">
            <template v-if="mobile">
                <button 
                    aria-label="back button"
                    type="button"
                    @click="onBack"
                    class="absolute flex top-8 left-8 z-10 p-1 rounded-full shadow-lg bg-white">
                    <svg class="h-12 w-12">
                        <use :xlink:href="`/storage/website-files/icons.svg#ri-arrow-left-s-line`" />
                    </svg>
                </button>
            </template>
            <template v-else>
                <div class="w-full inline-block md:w-2/6 md:px-8 lg:p-8">
                    <div class="sticky top-16 items-center flex flex-col">
                        <div 
                            v-if="organizer.largeImagePath"
                            class="p-8 w-1/4 mb-8 inline-block align-top w-full md:p-0 md:flex md:justify-center md:w-full">
                            <picture>
                                <source 
                                    type="image/webp" 
                                    :srcset="`/storage/${organizer.thumbImagePath}`"> 
                                <img
                                    class="w-full h-full rounded-full md:h-80 md:w-80"
                                    :src="`/storage/${organizer.thumbImagePath.slice(0, -4)}jpg`" 
                                    :alt="`${organizer.name} organizer`">
                            </picture>
                        </div>
                        <Social :organizer="organizer" />
                    </div>
                </div>
            </template>
            <div class="w-full inline-block md:w-4/6">
                <div class="w-3/4 inline-block p-8 float-right md:float-left md:w-full md:px-0">
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
                                    class="w-full h-full"
                                    :src="`/storage/${organizer.thumbImagePath.slice(0, -4)}jpg`" 
                                    :alt="`${organizer.name} organizer`">
                            </picture>
                        </div>
                    </template>
                    <Social :organizer="organizer" />
                </template>
                <div class="whitespace-pre-wrap mb-8 px-8 md:px-0">
                    <p>{{ organizer.description }}</p>
                </div>
                <template v-if="events">
                    <div class="border-t p-8 mb-8 md:border-none md:p-0">
                        <h4>Events by {{ organizer.name }}</h4>
                        <Album
                            v-if="events.data"
                            :mobile="mobile"
                            :user="user"
                            :items="events.data"
                            :past="true" />
                    </div>
                    <pagination 
                        :limit="1"
                        :list="events"
                        @selectpage="onLoad" />
                </template>
            </div>
        </div>
    </section>
</template>
<script>
    import Pagination  from './Show/Components/pagination.vue'
    import Social  from './Show/vue-social-component.vue'
    import Album from './Show/vue-events-album.vue'

    export default {

        props: ['organizer', 'user', 'mobile'],

        components: { Pagination, Album, Social },

        data() {
            return {
                events: [],
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
            },
            onBack() {
                document.referrer == "" ? window.location.href = '/' : window.history.back()
            }
        },

        mounted() {
            this.onLoad();
        },
    };
</script>