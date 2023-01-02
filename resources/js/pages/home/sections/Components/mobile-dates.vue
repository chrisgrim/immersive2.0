<template>
    <div>
        <!-- content -->
        <div 
            :class="{'mb-72' : hasCalendar}"
            class="relative">
            <div class="m-8 p-8 rounded-3xl bg-white shadow-custom-1">
                <div 
                    v-if="!hasCalendar"
                    @click="toggleCalendar"
                    class="flex justify-between cursor-pointer">
                    <div class="text-2xl">
                        When
                    </div>
                    <div class="font-semibold text-2xl">
                        <p v-if="inputVal.naturalDate.length">{{ inputVal.naturalDate[0] }}{{ inputVal.naturalDate[1] ? ' — ' + inputVal.naturalDate[1] : '' }}</p>
                        <p v-else>Add Dates</p>
                    </div>
                </div>
                <div v-else>
                    <div 
                        @click="toggleCalendar"
                        class="font-semibold text-4xl flex justify-between items-center">
                        <span>Choose Dates</span>
                        <svg class="w-8 h-8">
                            <use :xlink:href="`/storage/website-files/icons.svg#ri-arrow-up-s-line`" />
                        </svg>
                    </div>
                    <div class="z-50">
                        <div class="m-auto text-center bg-white rounded-2xl top-6 overflow-hidden">
                            <div class="w-full overflow-y-auto overflow-x-hidden max-h-[calc(100vh-24rem)] input-hidden shadow-hidden">
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
                                    @click="hasCalendar=false" 
                                    class="border-none flex items-center">
                                    Cancel
                                </button>
                                <button 
                                    class="px-8 h-14 rounded-full w-auto bg-white whitespace-nowrap text-white bg-default-red border-default-red" 
                                    @click="toggleCalendar">
                                    Save
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
                <!-- search -->
            </div>
        </div>
    </div>
</template>

<script>
    import flatPickr from 'vue-flatpickr-component'
    export default {

        props: ['value'],

        components: { flatPickr },

        computed: {
            inputVal: {
                get() { return this.value },
                set(val) { this.$emit('input', val) }
            },
        },

        data() {
            return {
                calendarConfig: this.initializeCalendarObject(),
                hasCalendar: false,
                dates:[],
            }
        },

        methods: {
            clear() {
                this.inputVal.dates=[]
                this.inputVal.naturalDate=[]
                this.inputVal.searchDates=[]
            },
            toggleCalendar() {
                this.hasCalendar=!this.hasCalendar
            },
            dateFunc() {
                const that = this;
                return function(value) {
                    that.dates = value.map(date => 
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
        },
}
    
</script>