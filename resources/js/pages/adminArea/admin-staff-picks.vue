<template>
    <div>
        <div class="flex justify-between mb-8 items-center">
            <h2>Staff Picks</h2>
            <button 
                @click="onAdd=!onAdd"
                class="border-none w-20 h-20 flex p-0 rounded-full justify-center items-center hover:bg-slate-300">
                <svg class="w-16 h-16">
                    <use :xlink:href="`/storage/website-files/icons.svg#ri-add-fill`" />
                </svg>
            </button>
        </div>
        
        <AddStaffPick
            :user="loaduser"
            :staff="loadstaff"
            @update="update"
            @close="onAdd=false"
            v-if="onAdd" />

        <div class="w-96 mb-4">
            <v-select 
                v-model="user"
                :options="loadstaff"
                label="name"
                placeholder="Filter by user"
                @input="onLoad" />
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
                                    class="border-b dark:border-slate-600 font-medium p-4 pt-0 pb-3 text-slate-400 dark:text-slate-200 text-left">
                                    <p 
                                        class="text-xl" 
                                        :class="col.class">{{ col.field }}</p>
                                </th>
                            </tr>
                        </thead>
                        <tbody class="bg-white dark:bg-slate-800">
                            <tr
                                :key="pick.id"
                                v-for="pick in staffpicks.data">
                                <td class="border-b border-slate-100 dark:border-slate-700 p-4 text-slate-500 dark:text-slate-400">
                                    <a 
                                        v-if="pick.event"
                                        class="flex flex-col items-center" 
                                        target="_blank"
                                        :href="`/events/${pick.event.slug}`">
                                        <img 
                                            class="w-32 rounded-2xl" 
                                            v-if="pick.event.thumbImagePath"
                                            :src="`/storage/${pick.event.thumbImagePath}`">
                                        <p class="text-xl text-center max-w-xs">{{ pick.event.name }}</p>
                                    </a>
                                </td>
                                <td class="border-b border-slate-100 dark:border-slate-700 p-4 text-slate-500 dark:text-slate-400">
                                    <textarea 
                                        v-model="pick.comments" 
                                        class="create-input area" 
                                        rows="1"
                                        placeholder=" "
                                        @blur="onUpdate(pick)"
                                        required 
                                        autofocus />
                                </td>
                                <td class="border-b border-slate-100 dark:border-slate-700 p-4 text-slate-500 dark:text-slate-400">
                                    <p class="text-lg">{{ pick.user.name }}</p>
                                </td>
                                <td class="border-b border-slate-100 dark:border-slate-700 p-4 text-slate-500 dark:text-slate-400">
                                    <select 
                                        class="text-xl px-4 py-2 rounded-xl hover:bg-slate-500 hover:text-white"
                                        v-model="pick.rank"
                                        @change="onUpdate(pick)">
                                        <option 
                                            v-for="pick in ['0','1', '2', '3', '4', '5']"
                                            :key="pick">
                                            {{ pick }}
                                        </option>
                                    </select>
                                </td>
                                <td class="border-b border-slate-100 dark:border-slate-700 p-4 text-slate-500 dark:text-slate-400">
                                    <flat-pickr
                                        :value="[pick.start_date, pick.end_date]"
                                        @on-close="updateDates($event, pick)"
                                        class="border p-2 rounded-2xl"
                                        :config="pickConfig"                                         
                                        placeholder="Select date"               
                                        name="dates" />
                                </td>
                                <td class="border-b border-slate-100 dark:border-slate-700 p-4 text-slate-500 dark:text-slate-400">
                                    <button 
                                        @click.prevent="selectedModal=pick" 
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
            :list="staffpicks"
            @selectpage="onLoad" />
        <VueDeleteModal 
            v-if="selectedModal"
            :item="selectedModal"
            :strict="true"
            body="You are deleting the staff pick. Please be sure you know what you are doing."
            @close="selectedModal=null"
            @ondelete="onDelete" />
    </div>
</template>

<script>
    import flatPickr from 'vue-flatpickr-component'
    import 'flatpickr/dist/flatpickr.css'
    import Pagination  from '../../components/pagination.vue'
    import AddStaffPick from './components/staff-pick-component'
    import VueDeleteModal from '../../components/modals/Vue-Modal-Delete'

    export default {

        props: ['loadstaff', 'loaduser'],

        components: { flatPickr, Pagination, AddStaffPick, VueDeleteModal },

        data() {
            return {
                onAdd: false,
                dates: [],
                listDates: [],
                rankOptions: ['1', '2', '3', '4', '5'],
                user: null,
                staffpicks:[],
                listDatesSubmit: [],
                listDatesFormatted: [],
                selectedModal: '',
                modal: '',
                listChangeDates: [],
                pickConfig: this.initializeDateObject('list'),
                cols: [
                    { id:0, field: 'Event', class:'text-center'},
                    { id:1, field: 'Comments', class:''},
                    { id:2, field: 'User', class:''},
                    { id:3, field: 'Rank', class:'' },
                    { id:4, field: 'Dates', class:''},
                    { id:5, field: '',class:'' },
                ],
            }
        },

        methods: {
            async onLoad(page) {
                await axios.post(`/admin/staffpicks/fetch`, { page: page, user: this.user ? this.user.id : '' })
                .then( res => { this.staffpicks = res.data })
            },
            async onDelete() {
                await axios.delete(`/staffpicks/${this.selectedModal.id}`)
                location.reload();
            },
            async onUpdate(pick) {
                await axios.patch(`/staffpicks/${pick.id}`, pick)
                .then( res => { this.staffpicks = res.data })
            },
            update(value) {
                this.staffpicks = value
                this.onAdd = false
            },
            updateDates(date, pick) {
                pick.start_date = this.$dayjs(date[0]).format("YYYY-MM-DD HH:mm:ss")
                pick.end_date = this.$dayjs(date[1]).format("YYYY-MM-DD HH:mm:ss")
                this.onUpdate(pick)
            },
            initializeDateObject(val) {
                return {
                    altFormat:'M d',
                    altInput: true,
                    mode: val == 'event' ? 'multiple' : 'range',
                    inline: val == 'list' ? false : true,
                    showMonths: 1,
                    dateFormat: 'Y-m-d H:i:s',
                    onClose:  [this.listDateFunc()], 
                }
            },
            listDateFunc() {
                const that = this;
                return function(value) {
                    that.listDatesSubmit = value.map(date => 
                        this.formatDate(date, "Y-m-d H:i:S"));
                    that.listDatesFormatted = value.map(date => 
                        this.formatDate(date, "M d"));
                }
            },
        },

        mounted() {
            this.onLoad()
            // this.$nextTick(() => this.$refs.button.focus())
        },

    }
</script>