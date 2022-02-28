<template>
    <div>
        <div class="flex justify-between mb-8 items-center">
            <h2>Remote Locations</h2>
            <button 
                @click="onAdd=!onAdd"
                class="border-none w-20 h-20 flex p-0 rounded-full justify-center items-center hover:bg-slate-300">
                <svg class="w-16 h-16">
                    <use :xlink:href="`/storage/website-files/icons.svg#ri-add-fill`" />
                </svg>
            </button>
        </div>

        <div class="not-prose relative bg-slate-50 rounded-xl overflow-hidden dark:bg-slate-800/25">
            <div class="relative rounded-xl overflow-auto border">
                <div class="shadow-sm overflow-hidden my-4">
                    <table class="table-auto border-collapse w-full">
                        <thead>
                            <tr>
                                <th 
                                    :key="col.id"
                                    v-for="col in cols"
                                    class="border-b dark:border-slate-600 font-medium p-4 pl-8 pt-0 pb-3 text-slate-400 dark:text-slate-200 text-left">
                                    <p 
                                        class="text-xl" 
                                        :class="col.class">{{ col.field }}</p>
                                </th>
                            </tr>
                        </thead>
                        <tbody class="bg-white dark:bg-slate-800">
                            <template v-if="remoteLocations && remoteLocations.length">
                                <tr
                                    v-for="(location) in remoteLocations"
                                    :key="location.id">
                                    <td class="border-b border-slate-100 dark:border-slate-700 p-4 pl-8 text-slate-500 dark:text-slate-400">
                                        <input 
                                            type="text"
                                            class="w-full p-2" 
                                            v-model="location.name"
                                            placeholder="Remote Location"
                                            @blur="onUpdate(location)">
                                    </td>
                                    <td class="border-b border-slate-100 dark:border-slate-700 p-4 pl-8 text-slate-500 dark:text-slate-400">
                                        <textarea
                                            type="text" 
                                            v-model="location.description" 
                                            placeholder="Remote location description"
                                            @blur="onUpdate(location)" />
                                    </td>
                                    <td class="border-b border-slate-100 dark:border-slate-700 p-4 pl-8 text-slate-500 dark:text-slate-400">
                                        <select 
                                            class="text-xl px-4 py-2 rounded-xl hover:bg-slate-500 hover:text-white" 
                                            placeholder="Admin"
                                            v-model="location.admin"
                                            @change="onUpdate(location)">
                                            <option value="1">True</option>
                                            <option value="0">False</option>
                                        </select>
                                    </td>
                                    <td class="border-b border-slate-100 dark:border-slate-700 p-4 pl-8 text-slate-500 dark:text-slate-400">
                                        <input 
                                            type="text"
                                            class="w-full p-2" 
                                            v-model="location.rank"
                                            placeholder="Rank"
                                            @blur="onUpdate(location)">
                                    </td>
                                    <td class="border-b border-slate-100 dark:border-slate-700 p-4 pl-8 text-slate-500 dark:text-slate-400">
                                        <button 
                                            @click.prevent="selectedModal=location" 
                                            class="rounded-full p-2 hover:bg-black hover:border-black">
                                            <svg class="w-8 h-8 hover:fill-white">
                                                <use :xlink:href="`/storage/website-files/icons.svg#ri-close-line`" />
                                            </svg>
                                        </button>
                                    </td>
                                </tr>
                            </template>
                        </tbody>
                    </table>
                </div>
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
    import VueDeleteModal from '../../../components/modals/Vue-Modal-Delete'
    import AddRemoteLocation from './Components/remote-location-component'

    export default {
        components: { AddRemoteLocation, VueDeleteModal },

        data() {
            return {
                remoteLocations: '',
                onAdd:false,
                selectedModal: '',
                cols: [
                    { id:0, field: 'name', class:''},
                    { id:1, field: 'description', class:''},
                    { id:2, field: 'admin', class:'' },
                    { id:3, field: 'rank', class:'' },
                    { id:4, field: '', class:'' },
                ],
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