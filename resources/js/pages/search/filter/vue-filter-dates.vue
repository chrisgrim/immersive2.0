<template>
    <div 
        class="button" 
        ref="dates">
        <button 
            @click="show('dates')" 
            :class="{ active : naturalDate.length }" 
            class="filter round">
            <template v-if="displayDate">
                {{ naturalDate[0] }}{{ naturalDate[1] ? ' | ' + naturalDate[1] : '' }}
            </template>
            <template v-else>
                Dates
            </template>
        </button>
        <template v-if="active">
            <div class="filter__dates">
                <div class="filter__dropdown">
                    <div class="element">
                        <flat-pickr
                            v-model="dates"
                            :config="calendarConfig"                                         
                            placeholder="Select date"               
                            name="dates" />
                    </div>
                    <div class="filter__dropdown--footer">
                        <button 
                            v-if="naturalDate.length" 
                            @click="clear" 
                            class="borderless">
                            Clear
                        </button>
                        <button 
                            v-if="!naturalDate.length" 
                            @click="active = false" 
                            class="borderless">
                            Cancel
                        </button>
                        <button 
                            class="filter round" 
                            @click="submit()">
                            Search
                        </button>
                    </div>
                </div>
            </div>
        </template>
    </div>
</template>

<script>
    import flatPickr from 'vue-flatpickr-component'
    export default {

        props: ['url', 'mobile'],

        components: { flatPickr },

        computed: {
            displayDate() {
                return this.naturalDate.length;
            },
            mobileDates() {
                return this.$store.state.mobiledates;
            },
        },

        data() {
            return {
                active: false,
                dates: this.$store.state.filterDates,
                computerDate: this.$store.state.filterDates,
                naturalDate: this.$store.state.filterNaturalDates,
                calendarConfig: this.initializeCalendarObject(),
            }
        },

        methods: {
            submit() {
                this.$store.commit('filterDates', this.computerDate)
                this.$emit('submit', true);
                this.active = false;
            },

            clear() {
                this.naturalDate = []; 
                this.computerDate = []; 
                this.dates = [];
                this.$store.commit('filterDates', []);
                this.$store.commit('filterNaturalDates', []);
            },

            show() {
                this.active =! this.active;
                setTimeout(() => document.addEventListener('click', this.onClickOutside), 200);
            },

            dateFunc() {
                const that = this;
                return function(value) {
                    that.computerDate = value.map(date => 
                        this.formatDate(date, 'Y-m-d H:i:S'));
                    that.naturalDate = value.map(date => 
                        this.formatDate(date, 'D M d'));
                    let shortestDates = value.map(date => 
                        this.formatDate(date, 'M d'));
                    that.$store.commit('displaydates', shortestDates);
                }
            },

            initializeCalendarObject() { 
                return {
                    altFormat:'M d',
                    altInput: true,
                    mode: 'range',
                    inline: true,
                    showMonths: window.innerWidth < 768 ? 1 : 2,
                    dateFormat: 'Y-m-d H:i:s',
                    onClose: [this.dateFunc()], 
                }
            },

            onClickOutside(event) {
                if (this.active == false) {return}
                let dates = this.$refs.dates;
                if (!dates || dates.contains(event.target)) return;
                this.active = false;
                this.submit();
            },
        },
    }
</script>