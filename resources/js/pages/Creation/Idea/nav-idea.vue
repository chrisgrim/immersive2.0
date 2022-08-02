<template>
    <div class="mx-[-12px] my-2">
        <div 
            @click="onDropdown"
            :class="{'border-black border-2 m-[-2px]' : inputVal.category === 'GettingStarted' }"
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
                Getting Started
            </div>
            <div 
                v-if="!dropdown"
                class="flex-[0_0_auto]">
                <svg 
                    :class="[{ 'fill-black': hasAll }, { 'fill-orange-400': !hasAll }]"
                    class="w-8 h-8">
                    <use v-if="hasAll" :xlink:href="`/storage/website-files/icons.svg#ri-checkbox-circle-fill`" />
                    <use v-else :xlink:href="`/storage/website-files/icons.svg#ri-question-fill`" />
                </svg>
            </div>
        </div>
        <div v-if="dropdown">
            <ul class="list-none mr-[-9px] ml-12">
                <li @click="showEventType">
                    <div 
                        :class="{'border-black border-2 m-[-2px]' : inputVal.page === 'EventType' }"
                        class="flex justify-between items-center p-3 rounded-full cursor-pointer hover:bg-gray-200">
                        <div class="text-xl tracking-wide"> Event Type </div>
                        <div>
                            <svg 
                                :class="[{ 'fill-black-400': hasEventType }, { 'fill-orange-400': !hasEventType }]"
                                class="w-8 h-8">
                                <use v-if="hasEventType" :xlink:href="`/storage/website-files/icons.svg#ri-checkbox-circle-fill`" />
                                <use v-else :xlink:href="`/storage/website-files/icons.svg#ri-question-fill`" />
                            </svg>
                        </div>
                    </div>
                </li>
                <li @click="showCategory">
                    <div 
                        :class="{'border-black border-2 m-[-2px]' : inputVal.page === 'Category' }"
                        class="flex justify-between items-center p-3 rounded-full cursor-pointer hover:bg-gray-200">
                        <div class="text-xl tracking-wide"> Category</div>
                        <div>
                            <svg 
                                :class="[{ 'fill-black-400': hasCategory }, { 'fill-orange-400': !hasCategory }]"
                                class="w-8 h-8">
                                <use v-if="hasCategory" :xlink:href="`/storage/website-files/icons.svg#ri-checkbox-circle-fill`" />
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
            hasAll() {
                return this.hasEventType && this.hasCategory
            },
            hasEventType() {
                return this.value.event.hasLocation !== null
            },
            hasCategory() {
                return this.value.event.category_id
            }
        },

        data() {
            return {
                dropdown: ['EventType', 'Category'].includes(this.value.page),
            };
        },

        methods: {
            onDropdown() {
                this.dropdown =! this.dropdown
                this.inputVal.category = 'GettingStarted'
            },
            showEventType() {
                this.inputVal.category = ''
                this.inputVal.page = 'EventType'
            },
            showCategory() {
                this.inputVal.category = ''
                this.inputVal.page = 'Category'
            }
        },

    }
</script>