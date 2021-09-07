<template>
    <div 
        class="button" 
        ref="dates">
        <button 
            @click="show('dates')" 
            :class="{ active : naturalDate.length }" 
            class="filter round dates">
            <template v-if="displayDate">
                {{ naturalDate[0] }}{{ naturalDate[1] ? ' — ' + naturalDate[1] : '' }}
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

        props: ['value','url', 'mobile'],

        components: { flatPickr },

        computed: {
            inputVal: {
                get() { return this.value },
                set(val) { this.$emit('input', val) }
            },
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
                dates: this.value,
                naturalDate: this.initializeNaturalDate(),
                calendarConfig: this.initializeCalendarObject(),
            }
        },

        methods: {
            submit() {
                this.$emit('submit', true);
                this.active = false;
            },
            clear() {
                this.naturalDate = []; 
                this.inputVal = []; 
                this.dates = [];
            },
            show() {
                this.active =! this.active;
                setTimeout(() => document.addEventListener('click', this.onClickOutside), 200);
            },
            dateFunc() {
                const that = this;
                return function(value) {
                    that.inputVal = value.map(date => 
                        this.formatDate(date, 'Y-m-d H:i:S'));
                    that.naturalDate = value.map(date => 
                        this.formatDate(date, 'M j'));
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
            initializeNaturalDate() {
                return this.value.length ? [this.$dayjs(this.value[0]).format("MMM D"), this.$dayjs(this.value[1]).format("MMM D")] : []
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