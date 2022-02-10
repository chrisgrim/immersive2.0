<template>
    <div class="w-full grid grid-cols-5 md:h-[calc(100vh-8rem)]">
        <div class="bg-slate-700 overflow-auto">
            <div class="my-8">
                <div 
                    @click="eventsOpen =! eventsOpen"
                    class="flex items-center cursor-pointer">
                    <svg 
                        :class="{ 'rotate-[-90deg]' : !eventsOpen }"
                        class="w-8 h-8 mx-4 fill-white">
                        <use :xlink:href="`/storage/website-files/icons.svg#ri-arrow-down-s-line`" />
                    </svg>
                    <p class="text-white text-xl">Events</p>
                </div>
                <div 
                    class="mt-4" 
                    v-if="eventsOpen">
                    <div class="px-8 py-2">
                        <input 
                            @input="searchEvents"
                            v-model="eventSearch"
                            class="p-2 bg-slate-600 text-white text-opacity-50 rounded-lg" 
                            placeholder="Filter by name" 
                            type="text">
                    </div>
                    <div 
                        @click="fetchConversation(eventConvo.id)"
                        :key="eventConvo.id"
                        class="flex cursor-pointer items-center px-8 py-2 hover:bg-slate-800 relative" 
                        v-for="eventConvo in eventList">
                        <div class="relative">
                            <svg class="w-6 h-6 fill-white">
                                <use :xlink:href="`/storage/website-files/icons.svg#ri-ticket-line`" />
                            </svg>
                        </div>
                        <span 
                            :class="eventConvo.id == url ? 'text-opacity-100' : 'text-opacity-50' "
                            class="mr-auto ml-2 whitespace-nowrap overflow-hidden text-ellipsis text-white text-xl">{{eventConvo.event_name}}</span>
                    </div>
                </div>
            </div>
        </div>
        <div class="flex flex-col col-span-4 relative h-full">
            <Conversation 
                v-if="conversation"
                :user="user"
                :mobile="mobile"
                v-model="conversation" />
        </div>
    </div>
</template>

<script>
    import Conversation from './message-show'
    export default {

        props: ['user', 'mobile', 'events'],

        components: { Conversation },

        computed: {
        },

        data() {
            return {
                eventList: this.events,
                messageList: this.messages,
                conversation: null,
                messagesOpen: false,
                eventsOpen: true,
                eventSearch: null,
                messageSearch: null,
                url: new URL(window.location.href).searchParams.get("event")
            }
        },

        methods: {
            async fetchConversation(convo) {
                this.url = convo
                await axios.get(`/conversations/${convo}`)
                .then( res => {
                    this.conversation = res.data
                    history.pushState(null, null,`/messages?event=${convo}`)
                })

            },
            searchEvents() {
                if (this.timeout) 
                    clearTimeout(this.timeout); 
                this.timeout = setTimeout(() => {
                    this.fetchEvents();
                }, 300); // delay
            },
            searchMessages() {
                if (this.timeout) 
                    clearTimeout(this.timeout); 
                this.timeout = setTimeout(() => {
                    this.fetchMessages();
                }, 300); // delay
            },
            async fetchEvents() {
                await axios.post(`/conversations/fetch/eventmessages`, {search: this.eventSearch})
                .then( res => {
                    this.eventList = res.data
                })
            },
            async fetchMessages() {
                await axios.post(`/conversations/fetch/messages`, {search: this.messageSearch})
                .then( res => {
                    this.messageList = res.data
                })
            },

        },

        mounted() {
            this.url ? this.fetchConversation(this.url) : null
        },

        validations: {
          
        },





    };
</script>