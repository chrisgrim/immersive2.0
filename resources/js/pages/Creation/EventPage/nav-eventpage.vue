<template>
    <div class="mx-[-12px] my-2">
        <div 
            @click="onDropdown"
            :class="[{'border-black border-2 m-[-2px]' : inputVal.category === 'EventPage' }, {'cursor-pointer hover:bg-gray-200': !isLocked}]"
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
                Event Page
            </div>
            <div 
                v-if="!dropdown"
                class="flex-[0_0_auto]">
                <svg 
                    :class="[{ 'fill-black-400': hasAll }, { 'fill-orange-400': !hasAll && !isLocked }, { 'fill-black-400': isLocked}]"
                    class="w-8 h-8">
                    <use v-if="isLocked" :xlink:href="`/storage/website-files/icons.svg#ri-rotate-lock-fill`" />
                    <use v-if="hasAll" :xlink:href="`/storage/website-files/icons.svg#ri-checkbox-circle-fill`" />
                    <use v-if="!hasAll && !isLocked" :xlink:href="`/storage/website-files/icons.svg#ri-question-fill`" />
                </svg>
            </div>
        </div>
        <div v-if="dropdown">
            <ul class="list-none mr-[-9px]">
                <li @click="onShow('Description')">
                    <div 
                        :class="{'border-black border-2 m-[-2px]' : inputVal.page === 'Description' }"
                        class="flex justify-between items-center p-3 rounded-full cursor-pointer hover:bg-gray-200">
                        <div class="text-xl tracking-wide"> Description </div>
                        <div>
                            <svg 
                                :class="[{ 'fill-black-400': hasDescription }, { 'fill-orange-400': !hasDescription }]"
                                class="w-8 h-8">
                                <use v-if="hasDescription" :xlink:href="`/storage/website-files/icons.svg#ri-checkbox-circle-fill`" />
                                <use v-else :xlink:href="`/storage/website-files/icons.svg#ri-question-fill`" />
                            </svg>
                        </div>
                    </div>
                </li>
                <li @click="onShow('EventHost')">
                    <div 
                        :class="{'border-black border-2 m-[-2px]' : inputVal.page === 'EventHost' }"
                        class="flex justify-between items-center p-3 rounded-full cursor-pointer hover:bg-gray-200">
                        <div class="text-xl tracking-wide"> EventHost </div>
                        <div>
                            <svg 
                                :class="[{ 'fill-black-400': hasEventHost }, { 'fill-orange-400': !hasEventHost }]"
                                class="w-8 h-8">
                                <use v-if="hasEventHost" :xlink:href="`/storage/website-files/icons.svg#ri-checkbox-circle-fill`" />
                                <use v-else :xlink:href="`/storage/website-files/icons.svg#ri-question-fill`" />
                            </svg>
                        </div>
                    </div>
                </li>
                <li 
                    v-if="value.event.hasLocation"
                    @click="onShow('Location')">
                    <div 
                        :class="{'border-black border-2 m-[-2px]' : inputVal.page === 'Location' }"
                        class="flex justify-between items-center p-3 rounded-full cursor-pointer hover:bg-gray-200">
                        <div class="text-xl tracking-wide"> Location </div>
                        <div>
                            <svg 
                                :class="[{ 'fill-black-400': hasLocation }, { 'fill-orange-400': !hasLocation }]"
                                class="w-8 h-8">
                                <use v-if="hasLocation" :xlink:href="`/storage/website-files/icons.svg#ri-checkbox-circle-fill`" />
                                <use v-else :xlink:href="`/storage/website-files/icons.svg#ri-question-fill`" />
                            </svg>
                        </div>
                    </div>
                </li>
                <li @click="onShow('GuestRequirements')">
                    <div 
                        :class="{'border-black border-2 m-[-2px]' : inputVal.page === 'GuestRequirements' }"
                        class="flex justify-between items-center p-3 rounded-full cursor-pointer hover:bg-gray-200">
                        <div class="text-xl tracking-wide"> Guest Requirements </div>
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
                <li @click="onShow('Title')">
                    <div 
                        :class="{'border-black border-2 m-[-2px]' : inputVal.page === 'Title' }"
                        class="flex justify-between items-center p-3 rounded-full cursor-pointer hover:bg-gray-200">
                        <div class="text-xl tracking-wide"> Title </div>
                        <div>
                            <svg 
                                :class="[{ 'fill-black-400': hasTitle }, { 'fill-orange-400': !hasTitle }]"
                                class="w-8 h-8">
                                <use v-if="hasTitle" :xlink:href="`/storage/website-files/icons.svg#ri-checkbox-circle-fill`" />
                                <use v-else :xlink:href="`/storage/website-files/icons.svg#ri-question-fill`" />
                            </svg>
                        </div>
                    </div>
                </li>
                <li @click="onShow('Photo')">
                    <div 
                        :class="{'border-black border-2 m-[-2px]' : inputVal.page === 'Photo' }"
                        class="flex justify-between items-center p-3 rounded-full cursor-pointer hover:bg-gray-200">
                        <div class="text-xl tracking-wide"> Photo </div>
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
            isLocked() {
                return  Number(this.value.event.status) === 0
            },
            hasAll() {
                return this.hasDescription && this.hasTitle
            },
            hasDescription() { return this.value.event.description },
            hasTitle() { return this.value.event.name && this.value.event.tag_line},
            hasEventHost() { return this.value.event.organizer_id },
            hasLocation() { return this.value.event.location.latitude },
        },

        data() {
            return {
                dropdown: ['Description', 'EventHost', 'Location', 'GuestRequirements', 'Title', 'Photo'].includes(this.value.page),
            };
        },

        methods: {
            onDropdown() {
                if (this.isLocked) return 
                this.dropdown =! this.dropdown
                this.inputVal.category = 'EventPage'
            },
            onShow(value) {
                this.$emit('submit')
                this.inputVal.category = ''
                this.inputVal.page = value
            },
        }
    }
</script>