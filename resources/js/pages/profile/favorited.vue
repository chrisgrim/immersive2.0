<template>
    <div class="favorited">
        <section class="section-a">
            <div class="section-a__wrapper">
                <div class="name">
                    <h3>Your Favorited Events</h3>
                </div>
                <vue-event-index :events="eventList.data" />
                <div>
                    <pagination 
                        :limit="1"
                        :list="eventList"
                        @selectpage="onLoad" />
                </div>
            </div>
        </section>
    </div>
</template>

<script>
    import vueEventIndex from '../events/components/index-item.vue'
    import Pagination  from '../../components/pagination.vue'

    export default {

        props: ['loaduser', 'events'],

        components: { vueEventIndex, Pagination },

        data() {
            return {
                eventList: this.events ? this.events : []
            }
        },

        methods: {
            onLoad(page) {
                axios.post(`/admin/event/boneyard/fetch?page=${page}`)
                .then( res => { this.eventList = res.data })
            },
        },  

    };
</script>