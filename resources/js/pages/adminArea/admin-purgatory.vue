<template>
    <div class="admin-events">
        <div class="">
            <div class="admin-events__title">
                <h1>Purgatory</h1>
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
                <a 
                    target="_blank" 
                    :href="`/create/${event.slug}/title`">
                    <button v-if="event.status == 'r'"> Event In Review </button>
                    <button v-else> Edit </button>
                </a>
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
                axios.get('/api/admin/purgatory/search', { params: { keywords: eventList } })
                .then( res => { console.log(res.data); this.events = res.data });
            },

            onLoad(page) {
                axios.post(`/admin/events/purgatory/fetch?page=${page}`)
                .then( res => {
                    this.events = res.data;
                })
            },
        },

        created() {
            this.onLoad()
        },

    }

</script>