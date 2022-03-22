<template>
    <div>
        <div class="flex justify-between mb-8 items-center">
            <h2>Contact Levels</h2>
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
                                    <p class="text-xl">Rank</p>
                                </th>
                                <th class="border-b dark:border-slate-600 font-medium p-4 pl-8 pt-0 pb-3 text-slate-400 dark:text-slate-200 text-left" />
                            </tr>
                        </thead>
                        <tbody class="bg-white dark:bg-slate-800">
                            <template v-if="contactLevels && contactLevels.length">
                                <tr
                                    :key="level.id"
                                    v-for="(level) in contactLevels">
                                    <td class="border-b border-slate-100 dark:border-slate-700 p-4 pl-8 text-slate-500 dark:text-slate-400">
                                        <input 
                                            type="text" 
                                            v-model="level.level"
                                            class="w-full p-2" 
                                            placeholder="Genre"
                                            @blur="onUpdate(level)">
                                    </td>
                                    <td class="border-b border-slate-100 dark:border-slate-700 p-4 pl-8 text-slate-500 dark:text-slate-400">
                                        <input 
                                            type="text" 
                                            v-model="level.rank"
                                            class="w-full p-2" 
                                            placeholder="Genre"
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
        <AddContactLevel 
            @update="update"
            @close="onAdd=false"
            v-if="onAdd" />
        <VueDeleteModal 
            v-if="selectedModal"
            :item="selectedModal"
            :strict="true"
            :body="`You are deleting ${selectedModal.level}`"
            @close="selectedModal=null"
            @ondelete="onDelete" />
    </div>
</template>

<script>
    import VueDeleteModal from '../../../components/modals/Vue-Modal-Delete'
    import AddContactLevel from './Components/contact-level-component'

    export default {

        components: { VueDeleteModal, AddContactLevel },

        data() {
            return {
                contactLevels: '',
                onAdd: false,
                selectedModal: null,
            }
        },

        methods: {
            async onDelete() {
                await axios.delete(`/contactlevels/${this.selectedModal.id}`)
                .then( res => { 
                    this.contactLevels = res.data 
                    this.selectedModal = null
                })
            },
            async onLoad() {
                await axios.get('/contactlevels')
                .then( res => { this.contactLevels = res.data })
            },
            async onUpdate(level) {
                await axios.patch(`/contactlevels/${level.id}`, level )
                .then( res => { this.contactLevels = res.data })
            },
            update(value) {
                this.contactLevels = value
                this.onAdd = false
            }
        },

        mounted() {
            this.onLoad()
        },
    }

</script>