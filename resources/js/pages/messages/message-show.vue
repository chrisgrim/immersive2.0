<template>
    <div>
        <div class="messages-show">
            <!-- Nav Section -->
            <nav class="account-notifications_header desktop">
                <div class="account-notifications_breadcrumbs">
                    <a href="/messages">Inbox</a> 
                    <span v-if="isMessage">
                        - {{ responder.name }}
                    </span>
                    <span v-else>
                        - {{ loadmessages.event.name }}
                    </span>
                </div>
            </nav>
            <nav class="event-show mobile">
                <div class="back">
                    <a href="/messages">
                        <svg 
                            aria-label="Back" 
                            role="img" 
                            viewBox="0 0 32 32" 
                            xmlns="http://www.w3.org/2000/svg" 
                            style="display: block; fill: none; height: 16px; width: 16px; stroke: currentcolor; stroke-width: 4; overflow: visible;">
                            <g fill="none">
                                <path d="m20 28-11.29289322-11.2928932c-.39052429-.3905243-.39052429-1.0236893 0-1.4142136l11.29289322-11.2928932" />
                            </g>
                        </svg>
                    </a>
                </div>
            </nav>
            <!-- Body Section -->
            <div 
                class="messages-show-content grid" 
                :class="{single: isMessage}">
                <!-- Event Image Section -->
                <div v-if="!isMessage">
                    <a :href="url">
                        <div class="messages-show-event">
                            <div class="messages-show-event__image">
                                <picture>
                                    <source type="image/webp" :srcset="`/storage/${loadmessages.event.thumbImagePath}`"> 
                                    <img :src="`/storage/${loadmessages.event.thumbImagePath.slice(0, -4)}jpg`" :alt="user.name + `'s account`">
                                </picture>
                            </div>
                            <div class="messages-show-event__title">
                                <h3>{{ loadmessages.event.name }}</h3>
                            </div>
                        </div>
                    </a>
                </div>
                <!--Message Section -->
                <div>
                    <div class="messages-show__element grid submit">
                        <div class="field">
                            <div class="messages-show__element-textarea">
                                <textarea 
                                    type="text" 
                                    v-model="message" 
                                    rows="4" />
                                <div v-if="$v.message.$error" class="validation-error">
                                    <p class="error" v-if="!$v.message.required">Please write something.</p>
                                </div>
                            </div>
                            <div class="messages-show__submit-bottom">
                                <button 
                                    :class="{bspin : disabled}" 
                                    :disabled="disabled" 
                                    @click="onSubmit">
                                    Send message
                                </button>
                            </div>
                        </div>
                        <div>
                            <div class="message-index__image" :style="`background:${owner.hexColor}`">
                                <label 
                                    v-if="owner.largeImagePath" 
                                    class="profile-image">
                                    <picture>
                                        <source type="image/webp" :srcset="`/storage/${owner.thumbImagePath}`"> 
                                        <img :src="`/storage/${owner.thumbImagePath.slice(0, -4)}jpg`" :alt="owner.name + `'s account`">
                                    </picture>
                                </label>
                                <div 
                                    v-else-if="owner.gravatar" 
                                    class="profile-image">
                                    <img 
                                        :src="owner.gravatar" 
                                        :alt="owner.name + `'s account`">
                                </div>
                                <div 
                                    v-else 
                                    class="message-index__user-noimage">
                                    <h2>{{ owner ? owner.name.charAt(0) : '' }}</h2>
                                </div>
                            </div>
                            <div class="message-show__username">
                                {{ owner.name }}
                            </div>
                        </div>
                    </div>

                    <div
                        v-for="message in conversationType"
                        :key="message.id">
                        <div 
                            class="messages-show__element grid"
                            v-if="isOwner(message)">
                            <div class="field">
                                <div class="messages-show__element-textarea">
                                    <div>
                                        {{ message.message ? message.message : message.comments }}
                                    </div>
                                    <div class="messages-show__element-date">
                                        {{ cleanDate(message.created_at) }}
                                    </div>
                                </div>
                            </div>
                            <div class="messages-show__element-image">
                                <div 
                                    class="message-index__image" 
                                    :style="`background:${owner.hexColor}`">
                                    <label 
                                        v-if="owner.largeImagePath" 
                                        class="profile-image" >
                                        <picture>
                                            <source type="image/webp" :srcset="`/storage/${owner.thumbImagePath}`"> 
                                            <img :src="`/storage/${owner.thumbImagePath.slice(0, -4)}jpg`" :alt="owner.name + `'s account`">
                                        </picture>
                                    </label>
                                    <div 
                                        v-else-if="owner.gravatar" 
                                        class="profile-image">
                                        <img :src="owner.gravatar" :alt="owner.name + `'s account`">
                                    </div>
                                    <div 
                                        v-else 
                                        class="message-index__user-noimage">
                                        <h2>{{ owner ? owner.name.charAt(0) : '' }}</h2>
                                    </div>
                                </div>
                                <div class="message-show__username">
                                    {{ owner.name }}
                                    <b v-if="owner.type == 'a'">
                                        admin
                                    </b>
                                </div>
                            </div>
                        </div>
                        <div 
                            class="messages-show__element grid" 
                            v-else
                            :class="{inv: isResponder(message)}">
                            <div class="messages-show__element-image">
                                <div 
                                    class="message-index__image" 
                                    :style="`background:${message.user.hexColor}`">
                                    <label 
                                        v-if="message.user.largeImagePath" 
                                        class="profile-image">
                                        <picture>
                                            <source type="image/webp" :srcset="`/storage/${message.user.thumbImagePath}`"> 
                                            <img :src="`/storage/${message.user.thumbImagePath.slice(0, -4)}jpg`" :alt="message.user.name + `'s account`">
                                        </picture>
                                    </label>
                                    <div 
                                        v-else-if="message.user.gravatar" 
                                        class="profile-image">
                                        <img :src="message.user.gravatar" :alt="message.user.name + `'s account`">
                                    </div>
                                    <div 
                                        v-else 
                                        class="message-index__user-noimage">
                                        <h2>{{ message.user ? message.user.name.charAt(0) : '' }}</h2>
                                    </div>
                                </div>
                                <div class="message-show__username">
                                    {{ message.user.name }}
                                    <b v-if="message.user.type == 'a'">admin</b>
                                </div>
                            </div>
                            <div class="field">
                                <div class="messages-show__element-textarea">
                                    <div>
                                        {{ message.message ? message.message : message.comments }}
                                    </div>
                                    <div class="messages-show__element-date">
                                        {{ cleanDate(message.created_at) }}
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="message-bar-mobile">
            <input 
                type="text"
                v-model="message" 
                placeholder="Write message here" 
                @keyup.enter="onSubmit"
                class="message-bar__input">
            <button 
                :disabled="disabled" 
                @click="onSubmit" 
                class="message-bar__submit">
                <svg 
                    :class="{disabled : disabled}" 
                    class="svgbutton" 
                    role="presentation" 
                    aria-hidden="true" 
                    focusable="false" 
                    width="24" 
                    height="24" 
                    viewBox="0 0 24 24" 
                    fill="none" 
                    xmlns="http://www.w3.org/2000/svg" 
                    style="height: 24px; width: 24px; display: block;">
                    <circle cx="12" cy="12" r="12" />
                    <g clip-path="url(#clipeSendIcon33)">
                        <path 
                            transform="scale(1)" 
                            fill-rule="evenodd" 
                            clip-rule="evenodd" 
                            d="M11.2929 6.29289C11.6834 5.90237 12.3166 5.90237 12.7071 6.29289L16.7071 10.2929C17.0976 10.6834 17.0976 11.3166 16.7071 11.7071C16.3166 12.0976 15.6834 12.0976 15.2929 11.7071L13 9.41421V17C13 17.5523 12.5523 18 12 18C11.4477 18 11 17.5523 11 17V9.41421L8.70711 11.7071C8.31658 12.0976 7.68342 12.0976 7.29289 11.7071C6.90237 11.3166 6.90237 10.6834 7.29289 10.2929L11.2929 6.29289Z" 
                            fill="white" />
                    </g>
                    <defs>
                        <clipPath id="clipeSendIcon33">
                            <rect width="12" height="12" fill="white" transform="translate(6 6)" />
                        </clipPath>
                    </defs>
                </svg>
            </button>
        </div>
    </div>
</template>

<script>
    import formValidationMixin from '../../mixins/form-validation-mixin'
    import { required } from 'vuelidate/lib/validators';

    export default {

        mixins: [formValidationMixin],

        props: ['loaduser', 'loadmessages'],

        components: {},

        computed: {
            isOwner() {
                return message => message.user_id === this.loaduser.id ? true : false;
            },

            isResponder() {
                return message => message.user_id !== this.loaduser.id ? true : false;
            },

            isMessage() {
                return this.loadmessages.event ? false : true
            },

            endpoint() {
                return `/conversations/${this.conversations.id}`
            },

            submitObject() {
                return {
                    message: this.message, 
                    type:this.isMessage ? 'message' : 'event',
                    event: this.loadmessages.event ? this.loadmessages.event : ''
                };
            },

            url() {
                if (this.loadmessages.event.status == 'p') {
                    return `/events/${this.loadmessages.event.slug}`;
                }
                if (this.loaduser.type == 'a') {
                    return `/admin/events/${this.loadmessages.event.slug}/finalize`;
                }
                return `/create/${this.loadmessages.event.slug}/title`;
            }

        },

        data() {
            return {
                conversationType: this.loadmessages ? this.loadmessages.messages : this.loadmessages.modmessages,
                user: this.loaduser ? this.loaduser : '',
                conversations: this.loadmessages ? this.loadmessages : '',
                message: '',
                owner: '',
                responder: '',
                disabled: false,
                avatar: this.loaduser.thumbImagePath ? `/storage/${this.loaduser.thumbImagePath}` : '',
            }
        },

        methods: {
            async onSubmit() {
                if (this.checkVuelidate()) { return false }
                await axios.post(this.endpoint, this.submitObject)
                location.reload();
            },

            getOwner() {
                this.loadmessages.users.forEach(user => {
                    user.id == this.loaduser.id ? this.owner = user : '';
                    user.id !== this.loaduser.id ? this.responder = user : '';
                })
            },

            cleanDate(data) {
                return this.$dayjs(data).format("MMM DD YYYY");
            }
        },


        mounted() {
           this.getOwner();
        },



        validations: {
            message: {
                required,
            },
        },


    };
</script>