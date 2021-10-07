<template>
    <div class="admin-docks">
        <div class="">
            <div class="title">
                <h1>Docks</h1>
                <div class="add-button">
                    <button 
                        @click="onCreate"
                        class="add__icon">
                        <svg>
                            <use :xlink:href="`/storage/website-files/icons.svg#ri-add-fill`" />
                        </svg>
                    </button>
                </div>
            </div>
        </div>

        <template v-if="docks && docks.length">
            <div class="data-grid">
                <div class="data-grid__row header">
                    <p>Dock Location</p>
                    <p>Dock Type</p>
                    <p>Dock Name</p>
                    <p>Content</p>
                    <p>Rank</p>
                    <p>Update</p>
                </div>
                <div 
                    class="data-grid__row"
                    v-for="dock in docks"
                    :key="dock.id">
                    <div>
                        <select 
                            v-model="dock.location" 
                            placeholder="Location"
                            @change="onUpdate(dock)">
                            <option value="none"> None </option>
                            <option value="home"> Home </option>
                            <option value="search"> Search </option>
                        </select>
                    </div>
                    <div>
                        <select 
                            v-model="dock.type" 
                            placeholder="Type"
                            @change="onUpdate(dock)">
                            <option value="f"> Four </option>
                            <option value="t"> Three </option>
                            <option value="i"> Icon </option>
                            <option value="h"> Hero </option>
                            <option value="s"> Spotlight </option>
                        </select>
                    </div>
                    <div>
                        <input 
                            type="text" 
                            v-model="dock.name"
                            @blur="onUpdate(dock)"
                            placeholder="Dock Name">
                    </div>
                    <div>
                        <template v-if="dock.posts.length">
                            <h4>Post</h4>
                            <div 
                                :key="`p-${post.id}`"
                                v-for="post in dock.posts">
                                <p> {{ post.name}} </p>
                            </div>
                        </template>
                        <template v-if="dock.shelves.length">
                            <h4>Shelf</h4>
                            <div 
                                :key="`s-${shelf.id}`"
                                v-for="shelf in dock.shelves">
                                <p> {{ shelf.name}} </p>
                            </div>
                        </template>
                        <template v-if="dock.communities.length">
                            <h4>Community</h4>
                            <div 
                                :key="`c-${community.id}`"
                                v-for="community in dock.communities">
                                <p> {{ community.name}} </p>
                            </div>
                        </template>
                    </div>
                    <div>
                        <div class="field">
                            <select 
                                v-model="dock.order"
                                @change="onUpdate(dock)"
                                placeholder="Leave blank for default Rank of 5 (1 being most important)">
                                <option 
                                    v-for="pick in ['0', '1', '2', '3', '4', '5', '6', '7', '8', '9']"
                                    :key="pick">
                                    {{ pick }}
                                </option>
                            </select>
                        </div>
                    </div>
                    <div>
                        <button @click="modalAdd=dock">
                            Add Content
                        </button>
                    </div>
                    <button 
                        @click.prevent="modalDelete=dock" 
                        class="delete">
                        <svg>
                            <use :xlink:href="`/storage/website-files/icons.svg#ri-close-line`" />
                        </svg>
                    </button>
                </div>
            </div>
        </template>
        <AddModal
            :dock="modalAdd"
            @update="onRefresh"
            @close="modalAdd=null"
            v-if="modalAdd" />
        <VueDeleteModal 
            v-if="modalDelete"
            :item="modalDelete"
            :strict="true"
            body="You are deleting the Dock. Please be sure you know what you are doing."
            @close="modalDelete=null"
            @ondelete="onDelete" />
    </div>
</template>

<script>
    import VueDeleteModal from '../../components/modals/Vue-Modal-Delete'
    import AddModal from './components/add-modal'
    export default {

        components: { VueDeleteModal, AddModal },

        props: ['loaddocks'],

        data() {
            return {
                feature: null,
                options: [],
                docks: this.loaddocks,
                modalDelete: null,
                modalAdd: false,
            }
        },

        methods: {
            async onUpdate(dock) {
                await axios.post(`/admin/docks/${dock.id}`, dock)
                .then( res => {
                    this.docks = res.data
                })
            },
            async onCreate() {
                await axios.post(`/admin/docks`)
                .then( res => {
                    console.log(res.data);
                    this.docks = res.data
                })
            },
            async onDelete() {
                await axios.delete(`/admin/docks/${this.modalDelete.id}`)
                .then( res => {
                    this.docks = res.data
                    this.modalDelete = null
                })
            },
            async addShelf(value, dock) {
                await axios.post(`/admin/docks/${dock.id}/shelf/${value.model.id}`)
                .then( res => {
                    console.log(res.data);
                    // this.docks = res.data
                    // this.feature = null
                })
            },
            onRefresh(val) {
                this.docks = val
                this.modalAdd = null
            }
        },

    }

</script>