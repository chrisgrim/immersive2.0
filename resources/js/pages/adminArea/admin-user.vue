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
                :key="user.id"
                v-for="(user) in users.data">
                <input 
                    type="text" 
                    v-model="user.name" 
                    placeholder="User Name"
                    @change="onUpdate(user)">
                <p>{{ user.email }}</p>
                <select 
                    v-model="user.type" 
                    placeholder="User Type"
                    @change="onUpdate(user)">
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
                    @click.prevent="selectedModal=user" 
                    class="delete">
                    <svg>
                        <use :xlink:href="`/storage/website-files/icons.svg#ri-close-line`" />
                    </svg>
                </button>
            </div>
        </div>
        <pagination 
            :limit="1"
            :list="users"
            @selectpage="onLoad" />
        <VueDeleteModal 
            v-if="selectedModal"
            :item="selectedModal"
            :strict="true"
            body="You are deleting the user. Please be sure you know what you are doing."
            @close="selectedModal=null"
            @ondelete="onDelete" />
    </div>
</template>

<script>
    import Pagination  from '../../components/pagination.vue'
    import VueDeleteModal from '../../components/modals/Vue-Modal-Delete'

    export default {
        components: { Pagination, VueDeleteModal },

        data() {
            return {
                users: [],
                userList: [],
                searchUserOptions: [],
                selectedModal: null,
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
                .then( res => { this.users = res.data })
            },

            createList() {
                this.searchUserOptions = this.userlist;
            },
        },

        created() {
            this.onLoad()
        },

    }

</script>