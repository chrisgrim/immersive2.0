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
                <p>New Name</p>
                <p>Reason</p>
                <p>Change Name</p>
                <p>Respond</p>
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
                    <p>{{ request.name }}</p>
                </div>
                <div>
                    <p>{{ request.request }}</p>
                </div>
                <button 
                    @click="changeName(request)" 
                    class="delete">
                    <IconSvg type="edit" />
                </button>
                <button @click="addResponse(request)">
                    Respond
                </button>
            </div>
        </div>
        <VueModalForm 
            v-if="modal === 'edit'"
            @onSubmit="onSubmitNameChange"
            input-placeholder=""
            input="true"
            @close="modal = null">
            <h3>Enter new Event Name</h3>
        </VueModalForm>
        <VueModalForm 
            v-if="modal === 'response'"
            @onSubmit="onSubmitResponse"
            textarea="true"
            textarea-placeholder="Respond to the owner"
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
    import VueModalForm from '../../components/modals/Vue-Modal-Form'

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

            onSubmitResponse(value) {
                axios.post(`/admin/event-requests/respond/${this.selectedModal.id}`, value);
                location.reload();
            },

            onSubmitNameChange(value) {
                axios.post(`/admin/event-requests/edit/${this.selectedModal.id}`, value);
                location.reload();
            },

            addResponse(request) {
                this.selectedModal = request;
                this.modal = 'response';
            },

            changeName(request) {
                this.selectedModal = request;
                this.modal = 'edit';
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