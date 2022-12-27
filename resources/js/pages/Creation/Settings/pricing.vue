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
                    <div class="flex gap-8 py-8 mt-8 border-b-[1px] border-blue-900">
                        <div 
                            class="w-48 relative"
                            ref="ticketTypeDrop">
                            <div class="w-full relative mt-10">
                                <input 
                                    class="text-2xl font-semibold relative w-full rounded-3xl focus:border-none"
                                    v-model="ticket.name"
                                    @focus="onDropdown"
                                    type="text">
                            </div>
                            <ul 
                                class="overflow-auto bg-white list-none rounded-3xl border m-0 absolute" 
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
                        <div class="">
                            <textarea
                                v-model="description"
                                rows="1"
                                class="p-6 mt-3"
                                placeholder="Ticket description" 
                                type="text">
                            </textarea>
                        </div>
                        <div class="w-40 mt-3">
                            <input 
                                v-model="price"
                                v-money="money"
                                class="p-6 border border-blue-900 rounded-lg w-40" 
                                placeholder="$0.00"
                                v-bind="money">
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </main>
</template>

<script>
    import { Money } from 'v-money'
    export default {

        props: [],

        components: { Money },

        data() {
            return {
                active: '',
                money: this.initializeMoneyObject(),
                dropdown: false,
                ticket: { id:1, name:'General'},
                price: 0,
                description: '',
                ticketTypes: [{ id:1, name:'General'}, {id:2, name:'Free'}]
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
            selectTicketType(item) {
                this.ticket = item
                this.dropdown = false
            },
            initializeMoneyObject() {
            return {
                decimal: '.',
                thousands: '',
                prefix: '',
                suffix: '',
                precision: 2,
                masked: false  
            }
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