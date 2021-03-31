<template>
    <div class="admin-events">
        <div class="">
            <div class="title">
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
        <div class="data-grid">
            <div class="data-grid__row header">
                <p />
                <p>Name</p>
                <p>Organization</p>
                <p>Status</p>
                <p>Clicks</p>
            </div>
            <div 
                class="data-grid__row" 
                :key="event.id"
                v-for="event in events.data">
                <div class="edit">
                    <a :href="`/create/${event.slug}/title`">
                        <IconSvg type="edit" />
                    </a>
                </div>
                <div class="lg">
                    <a 
                        target="_blank"
                        :href="`/create/${event.slug}/title`">
                        <img 
                            v-if="event.thumbImagePath"
                            :src="`/storage/${event.thumbImagePath}`">
                        <p>{{ event.name }}</p>
                    </a>
                </div>
                <div>
                    <button 
                        class="noBox" 
                        @click.prevent="showModal(event, 'changeOrganizer')">
                        {{ event.organizer.name }}
                    </button>
                </div>
                <div class="center">
                    <template v-if="event.status === 'p'">
                        <p>Live</p>
                    </template>
                    <template v-if="event.status === 'e'">
                        <p>Embargoed <br> (goes live {{ cleanDate(event.embargo_date) }})</p>
                    </template>
                </div>
                <div class="center">
                    <p v-if="event.clicks">{{ event.clicks.length }}</p>
                </div>
            </div>
        </div>
        <pagination 
            :limit="1"
            :list="events"
            @selectpage="onLoad" />
        <VueDataModal 
            v-if="modal == 'changeOrganizer'"
            @onSubmit="changeEventOrganization"
            search="organizer"
            @close="modal = null"
            :item="modalData">
            <h3>Change {{ modalData.name }} Event Organizer</h3>
            <p>Current organizer is {{ modalData.organizer.name }}</p>
        </VueDataModal>
    </div>
</template>

<script>
    import Pagination  from '../../components/pagination.vue'
    import IconSvg from '../../components/Svg-icon'
    import VueDataModal from '../../components/Vue-Data-Modal'

    export default {

        components: { Pagination, IconSvg, VueDataModal },

        data() {
            return {
                events: [],
                eventList: '',
                organizer: '',
                organizers: [],
                modalData: null,
                modal: '',
            }
        },

        methods: {
            onLoad(page) {
                axios.get(`/admin/events/fetch?page=${page}`)
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

            async changeEventOrganization(value) {
                await axios.post(`/admin/event/${this.modalData.slug}/change-organizer`, value)
                this.reset();
            },

            showModal(event, arr) {
                this.modalData = event;
                this.modal = arr;
            },

            reset() {
                this.onLoad();
                this.modalData = null
                this.modal = false
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