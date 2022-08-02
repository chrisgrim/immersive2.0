<template>
    <div class="mx-[-12px] my-4">
        <div 
            @click="onDropdown"
            :class="{'border-black border-2 m-[-2px]' : inputVal.category === 'Settings' }"
            class="starting-section items-center flex p-4 rounded-full cursor-pointer hover:bg-gray-200">
            <div class="flex-[0_0_auto] mr-2">
                <svg 
                    :class="{'rotate-90': dropdown}"
                    class="w-8 h-8 fill-gray-400">
                    <use :xlink:href="`/storage/website-files/icons.svg#ri-arrow-right-s-line`" />
                </svg>
            </div>
            <div 
                :class="{'!text-gray-500' : dropdown }"
                class="flex-1 text-gray-400">
                Settings
            </div>
            <div 
                v-if="!dropdown"
                class="flex-[0_0_auto]">
                <svg 
                    :class="{'rotate-90': dropdown}"
                    class="w-8 h-8 fill-gray-400">
                    <use :xlink:href="`/storage/website-files/icons.svg#ri-checkbox-circle-fill`" />
                </svg>
            </div>
        </div>
        <div v-if="dropdown">
            <ul class="list-none mr-[-9px] ml-12">
                <li @click="showDatesTimes">
                    <div 
                        :class="{'border-black border-2 m-[-2px]' : inputVal.page === 'DatesTimes' }"
                        class="flex justify-between items-center p-3 rounded-full cursor-pointer hover:bg-gray-200">
                        <div class="text-lg"> Dates and Times </div>
                        <div>
                            <svg 
                                :class="[{ 'fill-orange-400': dropdown }, { 'fill-gray-400': !dropdown }]"
                                class="w-8 h-8">
                                <use v-if="dropdown" :xlink:href="`/storage/website-files/icons.svg#ri-question-fill`" />
                                <use v-else :xlink:href="`/storage/website-files/icons.svg#ri-checkbox-circle-fill`" />
                            </svg>
                        </div>
                    </div>
                </li>
                <li @click="showGuestPricing">
                    <div 
                        :class="{'border-black border-2 m-[-2px]' : inputVal.page === 'GuestPricing' }"
                        class="flex justify-between items-center p-3 rounded-full cursor-pointer hover:bg-gray-200">
                        <div class="text-lg"> Guest Pricing </div>
                        <div>
                            <svg 
                                :class="[{ 'fill-orange-400': dropdown }, { 'fill-gray-400': !dropdown }]"
                                class="w-8 h-8">
                                <use v-if="dropdown" :xlink:href="`/storage/website-files/icons.svg#ri-question-fill`" />
                                <use v-else :xlink:href="`/storage/website-files/icons.svg#ri-checkbox-circle-fill`" />
                            </svg>
                        </div>
                    </div>
                </li>
            </ul>
        </div>
    </div>
</template>

<script>
    export default {

        props: [ 'value' ],

        computed: {
            inputVal: {
                get() { return this.value },
                set(val) { this.$emit('input', val) }
            },
        },

        data() {
            return {
                dropdown: ['DatesTimes', 'GuestPricing'].includes(this.value.page),
            };
        },

        methods: {
            onDropdown() {
                this.dropdown =! this.dropdown
                this.inputVal.category = 'Settings'
            },
            showDatesTimes() {
                this.inputVal.category = ''
                this.inputVal.page = 'DatesTimes'
            },
            showGuestPricing() {
                this.inputVal.category = ''
                this.inputVal.page = 'GuestPricing'
            },
        }
    }
</script>