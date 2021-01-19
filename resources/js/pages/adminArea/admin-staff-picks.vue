<template>
    <div class="c-staffpicks">
        <div class="">
            <div class="title">
                <h1>Picks of the Week</h1>
                <div class="add">
                    <button @click.prevent="add = true" ref="button">
                        <IconSvg type="add" />
                    </button>
                </div>
            </div>
        </div>

        <Vue-Add-Staffpick v-if="add" />

        <div class="c-staffpicks__list">
            <div class="field c-staffpicks__list--filter">
                <label>Filter by User</label>
                <v-select 
                    v-model="user"
                    :options="loadstaff"
                    label="name"
                    placeholder="filter by user"
                    @search:blur="active = null"
                    @search:focus="active = 'rank'"
                    @input="onLoad" />
            </div>
            <div class="c-staffpicks__list--grid-top">
                <p>event</p>
                <p>comments</p>
                <p>user</p>
                <p>rank</p>
                <p>dates</p>
            </div>
            <div 
                v-for="pick in staffpicks.data"
                :key="pick.id"
                class="list c-staffpicks__list--grid">
                <div
                    v-if="pick.event"
                    class="image">
                    <img 
                        class="c-staffpicks__list--image" 
                        :src="/storage/ + pick.event.thumbImagePath" 
                        :alt="pick.event.name">
                    <div class="c-staffpicks__list--name">
                        {{ pick.event.name }}
                    </div>
                </div>
                <div class="field">
                    <textarea 
                        v-model="pick.comments" 
                        class="create-input area" 
                        rows="3"
                        :class="{ active: active == `${pick.id}comments`}"
                        placeholder=" "
                        @click="active = `${pick.id}comments`"
                        @blur="onUpdate(pick)"
                        required 
                        autofocus />
                </div>
                <div class="field">
                    <label>{{ pick.user.name }}</label>
                </div>
                <div class="rank">
                    <v-select 
                        v-model="pick.rank"
                        :options="rankOptions"
                        placeholder="Leave blank for default Rank of 5 (1 being most important)"
                        @search:blur="active = null"
                        @search:focus="active = 'rank'"
                        @input="onUpdate(pick)"
                        :clearable=false
                        :class="{ active: active == 'rank'}" />
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
                    @click.prevent="showModal(pick, 'delete')" 
                    class="delete-circle">
                    <IconSvg type="delete" />
                </button>
            </div>
            <pagination 
                :limit="1"
                :list="staffpicks"
                @selectpage="onLoad" />
        </div>
        <VueDeleteModal 
            v-if="modal == 'delete'"
            :item="selectedModal"
            :strict="true"
            body="You are deleting the staff pick"
            @close="modal = null"
            @ondelete="onDelete" />
    </div>
</template>

<script>
    import flatPickr from 'vue-flatpickr-component'
    import 'flatpickr/dist/flatpickr.css'
    import Pagination  from '../../components/pagination.vue'
    import IconSvg from '../../components/Svg-icon'
    import VueAddStaffpick from './components/vue-add-staffpick'
    import VueDeleteModal from '../../components/Vue-Delete-Modal'

    export default {

        props: ['loadstaff'],

        components: { flatPickr, Pagination, IconSvg, VueAddStaffpick, VueDeleteModal },

        computed: {
            updateObject() {
                return {

                }
            }
        },

        data() {
            return {
                add: false,
                dates: [],
                listDates: [],
                active: '',
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

            onLoad(page) {
                axios.post(`/admin/staffpicks/fetch`, { page: page, user: this.user ? this.user.id : '' })
                .then( res => { this.staffpicks = res.data })
            },

            async onDelete() {
                await axios.delete(`/staffpicks/${this.selectedModal.id}`)
                location.reload();
            },

            onUpdate(pick) {
                axios.patch(`/staffpicks/${pick.id}`, pick)
                .then( res => { 
                    this.staffpicks = res.data; this.active = null 
                })
            },

            updateDates(date, pick) {
                pick.start_date = this.$dayjs(date[0]).format("YYYY-MM-DD HH:mm:ss")
                pick.end_date = this.$dayjs(date[1]).format("YYYY-MM-DD HH:mm:ss")
                this.onUpdate(pick)
            },

            showModal(pick, arr) {
                this.selectedModal = pick;
                this.modal = arr;
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
            this.$nextTick(() => this.$refs.button.focus())
        },

    }
</script>