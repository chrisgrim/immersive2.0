<template>
    <main class="transition ease-in duration-300">
        <div class="fixed w-[calc(100vw-26rem)] overflow-hidden h-full left-[26rem]">
            <div class="p-12 h-28 w-full">
                <p class="font-semibold">Experience type</p>
            </div>
            <div class="flex justify-center h-full w-full p-8">
                <div class="max-w-3xl">
                    <h2>What type of event are you hosting?</h2>
                    <div class="pt-16 flex flex-col gap-8">
                        <button 
                            @click="onSelect('location')"
                            :class="{ 'border-2 border-black bg-[#f7f7f7] m-[-1px]' : active==='location' }"
                            class="border-gray-300 rounded-2xl flex justify-between items-center w-full pb-4 hover:border-2 hover:m-[-1px] hover:border-black">
                            <div class="m-6 w-full text-left">
                                <div class="font-medium">
                                    In Person
                                </div>
                                <div class="text-1xl mt-4">
                                    Real world events that guests will be part of.
                                </div>
                            </div>
                        </button>
                        <button 
                            @click="onSelect('remote')"
                            :class="{ 'border-2 border-black bg-[#f7f7f7] m-[-1px]' : active==='remote' }"
                            class="border-gray-300 rounded-2xl flex justify-between items-center w-full hover:border-2 hover:m-[-1px] hover:border-black">
                            <div class="m-6 w-full text-left">
                                <div class="font-medium">
                                    Online Only
                                </div>
                                <div class="text-1xl mt-4">
                                    Guests will join the event virtually
                                </div>
                            </div>
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </main>
</template>

<script>
    export default {

        props: [],

        components: {  },

        data() {
            return {
                active:''
            };
        },

        methods: {
            onSelect(val) {
                this.active = val
            }
        }
    }
</script>