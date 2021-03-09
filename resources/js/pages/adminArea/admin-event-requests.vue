<template>
    <div class="admin-events-requests">
        <div class="">
            <div class="title">
                <h1>Event Requests</h1>
            </div>
        </div>
        <div class="data-grid">
            <div class="data-grid__row header">
                <p>Event</p>
                <p>Request</p>
                <p>Response</p>
            </div>
            <div 
                class="data-grid__row" 
                :key="request.id"
                v-for="request in requests.data">
                <div class="lg">
                    <a 
                        target="_blank"
                        :href="`/create/${request.event.slug}/title`">
                        <img 
                            v-if="request.event.thumbImagePath"
                            :src="`/storage/${request.event.thumbImagePath}`">
                        <p>{{ request.event.name }}</p>
                    </a>
                </div>
                <div>
                    <p>{{ request.request }}</p>
                </div>
                <button @click="addResponse(request)">
                    Respond
                </button>
                <button 
                    @click="onDelete(request)" 
                    class="delete">
                    <IconSvg type="delete" />
                </button>
            </div>
        </div>
        <VueModalForm 
            v-if="modal === 'response'"
            @onSubmit="onSubmit"
            placeholder="Respond to the request"
            @close="modal = null">
            <h3>Respond to request</h3>
        </VueModalForm>
        <pagination 
            :limit="1"
            :list="requests"
            @selectpage="onLoad" />
    </div>
</template>

<script>
    import Pagination  from '../../components/pagination.vue'
    import IconSvg from '../../components/Svg-icon'
    import VueModalForm from '../../components/Vue-Modal-Form'

    export default {

        components: { Pagination, IconSvg, VueModalForm },

        data() {
            return {
                requests: [],
                eventList: '',
                modal: '',
                selectedModal: null,
            }
        },

        methods: {
            onLoad(page) {
                axios.get(`/admin/event-requests/fetch?page=${page}`)
                .then( res => { this.requests = res.data })
            },

            onSubmit(value) {
                axios.post(`/admin/event-requests/respond/${this.selectedModal.id}`, {response: value})
                location.reload();
            },

            onDelete(request) {
                axios.post(`/admin/event-requests/delete/${request.id}`)
                location.reload();
            },

            addResponse(request) {
                this.selectedModal = request;
                this.modal = 'response';
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