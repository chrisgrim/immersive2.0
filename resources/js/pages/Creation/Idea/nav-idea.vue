<template>
    <div>
        <div 
            @click="dropdown =! dropdown"
            :class="{'border-black border-2 m-[-2px]' : active }"
            class="starting-section items-center flex p-4 rounded-full cursor-pointer mx-[-12px] hover:bg-gray-200">
            <div class="flex-[0_0_auto] mr-2">
                <svg 
                    :class="{'rotate-90': active}"
                    class="w-8 h-8 fill-gray-400">
                    <use :xlink:href="`/storage/website-files/icons.svg#ri-arrow-right-s-line`" />
                </svg>
            </div>
            <div 
                :class="{'!text-gray-500' : active }"
                class="flex-1 text-gray-400">
                Getting Started
            </div>
            <div 
                v-if="!active"
                class="flex-[0_0_auto]">
                <svg 
                    :class="{'rotate-90': active}"
                    class="w-8 h-8 fill-gray-400">
                    <use :xlink:href="`/storage/website-files/icons.svg#ri-checkbox-circle-fill`" />
                </svg>
            </div>
        </div>
        <div v-if="dropdown">
            <ul class="list-none mr-[-9px]">
                <li @click="inputVal='E-T'">
                    <div 
                        :class="{'border-black border-2 m-[-2px]' : inputVal==='E-T' }"
                        class="flex justify-between items-center p-3 rounded-full cursor-pointer hover:bg-gray-200">
                        <div class="text-xl tracking-wide"> Event Type </div>
                        <div>
                            <svg 
                                :class="[{ 'fill-orange-400': active }, { 'fill-gray-400': !active }]"
                                class="w-8 h-8">
                                <use v-if="active" :xlink:href="`/storage/website-files/icons.svg#ri-question-fill`" />
                                <use v-else :xlink:href="`/storage/website-files/icons.svg#ri-checkbox-circle-fill`" />
                            </svg>
                        </div>
                    </div>
                </li>
                <li @click="inputVal='C'">
                    <div 
                        :class="{'border-black border-2 m-[-2px]' : inputVal==='C' }"
                        class="flex justify-between items-center p-3 rounded-full cursor-pointer hover:bg-gray-200">
                        <div class="text-xl tracking-wide"> Category</div>
                        <div>
                            <svg 
                                :class="[{ 'fill-black-400': active }, { 'fill-gray-400': !active }]"
                                class="w-8 h-8">
                                <use :xlink:href="`/storage/website-files/icons.svg#ri-checkbox-circle-fill`" />
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

        props: [ 'event', 'value' ],

        computed: {
            inputVal: {
                get() { return this.value },
                set(val) { this.$emit('input', val) }
            },
        },

        data() {
            return {
                active: this.value === 'E-T' || this.value === 'C',
                dropdown: this.value === 'E-T' || this.value === 'C',
            };
        },
    }
</script>