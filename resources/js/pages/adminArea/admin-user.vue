<template>
    <div 
        ref="table"
        style="width:100%"
        class="users">
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
                @keyup="debounce(userList)"
                type="text">
        </div>

        <div class="v-table">
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
            <div class="v-data-pane v-pane">
                <div 
                    class="v-row"
                    :key="user.id"
                    :style="columns"
                    v-for="user in users.data">
                    <div class="v-cell">
                        <p>{{user.id}}</p>
                    </div>
                    <div class="v-cell">
                        <input 
                            type="text" 
                            v-model="user.name" 
                            placeholder="User Name"
                            @change="onUpdate(user)">
                    </div>
                    <div class="v-cell">
                        <p>{{ user.email }}</p>
                    </div>
                    <div class="v-cell">
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
                            <option value="c">
                                Curator
                            </option>
                            <option value="g">
                                Guest
                            </option>
                        </select>
                    </div>
                    <div class="v-cell">
                        <button 
                            @click.prevent="selectedModal=user" 
                            class="delete">
                            <svg>
                                <use :xlink:href="`/storage/website-files/icons.svg#ri-close-line`" />
                            </svg>
                        </button>
                    </div>
                </div>
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
                cols: [
                    { id:0, field: 'id'},
                    { id:1, field: 'name'},
                    { id:2, field: 'email' },
                    { id:3, field: 'status' },
                    { id:4, field: '' },
                ],
                columns: `grid-auto-columns: 5rem 0.6fr 1fr 1fr 5rem;`,
                dragging: false,
                active: null,
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
            debounce(query) {
                if (this.timeout) 
                    clearTimeout(this.timeout); 
                this.timeout = setTimeout(() => {
                    this.onSearch(query);
                }, 200); // delay
            },

        },

        created() {
            this.onLoad()
        },

    }

</script>