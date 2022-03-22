<template>
    <div>
        <div class="flex justify-between mb-8 items-center">
            <h2>Interactive Levels</h2>
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
                                <th class="border-b dark:border-slate-600 font-medium p-4 pl-8 pt-0 pb-3 text-slate-400 dark:text-slate-200 text-left">
                                    <p class="text-xl">Name</p>
                                </th>
                                <th class="border-b dark:border-slate-600 font-medium p-4 pl-8 pt-0 pb-3 text-slate-400 dark:text-slate-200 text-left">
                                    <p class="text-xl">Descriptio </p>
                                </th>
                                <th class="border-b dark:border-slate-600 font-medium p-4 pl-8 pt-0 pb-3 text-slate-400 dark:text-slate-200 text-left">
                                    <p class="text-xl">Rank</p>
                                </th>
                                <th class="border-b dark:border-slate-600 font-medium p-4 pl-8 pt-0 pb-3 text-slate-400 dark:text-slate-200 text-left" />
                            </tr>
                        </thead>
                        <tbody class="bg-white dark:bg-slate-800">
                            <template v-if="interactiveLevels && interactiveLevels.length">
                                <tr
                                    :key="level.id"
                                    v-for="(level) in interactiveLevels">
                                    <td class="border-b border-slate-100 dark:border-slate-700 p-4 pl-8 text-slate-500 dark:text-slate-400">
                                        <input 
                                            type="text" 
                                            v-model="level.name" 
                                            class="w-full p-2" 
                                            placeholder="Level"
                                            @blur="onUpdate(level)">
                                    </td>
                                    <td class="border-b border-slate-100 dark:border-slate-700 p-4 pl-8 text-slate-500 dark:text-slate-400">
                                        <textarea
                                            type="text" 
                                            v-model="level.description" 
                                            placeholder="Interactive Level description"
                                            @blur="onUpdate(level)" />
                                    </td>
                                    <td class="border-b border-slate-100 dark:border-slate-700 p-4 pl-8 text-slate-500 dark:text-slate-400">
                                        <input 
                                            type="text" 
                                            v-model="level.rank" 
                                            class="w-full p-2" 
                                            placeholder="Level"
                                            @blur="onUpdate(level)">
                                    </td>
                                    <td class="border-b border-slate-100 dark:border-slate-700 p-4 pl-8 text-slate-500 dark:text-slate-400">
                                        <button 
                                            @click.prevent="selectedModal=level" 
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
        <AddInteractiveLevel 
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
    import AddInteractiveLevel from './Components/interactive-level-component'

    export default {
        components: { AddInteractiveLevel, VueDeleteModal },


        data() {
            return {
                interactiveLevels: '',
                selectedModal: '',
                onAdd: false,
            }
        },

        methods: {
            async onDelete() {
                await axios.delete(`/interactivelevels/${this.selectedModal.id}`)
                .then( res => { 
                    this.interactiveLevels = res.data; 
                    this.selectedModal = null 
                })
            },
            async onLoad() {
                await axios.get('/interactivelevels')
                .then( res => { this.interactiveLevels = res.data })
            },
            async onUpdate(interactiveLevel) {
                await axios.patch(`/interactivelevels/${interactiveLevel.id}`, interactiveLevel)
                .then(res => { this.interactiveLevels = res.data })
            },
            update(value) {
                this.interactiveLevels = value
                this.onAdd = false
            }
        },

        mounted() {
            this.onLoad()
        },

    }

</script>