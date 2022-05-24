<template>
    <main class="transition ease-in duration-300">
        <div class="fixed w-[calc(100vw-26rem)] overflow-hidden h-full left-[26rem]">
            <div class="p-12 h-28 w-full">
                <p class="font-semibold">Category</p>
            </div>
            <div class="flex justify-center h-full w-full p-8">
                <div class="max-w-3xl">
                    <h2>What Category does your event fall into??</h2>
                    
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
            
        }
    }
</script>