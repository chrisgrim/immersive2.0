<template>
    <div>
        <div 
            @click="eventpage =! eventpage"
            :class="{'border-black border-2 m-[-2px]' : eventpage }"
            class="starting-section items-center flex p-4 rounded-full cursor-pointer mx-[-12px] hover:bg-gray-200">
            <div class="flex-[0_0_auto] mr-2">
                <svg 
                    :class="{'rotate-90': eventpage}"
                    class="w-8 h-8 fill-gray-400">
                    <use :xlink:href="`/storage/website-files/icons.svg#ri-arrow-right-s-line`" />
                </svg>
            </div>
            <div 
                :class="{'!text-gray-500' : eventpage }"
                class="flex-1 text-gray-400">
                Event Page
            </div>
            <div 
                v-if="!eventpage"
                class="flex-[0_0_auto]">
                <svg 
                    :class="{'rotate-90': eventpage}"
                    class="w-8 h-8 fill-gray-400">
                    <use :xlink:href="`/storage/website-files/icons.svg#ri-checkbox-circle-fill`" />
                </svg>
            </div>
        </div>
        <div v-if="eventpage">
            <ul class="list-none mr-[-9px]">
                <li>
                    <a :href="`/experience-hosting/listing/${event.slug}/experience-type`">
                        <div class="flex justify-between items-center p-3 rounded-full cursor-pointer hover:bg-gray-200">
                            <div class="text-lg"> Description </div>
                            <div>
                                <svg 
                                    :class="[{ 'fill-orange-400': eventpage }, { 'fill-gray-400': !eventpage }]"
                                    class="w-8 h-8">
                                    <use v-if="eventpage" :xlink:href="`/storage/website-files/icons.svg#ri-question-fill`" />
                                    <use v-else :xlink:href="`/storage/website-files/icons.svg#ri-checkbox-circle-fill`" />
                                </svg>
                            </div>
                        </div>
                    </a>
                </li>
                <li>
                    <div class="flex justify-between items-center p-3 rounded-full cursor-pointer hover:bg-gray-200">
                        <div class="text-lg">Event Host</div>
                        <div>
                            <svg 
                                :class="[{ 'fill-black-400': eventpage }, { 'fill-gray-400': !eventpage }]"
                                class="w-8 h-8">
                                <use :xlink:href="`/storage/website-files/icons.svg#ri-checkbox-circle-fill`" />
                            </svg>
                        </div>
                    </div>
                </li>
                <li>
                    <div class="flex justify-between items-center p-3 rounded-full cursor-pointer hover:bg-gray-200">
                        <div class="text-lg">Location</div>
                        <div>
                            <svg 
                                :class="[{ 'fill-black-400': eventpage }, { 'fill-gray-400': !eventpage }]"
                                class="w-8 h-8">
                                <use :xlink:href="`/storage/website-files/icons.svg#ri-checkbox-circle-fill`" />
                            </svg>
                        </div>
                    </div>
                </li>
                <li>
                    <div class="flex justify-between items-center p-3 rounded-full cursor-pointer hover:bg-gray-200">
                        <div class="text-lg">Guest Requirements</div>
                        <div>
                            <svg 
                                :class="[{ 'fill-black-400': eventpage }, { 'fill-gray-400': !eventpage }]"
                                class="w-8 h-8">
                                <use :xlink:href="`/storage/website-files/icons.svg#ri-checkbox-circle-fill`" />
                            </svg>
                        </div>
                    </div>
                </li>
                <li>
                    <div class="flex justify-between items-center p-3 rounded-full cursor-pointer hover:bg-gray-200">
                        <div class="text-lg">Title</div>
                        <div>
                            <svg 
                                :class="[{ 'fill-black-400': eventpage }, { 'fill-gray-400': !eventpage }]"
                                class="w-8 h-8">
                                <use :xlink:href="`/storage/website-files/icons.svg#ri-checkbox-circle-fill`" />
                            </svg>
                        </div>
                    </div>
                </li>
                <li>
                    <div class="flex justify-between items-center p-3 rounded-full cursor-pointer hover:bg-gray-200">
                        <div class="text-lg">Photo</div>
                        <div>
                            <svg 
                                :class="[{ 'fill-black-400': eventpage }, { 'fill-gray-400': !eventpage }]"
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
                eventpage: false,
                description: this.$parent.$parent.$attrs['description'],
                organization: this.$parent.$parent.$attrs['organization'],
                location: this.$parent.$parent.$attrs['location'],
                requirements: this.$parent.$parent.$attrs['requirements'],
                title: this.$parent.$parent.$attrs['title'],
                photo: this.$parent.$parent.$attrs['photo']
            };
        },
    }
</script>