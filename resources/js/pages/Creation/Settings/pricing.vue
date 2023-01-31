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
                    <div class="flex relative justify-end gap-8">
                        <div class="w-72"></div>
                        <div class="text-left">Quick des</div>
                        <div class="w-40">Price</div>
                    </div>
                    <Tickets
                        v-model="inputVal" />
                    <button 
                        @click="addNewTicket"
                        class="border-none underline font-semibold text-1xl mt-8">
                        Add additional ticket type.
                    </button>
                </div>
            </div>
        </div>
    </main>
</template>

<script>
    import Tickets from './tickets.vue'
    export default {

        props: ['value'],

        computed: {
            inputVal: {
                get() { return this.value },
                set(val) { this.$emit('input', val) }
            },
        },

        components: { Tickets },

        data() {
            return {
                active: '',
                dropdown: false,
                ticket: { id:1, name:'General'},
            };
        },

        methods: {
            addNewTicket() {
                this.inputVal.event.priceranges.concat({price:0.00, event_id:this.inputVal.event.id})
            }
        },

    }
</script>