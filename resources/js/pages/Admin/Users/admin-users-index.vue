<template>
    <div>
        <h2 class="mb-8">Users</h2>
        <div class="field">
            <input 
                v-model="userList"
                placeholder="Filter by name" 
                class="general"
                @keyup="debounce(userList)"
                type="text">
        </div>
        <div class="not-prose relative bg-slate-50 rounded-xl overflow-hidden dark:bg-slate-800/25">
            <div class="relative rounded-xl overflow-auto border">
                <div class="shadow-sm overflow-hidden my-4">
                    <table class="table-auto border-collapse w-full text-sm">
                        <thead>
                            <tr>
                                <th 
                                    :key="col.id"
                                    class="border-b dark:border-slate-600 font-medium p-4 pl-8 pt-0 pb-3 text-slate-400 dark:text-slate-200 text-left" 
                                    v-for="col in cols">
                                    <p>{{ col.field }}</p>
                                </th>
                            </tr>
                        </thead>
                        <tbody class="bg-white dark:bg-slate-800">
                            <tr 
                                :key="user.id"
                                v-for="user in users.data">
                                <td class="border-b border-slate-100 dark:border-slate-700 p-4 pl-8 text-slate-500 dark:text-slate-400">
                                    <a :href="`/users/${user.id}`">{{user.id}}</a>
                                </td>
                                <td class="border-b border-slate-100 dark:border-slate-700 p-4 pl-8 text-slate-500 dark:text-slate-400">
                                    <input 
                                        type="text" 
                                        v-model="user.name" 
                                        class="text-xl px-4 py-2 rounded-xl hover:bg-slate-500 hover:text-white" 
                                        placeholder="User Name"
                                        @change="onUpdate(user)">
                                </td>
                                <td class="border-b border-slate-100 dark:border-slate-700 p-4 pl-8 text-slate-500 dark:text-slate-400 truncate">
                                    <p class="text-xl">{{ user.email }}</p>
                                </td>
                                <td class="border-b border-slate-100 dark:border-slate-700 p-4 pl-8 text-slate-500 dark:text-slate-400">
                                    <select 
                                        class="text-xl px-4 py-2 rounded-xl hover:bg-slate-500 hover:text-white" 
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
                                </td>
                                <td class="border-b border-slate-100 dark:border-slate-700 p-4 pl-8 text-slate-500 dark:text-slate-400">
                                    <button 
                                        @click.prevent="selectedModal=user" 
                                        class="rounded-full p-2 hover:bg-black hover:border-black">
                                        <svg class="w-8 h-8 hover:fill-white">
                                            <use :xlink:href="`/storage/website-files/icons.svg#ri-close-line`" />
                                        </svg>
                                    </button>
                                </td>
                            </tr>
                        </tbody>
                    </table>
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
    import Pagination  from '../../../components/pagination.vue'
    import VueDeleteModal from '../../../components/modals/Vue-Modal-Delete'

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