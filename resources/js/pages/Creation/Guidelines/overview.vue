<template>
    <main class="transition ease-in duration-300">
        <div class="fixed w-[calc(100vw-26rem)] overflow-auto h-full left-[26rem]">
            <div class="p-12 h-28 w-full">
                <p class="font-semibold">Overview</p>
            </div>
            <div class="flex justify-center h-full w-full p-8">
                <div class="max-w-3xl">
                    <h2>What events are the right fit for Everything Immerive</h2>
                    <div class="rounded-2xl overflow-hidden my-12">
                        <img src="https://a0.muscache.com/pictures/aca23391-4bab-4ddb-91e3-3934147bbcac.jpg" alt="">
                    </div>
                    <p class="font-light">Immersive experiences are experiences that have some type of immersive aspect. They must meet these standards:</p>
                    <ul class="text-2xl mt-8 font-light">
                        <li><span class="font-semibold">Immersive: </span>Must include some way for the user to interact</li>
                        <li><span class="font-semibold">Safe: </span>If the event is in person it needs to be safe for the users.</li>
                    </ul>
                </div>
            </div>
        </div>
    </main>
</template>

<script>
    export default {

        props: ['value'],

        components: {  },

        computed: {
            inputVal: {
                get() { return this.value },
                set(val) { this.$emit('input', val) }
            },
        },

        data() {
            return {

            };
        },

        methods: {

        },

        mounted() {
            this.inputVal.submit=true
        }
    }
</script>