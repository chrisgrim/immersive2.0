<template>
    <div class="admin-events-requests">
        <div class="">
            <div class="title">
                <h1>Event Requests</h1>
            </div>
        </div>
        <div class="not-prose relative bg-slate-50 rounded-xl overflow-hidden dark:bg-slate-800/25">
            <div class="relative rounded-xl overflow-auto border">
                <div class="shadow-sm overflow-hidden my-4">
                    <table class="table-auto border-collapse w-full">
                        <thead>
                            <tr>
                                <th 
                                    :key="col.id"
                                    v-for="col in cols"
                                    class="border-b dark:border-slate-600 font-medium p-4 pl-8 pt-0 pb-3 text-slate-400 dark:text-slate-200 text-left">
                                    <p 
                                        class="text-xl" 
                                        :class="col.class">{{ col.field }}</p>
                                </th>
                            </tr>
                        </thead>
                        <tbody class="bg-white dark:bg-slate-800">
                            <tr
                                :key="request.id"
                                v-for="request in requests.data">
                                <td class="border-b border-slate-100 dark:border-slate-700 p-4 pl-8 text-slate-500 dark:text-slate-400">
                                    <a 
                                        target="_blank"
                                        class="flex flex-col items-center" 
                                        :href="`/create/${request.event.slug}/title`">
                                        <img 
                                            class="w-32 rounded-2xl" 
                                            v-if="request.event.thumbImagePath"
                                            :src="`${$envImageUrl}${request.event.thumbImagePath}`">
                                        <p class="text-xl text-center">{{ request.event.name }}</p>
                                    </a>
                                </td>
                                <td class="border-b border-slate-100 dark:border-slate-700 p-4 pl-8 text-slate-500 dark:text-slate-400">
                                    <p  class="text-xl">
                                        {{ request.name }}
                                    </p>
                                </td>
                                <td class="border-b border-slate-100 dark:border-slate-700 p-4 pl-8 text-slate-500 dark:text-slate-400">
                                    <p  class="text-xl">
                                        {{ request.request }}
                                    </p>
                                </td>
                                <td class="border-b border-slate-100 dark:border-slate-700 p-4 pl-8 text-slate-500 dark:text-slate-400">
                                    <button 
                                        class="block flex items-center justify-center border-none" 
                                        @click="changeName(request)">
                                        <svg class="w-12 h-12 rounded-full p-2 hover:bg-black hover:fill-white">
                                            <use :xlink:href="`/storage/website-files/icons.svg#ri-edit-2-line`" />
                                        </svg>
                                    </button>
                                </td>
                                <td class="border-b border-slate-100 dark:border-slate-700 p-4 pl-8 text-slate-500 dark:text-slate-400">
                                    <button 
                                        @click="addResponse(request)" 
                                        class="rounded-full p-2 hover:bg-black hover:border-black hover:text-white">
                                        Respond
                                    </button>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
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
    import Pagination  from '../../../components/pagination.vue'
    import VueModalForm from '../../../components/modals/Vue-Modal-Form'

    export default {

        components: { Pagination, VueModalForm },

        data() {
            return {
                requests: [],
                eventList: '',
                modal: '',
                selectedModal: null,
                cols: [
                    { id:0, field: 'Event', class:''},
                    { id:1, field: 'New Name', class:''},
                    { id:2, field: 'Reason', class:''},
                    { id:3, field: 'Change Name', class:'' },
                    { id:4, field: 'Respond', class:''},
                ],
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