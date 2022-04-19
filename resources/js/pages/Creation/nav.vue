<template>
    <div class="left-0">
        <div>
            <nav class="w-[26rem] z-[3001]">
                <div class="w-[26rem] absolute top-0 h-full bg-[#f7f7f7]">
                    <div class="h-full overflow-hidden px-12 py-8">
                        <!-- Nav top bar -->
                        <div class="flex justify-between items-center flex-wrap">
                            <div>
                                Logo
                            </div>
                            <div class="ml-4">
                                <button class="bg-transparent border-none text-xl underline text-black">Save and Exit</button>
                            </div>
                        </div>
                        <!-- Nav Title -->
                        <h2 class="my-12 text-4xl">Submit your event</h2>
                        <!-- Nav menu -->
                        <div class="relative">
                            <NavIdea
                                :event="event" />
                        </div>
                    </div>
                </div>
            </nav>
        </div>
    </div>
</template>
<script>
    import NavIdea  from './Idea/nav-idea.vue'
    export default {

        props: [ 'event' ],

        components: { NavIdea },

        data() {
            return {
                
            };
        },

    }
</script>