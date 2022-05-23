<template>
    <div>
        <div 
            @click="guidelines =! guidelines"
            :class="{'border-black border-2 m-[-2px]' : guidelines }"
            class="starting-section items-center flex p-4 rounded-full cursor-pointer mx-[-12px] hover:bg-gray-200">
            <div class="flex-[0_0_auto] mr-2">
                <svg 
                    :class="{'rotate-90': guidelines}"
                    class="w-8 h-8 fill-gray-400">
                    <use :xlink:href="`/storage/website-files/icons.svg#ri-arrow-right-s-line`" />
                </svg>
            </div>
            <div 
                :class="{'!text-gray-500' : guidelines }"
                class="flex-1 text-gray-400">
                Guidelines
            </div>
            <div 
                v-if="!guidelines"
                class="flex-[0_0_auto]">
                <svg 
                    :class="{'rotate-90': guidelines}"
                    class="w-8 h-8 fill-gray-400">
                    <use :xlink:href="`/storage/website-files/icons.svg#ri-checkbox-circle-fill`" />
                </svg>
            </div>
        </div>
        <div v-if="guidelines">
            <ul class="list-none mr-[-9px]">
                <li>
                    <a :href="`/experience-hosting/listing/${event.slug}/experience-type`">
                        <div class="flex justify-between items-center p-3 rounded-full cursor-pointer hover:bg-gray-200">
                            <div class="text-lg"> Overview </div>
                            <div>
                                <svg 
                                    :class="[{ 'fill-orange-400': guidelines }, { 'fill-gray-400': !guidelines }]"
                                    class="w-8 h-8">
                                    <use v-if="guidelines" :xlink:href="`/storage/website-files/icons.svg#ri-question-fill`" />
                                    <use v-else :xlink:href="`/storage/website-files/icons.svg#ri-checkbox-circle-fill`" />
                                </svg>
                            </div>
                        </div>
                    </a>
                </li>
                <li>
                    <div class="flex justify-between items-center p-3 rounded-full cursor-pointer hover:bg-gray-200">
                        <div class="text-lg"> Immersive</div>
                        <div>
                            <svg 
                                :class="[{ 'fill-black-400': guidelines }, { 'fill-gray-400': !guidelines }]"
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
                guidelines: false,
                overview: this.$parent.$parent.$attrs['overview'],
                immersive: this.$parent.$parent.$attrs['immersive'],
            };
        },
    }
</script>