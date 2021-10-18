<template>
    <div class="admin-organizers">
        <div class="">
            <div class="title">
                <h1>Organizers</h1>
            </div>
        </div>

        <div class="field">
            <input 
                v-model="organizer"
                placeholder="Filter by organization name" 
                class="general"
                @keyup="debounce(organizer, 'org')"
                type="text">
        </div>
        <template>
            <div 
                ref="table"
                class="v-table">
                <div class="v-header-pane v-pane">
                    <div 
                        :style="columns"
                        class="v-header v-row">
                        <div 
                            :key="col.id"
                            v-for="col in cols"
                            class="v-cell">
                            <p>{{ col.field }}</p>
                        </div>
                    </div>
                </div>
                <template v-if="organizers && organizers.length">
                    <div
                        :style="columns"
                        class="v-row"
                        v-for="(org) in organizers"
                        :key="org.id">
                        <div class="v-cell">
                            <p>{{org.id}}</p>
                        </div>
                        <div class="v-cell">
                            <div class="edit">
                                <a :href="`/organizer/${org.slug}/edit`">
                                    <svg>
                                        <use :xlink:href="`/storage/website-files/icons.svg#ri-edit-2-line`" />
                                    </svg>
                                </a>
                            </div>
                        </div>
                        <div class="v-cell">
                            <img :src="`/storage/${org.thumbImagePath}`">
                            <p>{{ org.name }}</p>
                        </div>
                        <div class="v-cell">
                            <button 
                                v-if="org.user"
                                class="noBox" 
                                @click.prevent="showModal(org, 'changeOwner')">
                                <p style="text-decoration: underline;">{{ org.user ? org.user.name : 'No User' }}</p>
                            </button>
                        </div>
                        <div class="v-cell">
                            <p>{{ org.user ? org.user.email : 'No User' }}</p>
                        </div>
                        <div class="v-cell">
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
                        </div>
                        <div class="v-cell">
                            <button 
                                @click.prevent="showModal(org, 'deleteOrg')" 
                                class="delete">
                                <svg>
                                    <use :xlink:href="`/storage/website-files/icons.svg#ri-close-line`" />
                                </svg>
                            </button>
                        </div>
                    </div>
                </template>
            </div>
        </template>
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
                    { id:0, field: 'id'},
                    { id:1, field: 'edit'},
                    { id:2, field: 'Organization' },
                    { id:3, field: 'Owner' },
                    { id:4, field: 'Email' },
                    { id:5, field: 'Members' },
                    { id:6, field: '' },
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
                .then( res => {
                    console.log(res.data);
                })
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