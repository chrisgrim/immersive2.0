<template>
    <div class="admin-events">
        <div class="">
            <div class="admin-events__title">
                <h1>Events</h1>
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
                {{ event.name }}
            </div>
            <a :href="`/events/${event.slug}`">
                <button class="default">
                    view
                </button>
            </a>
            <div>
                <button @click="openModal(event)">
                    Owner
                </button>
            </div>
            <a :href="`/create/${event.slug}/title`">
                <button class="default">
                    edit
                </button>
            </a>
            <div>
                <template v-if="event.status === 'p'">
                    <p>Live</p>
                </template>
                <template v-if="event.status === 'e'">
                    <p>Embargoed <br> (goes live {{ cleanDate(event.embargo_date) }})</p>
                </template>
            </div>
        </div>
        <pagination 
            :limit="1"
            :list="events"
            @selectpage="onLoad" />
        <modal 
            v-if="modalVisible" 
            @close="modalVisible = false">
            <div slot="header">
                <div class="circle sub">
                    <p>!</p>
                </div>
            </div>
            <div slot="body"> 
                <h3>Change {{ modalData.name }} Event Organizer</h3>
                <p>Current organizer is {{ modalData.organizer.name }}</p>
                <v-select 
                    v-model="organizer"
                    label="name"
                    placeholder="Enter Organizer"
                    @search="searchOrganizers"
                    @search:focus="searchOrganizers"
                    :clearable="false"
                    :options="organizers.data" />
            </div>
            <div slot="footer">
                <button 
                    class="btn sub" 
                    @click="onChangeOwner">
                    Change Owner
                </button>
            </div>
        </modal>
    </div>
</template>

<script>
    import Pagination  from '../../components/pagination.vue'

    export default {

        components: { Pagination },

        data() {
            return {
                events: [],
                eventList: '',
                organizer: '',
                organizers: [],
                modalData: null,
                modalVisible: false,
            }
        },

        methods: {
            onLoad(page) {
                axios.post(`/admin/events/fetch?page=${page}`)
                .then( res => { this.events = res.data })
            },

            onSearch(events) {
                axios.get('/api/admin/events/search', { params: { keywords: events } })
                .then( res => { this.events = res.data });
            },

            searchOrganizers(value) {
                axios.get('/api/admin/organizer/search', { params: { keywords: value } })
                .then( res => { this.organizers = res.data });
            },

            async onChangeOwner() {
                await axios.post(`/admin/event/${this.modalData.slug}/change-organizer`, this.organizer)
                this.reset();
            },

            openModal(data) {
                this.modalData = data
                this.modalVisible = true
            },

            reset() {
                this.onLoad();
                this.modalData = null
                this.modalVisible = false
            },

            cleanDate(data) {
                return this.$dayjs(data).format("dddd, MMMM D YYYY");
            }
        },

        created() {
            this.onLoad()
        },

    }

</script>