<template>
    <div class="users">
        <div class="">
            <div class="title">
                <h1>Users</h1>
            </div>
        </div>
        <div class="field">
            <input 
                v-model="userList"
                placeholder="Filter by name" 
                class="general"
                @keyup="onSearch(userList)"
                type="text">
        </div>
        <div class="data-grid">
            <div class="data-grid__row header">
                <p>Name</p>
                <p>Email</p>
                <p>Status</p>
            </div>
            <div 
                class="data-grid__row" 
                :key="item.id"
                v-for="(item) in users.data">
                <input 
                    type="text" 
                    v-model="item.name" 
                    placeholder="User Name"
                    @change="onUpdate(item)">
                <input 
                    type="text" 
                    v-model="item.email" 
                    placeholder="Email"
                    @change="onUpdate(item)">
                <select 
                    v-model="item.type" 
                    placeholder="User Type"
                    @change="onUpdate(item)">
                    <option value="a">
                        Admin
                    </option>
                    <option value="m">
                        Moderator
                    </option>
                    <option value="g">
                        Guest
                    </option>
                </select>
                <button 
                    @click.prevent="showModal(item, 'delete')" 
                    class="delete">
                    <IconSvg type="delete" />
                </button>
            </div>
        </div>
        <pagination 
            :limit="1"
            :list="users"
            @selectpage="onLoad" />
        <VueDeleteModal 
            v-if="modal == 'delete'"
            :item="selectedModal"
            :strict="true"
            body="You are deleting the user. Please be sure you know what you are doing."
            @close="modal = null"
            @ondelete="onDelete" />
    </div>
</template>

<script>

    import IconSvg from '../../components/Svg-icon'
    import Pagination  from '../../components/pagination.vue'
    import VueDeleteModal from '../../components/Vue-Delete-Modal'

    export default {

        components: { IconSvg, Pagination, VueDeleteModal },

        data() {
            return {
                users: [],
                userList: [],
                searchUserOptions: [],
                regionActive: false,
                user: '',
                isModalVisible: false,
                modal: false,
                selectedModal: '',
                isLoading: '',
            }
        },


        methods: {

            async onDelete() {
                await axios.delete(`/admin/users/${this.selectedModal.id}/delete`)
                location.reload();
            },

            onLoad(page) {
                axios.post(`/admin/users/fetch?page=${page}`)
                .then( res => { this.users = res.data })
            },

            async onUpdate(user) {
                await axios.patch(`/admin/users/${user.id}`, user)
                .then( res => { this.users = res.data; })
            },

            onSearch(query) {
                axios.get('/api/admin/users/search', { params: { keywords: query } })
                .then( res => { 
                    this.users = res.data 
                })
            },

            createList() {
                this.searchUserOptions = this.userlist;
            },

            showModal(user, arr) {
                this.selectedModal = user;
                this.modal = arr;
            }, 
        },

        created() {
            this.onLoad()
        },

    }

</script>