<template>
    <div class="favorited-index">
        <div class="favorited-index__title">
            <h3>Your Favorited Events</h3>
        </div>

        <div class="event-index-eventlist grid">
            <vue-event-index :events="eventList.data" />
        </div>
        <div>
            <pagination 
                :limit="1"
                :list="eventList"
                @selectpage="onLoad" />
        </div>
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