<template>
    <div class="max-w-screen-2xl relative">
        <section class="my-8 md:mt-16 md:mb-24">
            <div class="px-8 my-8 md:px-12 lg:px-32 lg:my-12">
                <h2>Notifications</h2>
                <section class="w-2/3 flex justify-between items-center gap-12 border-b my-4">
                    <div class="py-8">
                        <h4 class="text-2xl font-medium mb-2">Subscribe to monthly newsletter</h4>
                        <p class="text-2xl text-gray-500">Get our monthly newsletters about the latest and greatest immersive events.</p>
                    </div>
                    <div id="cover">
                        <input 
                            v-model="options.monthly" 
                            type="checkbox"
                            id="checkbox">
                        <div id="bar"></div>
                        <div id="knob">
                            <p v-if="options.monthly">Yes</p>
                            <p v-else>No</p>
                        </div>
                    </div>
                </section>
                <section class="w-2/3 flex justify-between items-center gap-12 border-b my-4">
                    <div class="py-8">
                        <h4 class="text-2xl font-medium mb-2">Subscribe to event update newsletters</h4>
                        <p class="text-2xl text-gray-500">Get the latest updates about the organizations and events you have liked on EI.</p>
                    </div>
                    <div id="cover">
                        <input 
                            v-model="options.updates" 
                            type="checkbox" 
                            id="checkbox">
                        <div id="bar"></div>
                        <div id="knob">
                            <p v-if="options.updates">Yes</p>
                            <p v-else>No</p>
                        </div>
                    </div>
                </section>
                <section class="w-2/3 flex justify-between items-center gap-12 border-b my-4">
                    <div class="py-8">
                        <h4 class="text-2xl font-medium mb-2">Messages</h4>
                        <p class="text-2xl text-gray-500">Get an email whenever a user or admin sends you a message</p>
                    </div>
                    <div id="cover">
                        <input 
                            v-model="options.messages" 
                            type="checkbox" 
                            id="checkbox">
                        <div id="bar"></div>
                        <div id="knob">
                            <p v-if="options.messages">Yes</p>
                            <p v-else>No</p>
                        </div>
                    </div>
                </section>
            </div>
        </section>
        <transition name="slide-fade">
            <div 
                v-if="updated" 
                class="fixed top-16 right-16 z-[2003] rounded-2xl bg-green-500 p-8">
                <p class="text-white">Your notifications have been updated.</p>
            </div>
        </transition>
    </div>
</template>
<script>



    export default {

        props: ['user', 'mobile'],

        data() {
            return {
                options: this.initializeOptions(),
                updated: false,
            }
        },

        methods: {
            async submit() {
                await axios.put(`/users/${this.user.id}`, this.options)
                .then( res => {
                    this.updated = true
                    setTimeout(() => this.updated = false, 3000)
                })
            },
            initializeOptions() {
                return {
                    monthly: this.user.newsletter_type == 'n' || this.user.newsletter_type == 'u' ? false : true,
                    updates: this.user.newsletter_type == 'a' || this.user.newsletter_type == 'u' ? true : false,
                    messages: this.user.silence == 'y' ? false : true,
                }
            }
        },

        watch: {
            options: {
                handler() {
                    this.submit()
                },
                deep: true
            }
        }

    };
</script>