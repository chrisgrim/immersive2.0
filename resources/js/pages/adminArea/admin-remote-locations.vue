<template>
    <div class="admin-remote-locations">
        <div class="">
            <div class="title">
                <h1>Remote Locations</h1>
                <div class="add-button">
                    <button 
                        @click="onAdd=!onAdd"
                        class="add__icon" 
                        :class="{active: onAdd}">
                        <svg>
                            <use :xlink:href="`/storage/website-files/icons.svg#ri-add-fill`" />
                        </svg>
                    </button>
                </div>
            </div>
        </div>

        <div class="data-grid">
            <div class="data-grid__row header">
                <p>name</p>
                <p>description</p>
                <p>admin</p>
                <p>rank</p>
            </div>  
            <div 
                class="data-grid__row" 
                :key="location.id"
                v-for="(location) in remoteLocations">
                <input 
                    type="text" 
                    v-model="location.name" 
                    placeholder="Remote Location"
                    @blur="onUpdate(location)">
                <textarea
                    type="text" 
                    v-model="location.description" 
                    placeholder="Remote location description"
                    @blur="onUpdate(location)" />
                <select 
                    placeholder="Admin"
                    v-model="location.admin"
                    @change="onUpdate(location)">
                    <option value="1">True</option>
                    <option value="0">False</option>
                </select>
                <input 
                    type="text" 
                    v-model="location.rank" 
                    placeholder="Rank"
                    @blur="onUpdate(location)">
                <button 
                    @click.prevent="selectedModal=location" 
                    class="delete">
                    <svg>
                        <use :xlink:href="`/storage/website-files/icons.svg#ri-close-line`" />
                    </svg>
                </button>
            </div>
        </div>
        <AddRemoteLocation 
            @update="update"
            @close="onAdd=false"
            v-if="onAdd" />
        <VueDeleteModal 
            v-if="selectedModal"
            :item="selectedModal"
            :strict="true"
            body="You are deleting the category. Please be sure you know what you are doing."
            @close="selectedModal=null"
            @ondelete="onDelete" />
    </div>
</template>

<script>
    import VueDeleteModal from '../../components/modals/Vue-Modal-Delete'
    import AddRemoteLocation from './components/add-remote-location'

    export default {
        components: { AddRemoteLocation, VueDeleteModal },

        data() {
            return {
                remoteLocations: '',
                onAdd:false,
                selectedModal: '',
            }
        },

        methods: {
            async onDelete() {
                await axios.delete(`/remotelocations/${this.selectedModal.id}`)
                .then( res => { 
                    this.remoteLocations = res.data; 
                    this.selectedModal = false 
                })
            },
            async onLoad() {
                await axios.get('/remotelocations')
                .then( res => { this.remoteLocations = res.data })
            },
            async onUpdate(remoteLocation) {
                await axios.patch(`/remotelocations/${remoteLocation.id}`, remoteLocation)
                .then( res => { this.remoteLocations = res.data })
            },
            update(value) {
                this.remoteLocations = value
                this.onAdd = false
            }

        },

        created() {
            this.onLoad()
        },

    }
</script>