<template>
    <div 
        ref="table"
        style="width:100%"
        class="users">
        <div
            :style="`height:${(users.data.length * 32) + 64}px`"
            class="v-table">
            <div 
                style="height:32px;"
                class="v-header-pane v-pane">
                <div class="v-header v-row">
                    <div 
                        :style="`width:${col.width}px;left:${left(col.id)}px`"
                        :key="col.id"
                        v-for="col in cols"
                        class="v-cell">
                        <p>{{ col.field }}</p>
                        <div 
                            v-if="col.id < cols.length - 1"
                            class="v-resize" 
                            :class="{ active: active === col.id }"
                            :style="`left:${col.width}px`" 
                            @mousedown="startDrag(col)" 
                            @mousemove="doDrag($event, col)">
                            <div class="v-bar" />
                        </div>
                    </div>
                </div>
            </div>
            <div class="v-data-pane v-pane">
                <div 
                    :style="`top:${32*i}px`"
                    class="v-row"
                    :key="user.id"
                    v-for="(user, i) in users.data">
                    <div 
                        class="v-cell" 
                        :style="`width:${cols[0].width}px`">
                        <input 
                            type="text" 
                            v-model="user.name" 
                            placeholder="User Name"
                            @change="onUpdate(user)">
                    </div>
                    <div 
                        class="v-cell" 
                        :style="`width:${cols[1].width}px;left:${left(cols[1].id)}px`">
                        <p>{{ user.email }}</p>
                    </div>
                    <div 
                        class="v-cell" 
                        :style="`width:${cols[2].width}px;left:${left(cols[2].id)}px`">
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
                    </div>
                    <div 
                        class="v-cell" 
                        :style="`width:${cols[3].width}px;left:${left(cols[3].id)}px`">
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

        computed: {
            combinedWidth() {
                return this.cols.map( col => col.x ).reduce((partial_sum, a) => partial_sum + a,0)
            },
        },

        data() {
            return {
                users: [],
                userList: [],
                searchUserOptions: [],
                selectedModal: null,
                cols: [
                    { id:0, width: 0, left: 0, ratio: 32, field: 'name'},
                    { id:1, width: 0, left: 0, ratio: 32, field: 'email' },
                    { id:2, width: 0, left: 0, ratio: 32, field: 'status' },
                    { id:3, width: 0, left: 0, ratio: 4, field: '' },
                ],
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
            startDrag(col) {
                this.dragging = true
                this.active = col.id
            },
            stopDrag() {
                this.dragging = false
                this.active = null
            },
            doDrag(event, col) {
                if (this.dragging) {
                    var sum = 0;
                    for (let i = 0; i < col.id; i++) {
                        sum += this.cols[i].width
                    }
                    col.width = event.clientX - (300 + sum);

                }
            },
            left(val) {
                var sum = 0;
                for (let i = 0; i < val; i++) {
                    sum += this.cols[i].width;
                }
                return sum
            },
            getWidth() {
                const table = this.$refs.table.clientWidth
                this.cols.map( col => col.width = table * (col.ratio/100) )
            },
            getLeft() {
                let columns = this.cols
                this.cols.map(function(col) {
                    var sum = 0;
                    for (let i = 0; i < col.id; i++) {
                        sum += columns[i].width;
                    }
                    return col.left = sum
                })
            }
        },

        mounted() {
            window.addEventListener('mouseup', this.stopDrag);
            this.getWidth()
            this.getLeft()
        },

        created() {
            this.onLoad()
        },

    }

</script>