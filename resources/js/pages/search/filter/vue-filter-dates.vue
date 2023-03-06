<template>
    <div>
        <button 
            ref="datesButton"
            @click="toggleDates" 
            :class="[ displayDate || isShowing ? 'text-white bg-default-red border-default-red' : '' ]" 
            class="relative px-8 h-14 rounded-full w-auto bg-white whitespace-nowrap hover:text-white hover:bg-default-red hover:border-default-red text-xl">
            <template v-if="displayDate">
                {{ inputVal.naturalDate[0] }}{{ inputVal.naturalDate[1] ? ' — ' + inputVal.naturalDate[1] : '' }}
            </template>
            <template v-else>
                Dates
            </template>
        </button>
        <div 
            v-if="isShowing"
            ref="datesPopUp"
            class="absolute mt-8 w-full max-w-5xl">
            <div class="m-auto text-center bg-white shadow-custom-1 rounded-2xl top-6 overflow-hidden">
                <div class="w-full overflow-y-auto overflow-x-hidden max-h-[calc(80vh-24rem)] input-hidden shadow-hidden">
                    <flat-pickr
                        v-model="inputVal.dates"
                        :config="calendarConfig"                                         
                        placeholder="Select date"               
                        name="dates" />
                </div>
                <div class="py-4 px-12 w-full border-t justify-between flex">
                    <button 
                        v-if="inputVal.naturalDate.length" 
                        @click="clear" 
                        class="border-none flex items-center">
                        Clear
                    </button>
                    <button 
                        v-if="!inputVal.naturalDate.length" 
                        @click="toggleDates" 
                        class="border-none flex items-center">
                        Cancel
                    </button>
                    <button 
                        class="px-8 h-14 rounded-full w-auto bg-white whitespace-nowrap text-white bg-default-red border-default-red" 
                        @click="submit()">
                        Search
                    </button>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
    import flatPickr from 'vue-flatpickr-component'
    import clickOutside from '../../../Directives/clickOutside'
    export default {

        props: ['value'],

        components: { flatPickr },

        computed: {
            inputVal: {
                get() { return this.value },
                set(val) { this.$emit('input', val) }
            },
            isShowing() {
                return this.value.currentTab==='dates'
            },
            displayDate() {
                return this.inputVal.naturalDate.length;
            },
        },

        data() {
            return {
                calendarConfig: this.initializeCalendarObject(),
            }
        },

        methods: {
            submit() {
                this.$emit('submit', true)
                this.hideDates()
            },
            clear() {
                this.inputVal.dates = []
                this.inputVal.naturalDate = []
                this.inputVal.searchDates = []
            },
            toggleDates() {
                this.inputVal.currentTab==='dates' ? this.inputVal.currentTab='' : this.inputVal.currentTab='dates'
            },
            hideDates() {
                this.inputVal.currentTab=''
            },
            dateFunc() {
                const that = this;
                return function(value) {
                    that.inputVal.dates = value.map(date => 
                        this.formatDate(date, 'Y-m-d H:i:S'));
                    that.inputVal.searchDates = value.map(date => 
                        this.formatDate(date, 'Y-m-d H:i:S'));
                    that.inputVal.naturalDate = value.map(date => 
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
            onClickOutside(event) {
                let arr = this.$refs.datesButton;
                let arr1 = this.$refs.datesPopUp;
                if (!arr || arr.contains(event.target) || !arr1 || arr1.contains(event.target)) return;
                this.hideDates()
            },
        },

        mounted() {
            document.addEventListener("click", this.onClickOutside);
        },

        beforeUnmount() {
            document.removeEventListener("click", this.onClickOutside);
        },
    }
</script>