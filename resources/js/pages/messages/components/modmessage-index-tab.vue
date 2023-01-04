<template>
    <a :href="`/conversations/${conversation.id}`">
        <div class="message-index__element grid">
            <div class="message-index__card grid">
                <div class="message-index__image">
                    <picture>
                        <source type="image/webp" :srcset="`${$envImageUrl}${conversation.event.thumbImagePath}`"> 
                        <img :src="`${$envImageUrl}${conversation.event.thumbImagePath.slice(0, -4)}jpg`" alt="message image">
                    </picture>
                </div>
                <div class="message-index__name">
                    <p><b>{{ conversation.event.name }}</b></p>
                </div>
            </div>

            <div>
                <div 
                    :key="message.id"
                    v-for="message in firstMessage(conversation.messages)" 
                    class="message-index__message desktop">
                    <p><span>{{ message.message }}</span></p>
                </div>
                <div 
                    :key="message.id"
                    v-for="message in firstMessage(conversation.messages)" 
                    class="message-index__mdates mobile">
                    <p><span>
                        {{ cleanDate(message.updated_at) }}
                    </span></p>
                </div>
            </div>
        </div>
    </a>
</template>

<script>
    export default {
        props: ['conversation', 'loaduser'],

        computed: {
            isOwner() {
                return user => user.id === this.loaduser.id;
            },

            isNotUser() {
                return user => user.id !== this.loaduser.id ? true : false;
            },

            firstMessage() {
                return message =>  message.slice(0, 1)
            },

        },

        data() {
            return {
                userConversation: this.conversation ? this.conversation.users : '',
            }
        },

        methods: {
            cleanDate(data) {
                return this.$dayjs(data).format("MMM DD YYYY");
            }
        },

        mounted() {

        },



    };
</script>