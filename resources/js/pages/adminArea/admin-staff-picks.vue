<template>
    <div class="admin-staffpicks">
        <div class="">
            <div class="title">
                <h1>Picks of the Week</h1>
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

        <div class="field">
            <label>Filter by User</label>
            <v-select 
                v-model="user"
                :options="loadstaff"
                label="name"
                placeholder="filter by user"
                @input="onLoad" />
        </div>
        <div class="data-grid">
            <div class="data-grid__row header">
                <p>event</p>
                <p>comments</p>
                <p>user</p>
                <p>rank</p>
                <p>dates</p>
            </div>
            <div 
                v-for="pick in staffpicks.data"
                :key="pick.id"
                class="data-grid__row">
                <div
                    v-if="pick.event"
                    class="image lg">
                    <img :src="/storage/ + pick.event.thumbImagePath">
                    <div class="admin-staffpicks__list--name">
                        {{ pick.event.name }}
                    </div>
                </div>
                <div class="field">
                    <textarea 
                        v-model="pick.comments" 
                        class="create-input area" 
                        rows="1"
                        placeholder=" "
                        @blur="onUpdate(pick)"
                        required 
                        autofocus />
                </div>
                <div class="field">
                    <label>{{ pick.user.name }}</label>
                </div>
                <div class="rank">
                    <select 
                        v-model="pick.rank"
                        @change="onUpdate(pick)"
                        placeholder="Leave blank for default Rank of 5 (1 being most important)">
                        <option 
                            v-for="picknum in ['1', '2', '3', '4', '5']"
                            :key="picknum">
                            {{ picknum }}
                        </option>
                    </select>
                </div>
                <div class="date-s">
                    <flat-pickr
                        :value="[pick.start_date, pick.end_date]"
                        @on-close="updateDates($event, pick)"
                        :config="pickConfig"                                         
                        placeholder="Select date"               
                        name="dates" />
                </div>
                <button 
                    @click.prevent="selectedModal=pick" 
                    class="delete">
                    <svg>
                        <use :xlink:href="`/storage/website-files/icons.svg#ri-close-line`" />
                    </svg>
                </button>
            </div>
            <pagination 
                :limit="1"
                :list="staffpicks"
                @selectpage="onLoad" />
        </div>
        <AddStaffPick
            :user="loaduser"
            :staff="loadstaff"
            @update="update"
            @close="onAdd=false"
            v-if="onAdd" />
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
    import AddStaffPick from './components/add-staff-pick'
    import VueDeleteModal from '../../components/modals/Vue-Modal-Delete'

    export default {

        props: ['loadstaff', 'loaduser'],

        components: { flatPickr, Pagination, AddStaffPick, VueDeleteModal },

        computed: {
            updateObject() {
                return {

                }
            }
        },

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