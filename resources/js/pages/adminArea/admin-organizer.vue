<template>
    <div>
        <h2 class="mb-8">Organizers</h2>
        <div class="field">
            <input 
                v-model="organizer"
                placeholder="Filter by organization name" 
                class="general"
                @keyup="debounce(organizer, 'org')"
                type="text">
        </div>
        <div class="not-prose relative bg-slate-50 rounded-xl overflow-hidden dark:bg-slate-800/25">
            <div class="relative rounded-xl overflow-auto border">
                <div class="shadow-sm overflow-hidden my-8">
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
                            <template v-if="organizers && organizers.length">
                                <tr
                                    v-for="(org) in organizers"
                                    :key="org.id">
                                    <td class="border-b border-slate-100 dark:border-slate-700 p-4 pl-8 text-slate-500 dark:text-slate-400">
                                        <p class="text-xl">{{org.id}}</p>
                                    </td>
                                    <td class="border-b border-slate-100 dark:border-slate-700 p-4 pl-8 text-slate-500 dark:text-slate-400">       
                                        <a 
                                            class="rounded-full w-12 h-12 block flex items-center justify-center hover:bg-black" 
                                            :href="`/organizer/${org.slug}/edit`">
                                            <svg class="w-8 h-8 hover:fill-white">
                                                <use :xlink:href="`/storage/website-files/icons.svg#ri-edit-2-line`" />
                                            </svg>
                                        </a>
                                    </td>
                                    <td class="border-b border-slate-100 dark:border-slate-700 p-4 pl-8 text-slate-500 dark:text-slate-400">
                                        <div class="flex flex-col items-center">
                                            <img 
                                                :src="`/storage/${org.thumbImagePath}`"
                                                class="w-12 rounded-full">
                                            <p class="text-xl">{{ org.name }}</p>
                                        </div>
                                    </td>
                                    <td class="border-b border-slate-100 dark:border-slate-700 p-4 pl-8 text-slate-500 dark:text-slate-400">
                                        <p 
                                            v-if="org.user"
                                            @click.prevent="showModal(org, 'changeOwner')"
                                            class="underline text-xl">
                                            {{ org.user ? org.user.name : 'No User' }}
                                        </p>
                                    </td>
                                    <td class="border-b border-slate-100 dark:border-slate-700 p-4 pl-8 text-slate-500 dark:text-slate-400">
                                        <p class="text-xl">{{ org.user ? org.user.email : 'No User' }}</p>
                                    </td>
                                    <td class="border-b border-slate-100 dark:border-slate-700 p-4 pl-8 text-slate-500 dark:text-slate-400">
                                        <v-select 
                                            v-model="org.users"
                                            label="name"
                                            placeholder="Members"
                                            multiple
                                            :append-to-body="true"
                                            @search="debounce($event, 'user')"
                                            @input="addNewMember($event, org)"
                                            :filterable="false"
                                            :options="users.length ? users : org.users">
                                            <template #option="option" class="test">
                                                <div class="v-dropcell">
                                                    <p>{{ option.name }} ({{option.email}}) </p>
                                                </div>
                                            </template>
                                        </v-select>
                                    </td>
                                    <td class="border-b border-slate-100 dark:border-slate-700 p-4 pl-8 text-slate-500 dark:text-slate-400">
                                        <button 
                                            @click.prevent="showModal(org, 'deleteOrg')" 
                                            class="rounded-full p-2 hover:bg-black hover:border-black">
                                            <svg class="w-8 h-8 hover:fill-white">
                                                <use :xlink:href="`/storage/website-files/icons.svg#ri-close-line`" />
                                            </svg>
                                        </button>
                                    </td>
                                </tr>
                            </template>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
        <VueDeleteModal 
            v-if="modal == 'deleteOrg'"
            :item="selectedModal"
            :strict="true"
            body="Deleting this organizer will delete all of the organizers events as well. Please be sure you know what you are doing."
            @close="modal = null"
            @ondelete="onDelete" />
        <VueDataModal 
            v-if="modal == 'changeOwner'"
            @onSubmit="onChangeOwner"
            search="user"
            @close="modal = null"
            :item="selectedModal">
            <h3> Change owner of the organization {{ selectedModal.name }} </h3>
            <p> Select user below </p>
        </VueDataModal>
    </div>
</template>

<script>
    import VueDeleteModal from '../../components/modals/Vue-Modal-Delete'
    import VueDataModal from '../../components/modals/Vue-Data-Modal'

    export default {

        components: { VueDeleteModal, VueDataModal },

        data() {
            return {
                organizers: [],
                organizer: null,
                isModalVisible: false,
                isEditModalVisible: false,
                modalDelete: '',
                selectedModal: '',
                modal: '',
                user: '',
                users:[],
                showEdit: false,
                cols: [
                    { id:0, field: 'id', class:''},
                    { id:1, field: 'edit', class:''},
                    { id:2, field: 'Organization', class:'text-center' },
                    { id:3, field: 'Owner', class:''},
                    { id:4, field: 'Email',class:'' },
                    { id:5, field: 'Members', class:'' },
                    { id:6, field: '', class:'' },
                ],
                columns: `grid-auto-columns: 5rem 5rem minmax(15rem, 40rem) minmax(10rem, 40rem) minmax(20rem, 40rem) minmax(20rem, 1fr) 5rem;`,
            }
        },

        methods: {
            showModal(event, arr) {
                this.selectedModal = event;
                this.modal = arr;
            },
            async onDelete() {
                await axios.delete(`/organizer/${this.selectedModal.slug}`)
                location.reload()
            },
            onLoad() {
                axios.get(`/admin/organizer/fetch?timestamp=${new Date().getTime()}`)
                .then( res => { this.organizers = res.data.data });
            },
            async onChangeOwner(value) {
                await axios.post(`/admin/organizer/${this.selectedModal.slug}/changeUser`, value)
                location.reload()
            },
            async addNewMember(value, organization) {
                await axios.post(`/admin/organizer/${organization.slug}/addTeamMember`, value)

            },
            onSearchOrganizers (query) {
                axios.get('/api/admin/organizer/search', { params: { keywords: query } })
                .then( res => { this.organizers = res.data.data });
            },
            onSearchUsers(query) {
                axios.get('/api/admin/users/search', { params: { keywords: query } })
                .then( res => { this.users = res.data.data })
            },
            debounce(query, type) {
                if (this.timeout) 
                    clearTimeout(this.timeout); 
                this.timeout = setTimeout(() => {
                    type === 'org' ? this.onSearchOrganizers(query) : this.onSearchUsers(query);
                }, 200); // delay
            },
        },

        created() {
            this.onLoad();
        },

    }

</script>