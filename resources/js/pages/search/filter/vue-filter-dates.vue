<template>
    <div v-click-outside="hide">
        <!-- mobile -->
        <div 
            v-if="mobile"
            class="w-full py-4 px-8">
            <button 
                @click="show('dates')" 
                :class="[ displayDate || active ? 'text-white bg-default-red border-default-red' : '' ]" 
                class="rounded-2xl shadow-custom-1 w-full overflow-hidden h-16 whitespace-nowrap">
                <template v-if="displayDate">
                    {{ naturalDate[0] }}{{ naturalDate[1] ? ' — ' + naturalDate[1] : '' }}
                </template>
                <template v-else>
                    Dates
                </template>
            </button>
        </div>
        <button 
            v-else
            @click="show('dates')" 
            :class="[ displayDate || active ? 'text-white bg-default-red border-default-red' : '', type === 'location-mobile' ? 'border-none' : 'border' ]" 
            class="px-8 h-14 rounded-full w-auto bg-white whitespace-nowrap hover:text-white hover:bg-default-red hover:border-default-red">
            <template v-if="displayDate">
                {{ naturalDate[0] }}{{ naturalDate[1] ? ' — ' + naturalDate[1] : '' }}
            </template>
            <template v-else>
                Dates
            </template>
        </button>
        <div 
            v-if="active"
            class="absolute mt-8 left-0 w-full md:left-32 md:max-w-5xl">
            <div class="m-auto text-center bg-white shadow-custom-1 rounded-5xl top-6 overflow-hidden">
                <div class="w-full overflow-y-auto overflow-x-hidden max-h-[calc(100vh-24rem)] input-hidden shadow-hidden">
                    <flat-pickr
                        v-model="dates"
                        :config="calendarConfig"                                         
                        placeholder="Select date"               
                        name="dates" />
                </div>
                <div class="py-4 px-12 w-full border-t justify-between flex">
                    <button 
                        v-if="naturalDate.length" 
                        @click="clear" 
                        class="border-none flex items-center">
                        Clear
                    </button>
                    <button 
                        v-if="!naturalDate.length" 
                        @click="active = false" 
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

        props: ['value', 'url', 'mobile', 'type'],

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
            hide() {
                this.active = false;
            },
        },
    }
</script>