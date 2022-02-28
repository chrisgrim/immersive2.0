<template>
    <div>
        <div class="flex justify-between mb-8 items-center">
            <h2>Content Advisories</h2>
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
                                    <p class="text-xl">Advisory</p>
                                </th>
                                <th class="border-b dark:border-slate-600 font-medium p-4 pl-8 pt-0 pb-3 text-slate-400 dark:text-slate-200 text-left">
                                    <p class="text-xl">Rank</p>
                                </th>
                                <th class="border-b dark:border-slate-600 font-medium p-4 pl-8 pt-0 pb-3 text-slate-400 dark:text-slate-200 text-left" />
                            </tr>
                        </thead>
                        <tbody class="bg-white dark:bg-slate-800">
                            <template v-if="contentAdvisories && contentAdvisories.length">
                                <tr
                                    :key="advisory.id"
                                    v-for="(advisory) in contentAdvisories">
                                    <td class="border-b border-slate-100 dark:border-slate-700 p-4 pl-8 text-slate-500 dark:text-slate-400">
                                        <input 
                                            type="text" 
                                            v-model="advisory.advisories" 
                                            class="w-full p-2" 
                                            placeholder="Advisory"
                                            @blur="onUpdate(advisory)">
                                    </td>
                                    <td class="border-b border-slate-100 dark:border-slate-700 p-4 pl-8 text-slate-500 dark:text-slate-400">
                                        <input 
                                            type="text" 
                                            v-model="advisory.rank"
                                            class="w-full p-2" 
                                            placeholder="Advisory"
                                            @blur="onUpdate(advisory)">
                                    </td>
                                    <td class="border-b border-slate-100 dark:border-slate-700 p-4 pl-8 text-slate-500 dark:text-slate-400">
                                        <button 
                                            @click.prevent="selectedModal=advisory" 
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
        <AddContentAdvisory 
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
    import AddContentAdvisory from './Components/content-advisory-component'

    export default {
        components: { AddContentAdvisory, VueDeleteModal },

        data() {
            return {
                contentAdvisories: '',
                selectedModal: null,
                onAdd: false,
            }
        },

        methods: {
            async onDelete() {
                await axios.delete(`/contentadvisories/${this.selectedModal.id}`)
                .then(res => { 
                    this.contentAdvisories = res.data; 
                    this.selectedModal = null 
                })
            },
            async onLoad() {
                await axios.get('/contentadvisories')
                .then( res => { this.contentAdvisories = res.data })
            },
            async onUpdate(advisory) {
                await axios.patch(`/contentadvisories/${advisory.id}`, advisory)
                .then( res => { this.contentAdvisories = res.data })
            },
            update(value) {
                this.contentAdvisories = value
                this.onAdd = false
            }
        },

        created() {
            this.onLoad()
        },

    }

</script>