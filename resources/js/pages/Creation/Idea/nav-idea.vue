<template>
    <div>
        <div 
            @click="idea =! idea"
            :class="{'border-black' : type }"
            class="starting-section items-center flex p-4 rounded-full cursor-pointer hover:bg-slate-100">
            <div class="flex-[0_0_auto] mr-2">
                <svg 
                    :class="{'rotate-90': idea}"
                    class="w-8 h-8 fill-gray-400">
                    <use :xlink:href="`/storage/website-files/icons.svg#ri-arrow-right-s-line`" />
                </svg>
            </div>
            <div class="flex-1">
                Getting Started
            </div>
            <div 
                v-if="!idea"
                class="flex-[0_0_auto]">
                <svg 
                    :class="{'rotate-90': idea}"
                    class="w-8 h-8 fill-gray-400">
                    <use :xlink:href="`/storage/website-files/icons.svg#ri-checkbox-circle-fill`" />
                </svg>
            </div>
        </div>
        <div v-if="idea">
            <ul>
                <li>
                    <a :href="`/experience-hosting/listing/${event.slug}/experience-type`">
                        <div class="flex justify-between items-center p-3 rounded-full cursor-pointer hover:bg-slate-100">
                            <div class="text-lg"> Event Type </div>
                            <div>
                                <svg 
                                    :class="[{ 'fill-orange-400': idea }, { 'fill-gray-400': !idea }]"
                                    class="w-8 h-8">
                                    <use v-if="idea" :xlink:href="`/storage/website-files/icons.svg#ri-question-fill`" />
                                    <use v-else :xlink:href="`/storage/website-files/icons.svg#ri-checkbox-circle-fill`" />
                                </svg>
                            </div>
                        </div>
                    </a>
                </li>
                <li>
                    <div class="flex justify-between items-center p-3 rounded-full cursor-pointer hover:bg-slate-100">
                        <div class="text-lg">Basics</div>
                        <div>
                            <svg 
                                :class="[{ 'fill-black-400': idea }, { 'fill-gray-400': !idea }]"
                                class="w-8 h-8">
                                <use :xlink:href="`/storage/website-files/icons.svg#ri-checkbox-circle-fill`" />
                            </svg>
                        </div>
                    </div>
                </li>
                <li>
                    <div class="flex justify-between items-center p-3 rounded-full cursor-pointer hover:bg-slate-100">
                        <div class="text-lg"> Categories </div>
                        <div>
                            <svg 
                                :class="[{ 'fill-black-400': idea }, { 'fill-gray-400': !idea }]"
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

        props: [ 'event' ],

        data() {
            return {
                idea: false,
                type: this.$parent.$parent.$attrs['type'],
                basics: this.$parent.$parent.$attrs['basics'],
                categories: this.$parent.$parent.$attrs['categories']
            };
        },
    }
</script>