<template>
    <main class="transition ease-in duration-300">
        <div class="fixed w-[calc(100vw-26rem)] overflow-auto h-full left-[26rem]">
            <div class="p-12 h-28 w-full">
                <p class="font-semibold">Event Pricing</p>
            </div>
            <div class="flex justify-center h-full w-full p-8">
                <div class="max-w-3xl">
                    <h2> Event Pricing</h2>
                    <div>
                        <p class="font-light mt-4">Let our users know your ticket pricing Lorem ipsum dolor, sit amet consectetur adipisicing elit. Ipsa fuga unde, quam autem vol</p>
                    </div>
                    <div class="grid grid-cols-3 gap-8 mt-8 border-b-[1px] border-blue-900">
                        <div 
                            class="w-full relative"
                            ref="ticketTypeDrop">
                            <div class="w-full relative">
                                <svg 
                                    :class="{'rotate-90': dropdown}"
                                    class="w-10 h-10 fill-black absolute z-10 right-4 top-8">
                                    <use :xlink:href="`/storage/website-files/icons.svg#ri-arrow-right-s-line`" />
                                </svg>
                                <input 
                                    class="text-2xl relative p-8 w-full border mb-12 rounded-3xl focus:rounded-3xl"
                                    v-model="ticket.name"
                                    @focus="onDropdown"
                                    type="text">
                            </div>
                            <ul 
                                class="h-[40rem] overflow-auto bg-white relative w-full list-none rounded-b-3xl" 
                                v-if="dropdown">
                                <li 
                                    class="py-6 px-6 flex items-center gap-8 hover:bg-gray-300" 
                                    v-for="item in ticketTypes"
                                    :key="item.id"
                                    @click="selectTicketType(item)">
                                    {{item.name}}
                                </li>
                            </ul>
                        </div>
                        <div>
                            <input 
                                v-model="description"
                                placeholder="Ticket description" 
                                type="text">
                        </div>
                        <div>
                            <input 
                                class="bg-red-100" 
                                v-model="price"
                                type="text">
                        </div>
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
                active: '',
                dropdown: false,
                ticket: { id:1, name:'general'},
                price: 0,
                description: '',
                ticketTypes: [{ id:1, name:'general'}, {id:2, name:'free'}]
            };
        },

        methods: {
            onDropdown() {
                this.dropdown = true
            },
            onClickOutside(event) {
                let arr =  this.$refs.ticketTypeDrop;
                if (!arr || arr.contains(event.target)) return;
                this.dropdown = false;
            },
        },

        mounted() {
            document.addEventListener("click", this.onClickOutside);
        },

        beforeUnmount() {
            document.removeEventListener("click", this.onClickOutside);
        },
    }
</script>