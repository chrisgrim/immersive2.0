<template>
    <div class="mx-[-12px] my-2">
        <div 
            @click="onDropdown"
            :class="[{'border-black border-2 m-[-2px]' : value.category === 'Guidelines' }, { 'cursor-pointer hover:bg-gray-200' : !isLocked }]"
            class="starting-section items-center flex p-4 rounded-full">
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
                Guidelines
            </div>
            <div 
                v-if="!dropdown"
                class="flex-[0_0_auto]">
                <svg 
                    :class="[{ 'fill-black-400': hasOverview }, { 'fill-orange-400': !hasOverview && !isLocked }, { 'fill-black-400': isLocked}]"
                    class="w-8 h-8">
                    <use v-if="isLocked" :xlink:href="`/storage/website-files/icons.svg#ri-rotate-lock-fill`" />
                    <use v-if="hasOverview" :xlink:href="`/storage/website-files/icons.svg#ri-checkbox-circle-fill`" />
                    <use v-if="!hasOverview && !isLocked" :xlink:href="`/storage/website-files/icons.svg#ri-question-fill`" />
                </svg>
            </div>
        </div>
        <div v-if="dropdown">
            <ul class="list-none mr-[-9px] ml-12">
                <li @click="showOverview">
                    <div 
                        :class="{'border-black border-2 m-[-2px]' : inputVal.page === 'Overview' }"
                        class="flex justify-between items-center p-3 rounded-full cursor-pointer hover:bg-gray-200">
                        <div class="text-xl tracking-wide"> Overview </div>
                        <div>
                            <svg 
                                :class="[{ 'fill-black-400': hasOverview }, { 'fill-orange-400': !hasOverview }]"
                                class="w-8 h-8">
                                <use v-if="hasOverview" :xlink:href="`/storage/website-files/icons.svg#ri-checkbox-circle-fill`" />
                                <use v-else :xlink:href="`/storage/website-files/icons.svg#ri-question-fill`" />
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
            isLocked() {
                return this.value.event.hasLocation === null && !this.value.event.category_id
            },
            hasOverview() {
                return Number(this.value.event.status) !== 0
            },
        },

        data() {
            return {
                dropdown: ['Overview'].includes(this.value.page),
            };
        },

        methods: {
            onDropdown() {
                if (this.isLocked) return
                this.dropdown =! this.dropdown
                this.inputVal.category = 'Guidelines'
            },
            showOverview() {
                this.inputVal.category = ''
                this.inputVal.page = 'Overview'
            },
        }
    }
</script>