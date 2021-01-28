<template>
    <div class="organizers">
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
                @keyup="onSearchOrganizers(organizer)"
                type="text">
        </div>
        <template v-if="organizers && organizers.length">
            <div class="data-grid">
                <div class="data-grid__row header">
                    <p></p>
                    <p>Organization</p>
                    <p>Owner</p>
                    <p>Members</p>
                    <p></p>
                </div>
                <div 
                    class="data-grid__row"
                    v-for="(org) in organizers"
                    :key="org.id">
                    <div class="edit">
                        <a :href="`/organizer/${org.slug}/edit`">
                            <IconSvg type="edit" />
                        </a>
                    </div>
                    <div>
                        <img :src="`/storage/${org.thumbImagePath}`">
                        <p>{{ org.name }}</p>
                    </div>
                    <div>
                        <button 
                            class="noBox" 
                            @click.prevent="showModal(org, 'changeOwner')">
                            <p>{{ org.user.name }}</p>
                        </button>
                    </div>
                    <div>
                        <v-select 
                            v-model="org.users"
                            label="name"
                            placeholder="Members"
                            multiple
                            @search="onSearchUsers"
                            @input="addNewMember($event, org)"
                            :clearable="false"
                            :options="users.length ? users : org.users" />
                    </div>
                    <button 
                        @click.prevent="showModal(org, 'deleteOrg')" 
                        class="delete">
                        <IconSvg type="delete" />
                    </button>
                </div>
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
    
    import IconSvg from '../../components/Svg-icon'
    import VueDeleteModal from '../../components/Vue-Delete-Modal'
    import VueDataModal from '../../components/Vue-Data-Modal'

    export default {

        components: { IconSvg, VueDeleteModal, VueDataModal },

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

            onSearchUsers (query) {
                axios.get('/api/admin/users/search', { params: { keywords: query } })
                .then(  res => { 
                    this.users = res.data.data 
                    console.log(res.data.data);
                });
            },

        },

        created() {
            this.onLoad();
        },

    }

</script>