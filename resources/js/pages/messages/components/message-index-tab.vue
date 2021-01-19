<template>
    <a :href="`/conversations/${conversation.id}`">
        <div class="message-index__element grid">
            <div 
                v-for="user in userConversation" 
                :key="user.id"
                class="message-index__card grid">
                <div class="message-index__image" :style="`background:${user.hexColor}`">
                    <label v-if="user.largeImagePath">
                        <picture>
                            <source type="image/webp" :srcset="`/storage/${user.thumbImagePath}`"> 
                            <img :src="`/storage/${user.thumbImagePath.slice(0, -4)}jpg`" :alt="user.name + `'s account`">
                        </picture>
                    </label>
                    <div v-else-if="user.gravatar">
                        <img :src="user.gravatar" :alt="user.name + `'s account`">
                    </div>
                    <div 
                        v-else 
                        class="message-index__user-noimage">
                        <h2>{{ user ? user.name.charAt(0) : '' }}</h2>
                    </div>
                </div>
                <div class="message-index__name">
                    <p><b>{{ user.name }}</b></p>
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

            isNotUser() {
                return user => user.id !== this.loaduser.id ? true : false;
            },

            firstMessage() {
                return message =>  message.slice(0, 1)
            },

        },

        data() {
            return {
                userConversation: this.conversation ? this.conversation.users.filter( user => { return user.id !== this.loaduser.id }) : ''
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