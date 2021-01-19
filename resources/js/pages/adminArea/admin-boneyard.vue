<template>
    <div class="admin-events">
        <div class="">
            <div class="admin-events__title">
                <h1>Boneyard</h1>
            </div>
        </div>
        <div class="field">
            <input 
                v-model="eventList"
                placeholder="Filter by event name" 
                class="general"
                @keyup="onSearch(eventList)"
                type="text">
        </div>
        <div 
            class="list"
            :key="event.id"
            v-for="event in events.data">
            <div>
                <img 
                    style="height:40px;width:40px;object-fit:cover;" 
                    :src="`/storage/${event.thumbImagePath}`" 
                    alt="">
            </div>
            <div>
                {{ event.name }}
            </div>
            <div>
                <button @click="onResurrect(event)">
                    Resurrect
                </button>
            </div>
        </div>
        <pagination 
            :limit="1"
            :list="events"
            @selectpage="onLoad" />
    </div>
</template>

<script>
    import Pagination  from '../../components/pagination.vue'

    export default {

        components: { Pagination },

        data() {
            return {
                events: this.loadedevents ? this.loadedevents : [],
                eventList: '',
            }
        },

        methods: {

            onSearch(eventList) {
                axios.get('/api/admin/event/deleted/search', { params: { keywords: eventList } })
                .then( res => { this.events = res.data })
            },

            onLoad(page) {
                axios.post(`/admin/event/boneyard/fetch?page=${page}`)
                .then( res => { this.events = res.data })
            },

            async onResurrect(event) {
                await axios.post(`/admin/event/boneyard/${event.id}/resurrect`)
                this.onLoad()
            }   
        },

        created() {
            this.onLoad()
        },

    }

</script>