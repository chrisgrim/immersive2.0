<template>
    <div>
        <h2 class="mb-8">Boneyard</h2>
        <div class="field">
            <input 
                v-model="eventList"
                placeholder="Filter by event name" 
                class="general"
                @keyup="onSearch(eventList)"
                type="text">
        </div>
        <div class="not-prose relative bg-slate-50 rounded-xl overflow-hidden dark:bg-slate-800/25">
            <div class="relative rounded-xl overflow-auto border">
                <div class="shadow-sm overflow-hidden my-4">
                    <table class="table-auto border-collapse w-full text-sm">
                        <thead>
                            <tr>
                                <th class="border-b dark:border-slate-600 font-medium p-4 pl-8 pt-0 pb-3 text-slate-400 dark:text-slate-200 text-left">
                                    <p>Name</p>
                                </th>
                                <th class="border-b dark:border-slate-600 font-medium p-4 pl-8 pt-0 pb-3 text-slate-400 dark:text-slate-200 text-left">
                                    <p>Revive</p>
                                </th>
                            </tr>
                        </thead>
                        <tbody class="bg-white dark:bg-slate-800">
                            <tr 
                                :key="event.id"
                                v-for="event in events.data">
                                <td class="border-b border-slate-100 dark:border-slate-700 p-4 pl-8 text-slate-500 dark:text-slate-400">
                                    <a 
                                        target="_blank"
                                        :href="`/create/${event.slug}/title`">
                                        <div class="flex items-center">
                                            <img 
                                                v-if="event.thumbImagePath"
                                                class="w-60 rounded-xl mr-4"
                                                :src="`${$envImageUrl}${event.thumbImagePath}`">
                                            <p class="text-xl">{{ event.name }}</p>
                                        </div>
                                    </a>
                                </td>
                                <td class="border-b border-slate-100 dark:border-slate-700 p-4 pl-8 text-slate-500 dark:text-slate-400">
                                    <button 
                                        @click="onResurrect(event)"
                                        class="px-4 py-2 text-xl rounded-full bg-black border border-black text-white hover:bg-white hover:text-black">
                                        Resurrect
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
            :list="events"
            @selectpage="onLoad" />
    </div>
</template>

<script>
    import Pagination  from '../../../components/pagination.vue'
    export default {

        components: { Pagination },

        data() {
            return {
                events: this.loadedevents ? this.loadedevents : [],
                eventList: '',
            }
        },

        methods: {
            onSearch(eventList) {
                axios.get('/api/admin/event/deleted/search', { params: { keywords: eventList } })
                .then( res => { this.events = res.data })
            },
            onLoad(page) {
                axios.post(`/admin/event/boneyard/fetch?page=${page}`)
                .then( res => { this.events = res.data })
            },
            async onResurrect(event) {
                await axios.post(`/admin/event/boneyard/${event.id}/resurrect`)
                this.onLoad();
            }   
        },

        created() {
            this.onLoad()
        },

    }

</script>