<template>
    <div>
        <h2 class="mb-8">Events</h2>
        <select 
            v-model="dateFilter"
            id="dates">
            <option value="published">Published At</option>
            <option value="updated">Updated At</option>
        </select>
        <div class="field">
            <input 
                v-model="eventList"
                placeholder="Filter by event name" 
                class="general"
                @keyup="debounce(eventList)"
                type="text">
        </div>

        <div class="not-prose relative bg-slate-50 rounded-xl overflow-hidden dark:bg-slate-800/25">
            <div class="relative rounded-xl overflow-auto border">
                <div class="shadow-sm overflow-hidden my-8">
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
                            <tr
                                :key="event.id"
                                v-for="event in events.data">
                                <td class="border-b border-slate-100 dark:border-slate-700 p-4 pl-8 text-slate-500 dark:text-slate-400">
                                    <a 
                                        target="_blank"
                                        class="rounded-full w-12 h-12 block flex items-center justify-center hover:bg-black" 
                                        :href="`/create/${event.slug}/title`">
                                        <svg class="w-8 h-8 hover:fill-white">
                                            <use :xlink:href="`/storage/website-files/icons.svg#ri-edit-2-line`" />
                                        </svg>
                                    </a>
                                </td>
                                <td class="border-b border-slate-100 dark:border-slate-700 p-4 pl-8 text-slate-500 dark:text-slate-400">
                                    <a 
                                        class="flex flex-col items-center" 
                                        target="_blank"
                                        :href="`/events/${event.slug}`">
                                        <img 
                                            class="w-32 rounded-2xl" 
                                            v-if="event.thumbImagePath"
                                            :src="`/storage/${event.thumbImagePath}`">
                                        <p class="text-xl text-center">{{ event.name }}</p>
                                    </a>
                                </td>
                                <td class="border-b border-slate-100 dark:border-slate-700 p-4 pl-8 text-slate-500 dark:text-slate-400">
                                    <p 
                                        class="underline text-xl"
                                        @click.prevent="showModal(event, 'changeOrganizer')">
                                        {{ event.organizer.name }}
                                    </p>
                                </td>
                                <td class="border-b border-slate-100 dark:border-slate-700 p-4 pl-8 text-slate-500 dark:text-slate-400">
                                    <p 
                                        class="text-xl" 
                                        v-if="event.hasLocation">{{ event.location.city ? event.location.city : event.location.region }}, {{ event.location.country }}</p>
                                    <p 
                                        class="text-xl" 
                                        v-else>Online</p>
                                </td>
                                <td class="border-b border-slate-100 dark:border-slate-700 p-4 pl-8 text-slate-500 dark:text-slate-400">
                                    <p class="text-xl">{{ event.category.name }}</p>
                                </td>
                                <td class="border-b border-slate-100 dark:border-slate-700 p-4 pl-8 text-slate-500 dark:text-slate-400">
                                    <a 
                                        target="_blank"
                                        class="text-xl" 
                                        :href="`/admin/events/show/${event.slug}`">
                                        <IconSvg type="book" />
                                    </a>
                                </td>
                                <td class="border-b border-slate-100 dark:border-slate-700 p-4 pl-8 text-slate-500 dark:text-slate-400">
                                    <a 
                                        v-if="event.status === 'p'"
                                        target="_blank"
                                        :href="`/events/${event.slug}`">
                                        <p style="text-decoration: underline;">Live</p>
                                    </a>
                                    <p 
                                        v-if="event.status === 'e'"
                                        style="text-decoration: underline;">Embargoed <br> (goes live {{ cleanDate(event.embargo_date) }})
                                    </p>
                                </td>
                                <td class="border-b border-slate-100 dark:border-slate-700 p-4 pl-8 text-slate-500 dark:text-slate-400">
                                    <p v-if="event.clicks">{{ event.clicks.length }}</p>
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
        <VueDataModal 
            v-if="modal == 'changeOrganizer'"
            @onSubmit="changeEventOrganization"
            search="organizer"
            @close="modal = null"
            :item="modalData">
            <h3>Change {{ modalData.name }} Event Organizer</h3>
            <p>Current organizer is {{ modalData.organizer.name }}</p>
        </VueDataModal>
    </div>
</template>

<script>
    import Pagination  from '../../components/pagination.vue'
    import IconSvg from '../../components/Svg-icon'
    import VueDataModal from '../../components/modals/Vue-Data-Modal'

    export default {

        components: { Pagination, IconSvg, VueDataModal },

        data() {
            return {
                events: [],
                eventList: '',
                organizer: '',
                organizers: [],
                modalData: null,
                modal: '',
                page:1,
                dateFilter: 'published',
                cols: [
                    { id:0, field: '', class:''},
                    { id:1, field: 'Name', class:'text-center'},
                    { id:2, field: 'Organization', class:''},
                    { id:3, field: 'Location', class:'' },
                    { id:4, field: 'Category', class:''},
                    { id:5, field: 'Sum.',class:'' },
                    { id:6, field: 'Status', class:'' },
                    { id:7, field: 'Clicks', class:'' },
                ],
            }
        },

        methods: {
            async onLoad(page) {
                await axios.get(`/admin/events/fetch?page=${page}&date=${this.dateFilter}`)
                .then( res => { 
                    this.events = res.data 
                })
            },
            async onSearch(events) {
                await axios.get('/api/admin/events/search', { params: { keywords: events } })
                .then( res => { this.events = res.data });
            },
            async searchOrganizers(value) {
                await axios.get('/api/admin/organizer/search', { params: { keywords: value } })
                .then( res => { this.organizers = res.data });
            },
            async changeEventOrganization(value) {
                await axios.post(`/admin/event/${this.modalData.slug}/change-organizer`, value)
                this.reset();
            },
            showModal(event, arr) {
                this.modalData = event;
                this.modal = arr;
            },
            reset() {
                this.onLoad();
                this.modalData = null
                this.modal = false
            },
            cleanDate(data) {
                return this.$dayjs(data).format("dddd, MMMM D YYYY");
            },
            debounce(query) {
                if (this.timeout) 
                    clearTimeout(this.timeout); 
                this.timeout = setTimeout(() => {
                    this.onSearch(query)
                }, 500); // delay
            },
        },

        created() {
            this.onLoad()
        },

        watch: {
            dateFilter() {
                this.onLoad()
            }
        }

    }

</script>