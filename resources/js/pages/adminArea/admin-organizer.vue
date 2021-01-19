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
        <div v-if="organizers && organizers.length">
            <div 
                class="list"
                v-for="(org) in organizers"
                :key="org.id">
                <div class="edit">
                    <a :href="`/organizer/${org.slug}/edit`">
                        <button class="default">Edit Organizer</button>
                    </a>
                </div>
                <div>
                    {{ org.name }}
                </div>
                <div>
                    {{ org.user.email }}
                </div>
                <div>
                    <button @click.prevent="showModal(org, 'changeOrg')">
                        Change Owner
                    </button>
                </div>
                <button 
                    @click.prevent="showModal(org, 'deleteOrg')" 
                    class="delete-circle">
                    <IconSvg type="delete" />
                </button>
            </div>
        </div>
        <VueDeleteModal 
            v-if="modal == 'deleteOrg'"
            :item="selectedModal"
            :strict="true"
            body="Deleting this organizer will delete all of the organizers events as well. Please be sure you know what you are doing."
            @close="modal = null"
            @ondelete="onDelete" />
        <modal 
            v-if="modal == 'changeOrg'" 
            @close="modal = null">
            <div slot="header">
                <div class="circle sub">
                    <p>!</p>
                </div>
            </div>
            <div slot="body"> 
                <h3>Change Organizer Owner</h3>
                <v-select 
                    v-model="user"
                    label="name"
                    placeholder="Enter User Name"
                    @search="onSearchUsers"
                    @search:focus="onSearchUsers"
                    :clearable="false"
                    :options="users" />
            </div>
            <div slot="footer">
                <button 
                    class="btn sub" 
                    @click="onChangeOwner()">
                    Change Owner 
                </button>
            </div>
        </modal>
    </div>
</template>

<script>
    
    import IconSvg from '../../components/Svg-icon'
    import VueDeleteModal from '../../components/Vue-Delete-Modal'

    export default {

        components: { IconSvg, VueDeleteModal },

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

            async onChangeOwner() {
                await axios.post(`/admin/organizer/${this.selectedModal.slug}/changeUser`, this.user)
                location.reload()
            },

            onSearchOrganizers (query) {
                axios.get('/api/admin/organizer/search', { params: { keywords: query } })
                .then( res => { this.organizers = res.data.data });
            },

            onSearchUsers (query) {
                axios.get('/api/admin/users/search', { params: { keywords: query } })
                .then(res => { this.users = res.data.data });
            },

        },

        created() {
            this.onLoad();
        },

    }

</script>