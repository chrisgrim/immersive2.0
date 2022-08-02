<template>
    <div class="left-0">
        <div>
            <nav class="w-[26rem] z-[3001]">
                <div class="w-[26rem] absolute top-0 h-full bg-[#f7f7f7]">
                    <div class="h-full overflow-auto px-12 py-8 shadow-[inset_-4px_0px_20px_rgb(0,0,0,0.05)]">
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
                            <NavIdea v-model="inputVal" />
                            <NavGuidelines v-model="inputVal" />
                            <NavEventPage
                                @submit="onSubmit"
                                v-model="inputVal" />
                            <NavSettings
                                v-model="inputVal" />
                        </div>
                    </div>
                </div>
            </nav>
        </div>
    </div>
</template>
<script>
    import NavIdea  from './Idea/nav-idea.vue'
    import NavGuidelines  from './Guidelines/nav-guidelines.vue'
    import NavEventPage from './Eventpage/nav-eventpage.vue'
    import NavSettings from './Settings/nav-settings.vue'
    export default {

        props: [ 'value' ],

        components: { NavIdea, NavGuidelines, NavEventPage, NavSettings },

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
            onSubmit() {
                this.$emit('submit')
            }
        }

    }
</script>