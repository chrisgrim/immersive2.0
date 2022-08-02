<template>
    <div class="h-full flex flex-col justify-between h-[calc(100vh-8rem)]">
        <div 
            v-if="mobile"
            class="w-full h-32 p-4 border-b px-8 flex relative">
            <button 
                aria-label="back button"
                type="button"
                @click="onBack"
                class="flex left-8 z-10 p-1 rounded-full shadow-lg bg-white">
                <svg class="h-12 w-12">
                    <use :xlink:href="`/storage/website-files/icons.svg#ri-arrow-left-s-line`" />
                </svg>
            </button>
        </div>
        <div 
            v-if="event"
            class="w-full p-4 border-b px-8 flex relative">
            <picture>
                <source 
                    type="image/webp" 
                    :srcset="`${envImageUrl}${mobile ? event.thumbImagePath : event.largeImagePath}`"> 
                <img 
                    class="h-20 md:h-44 object-cover rounded-xl"
                    :src="`${envImageUrl}${mobile ? event.thumbImagePath.slice(0, -4) : event.largeImagePath.slice(0, -4)}jpg`" 
                    :alt="`${event.name} Immersive Event`">
            </picture>
            <h2 class="ml-4 text-2xl leading-6 md:text-3xl md:leading-8">{{ event.name}} </h2>
        </div>
        

        <!-- Messages -->
        <div 
            ref="container"
            class="w-full h-full flex flex-col px-8 overflow-auto">
            <div v-if="messages">
                <div
                    v-for="message in messages"
                    :key="message.id"
                    class="my-4 first:mt-8 last:mb-8">
                    <div class="flex w-full">
                        <div 
                            :style="`background:${message.user.hexColor}`" 
                            class="cursor-pointer overflow-hidden flex justify-center items-center w-14 h-14 rounded-lg mr-2">
                            <template v-if="message.user.largeImagePath">
                                <picture>
                                    <source 
                                        type="image/webp" 
                                        :srcset="`${envImageUrl}${message.user.thumbImagePath}`"> 
                                    <img 
                                        class="w-14 h-14"
                                        :src="`${envImageUrl}${message.user.thumbImagePath.slice(0, -4)}jpg`" >
                                </picture>
                            </template>
                            <template v-else-if="message.user.gravatar">
                                <img 
                                    :src="message.user.gravatar" 
                                    class="w-10 h-10">
                            </template>
                            <template v-else>
                                <span>{{ message.user.name.charAt(0) }}</span>
                            </template>
                        </div>
                        <div>
                            <div>
                                <a 
                                    :href="`/users/${message.user.id}`"
                                    class="font-black text-2xl hover:underline"> 
                                    {{ message.user.name }} 
                                </a>
                                <span class="text-xl text-slate-400">{{ cleanTime(message.created_at) }} </span>
                            </div>
                            <div>
                                <p class="text-2xl" v-html="message.message" />
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="flex-end px-8 mt-[-1rem]">
            <tiptap 
                @save="onSubmit"
                :disabled="disabled"
                :class="{ 'error': $v.newMessage.$error }"
                v-model="newMessage" />
            <div v-if="$v.newMessage.$error" class="validation-error">
                <p class="error" v-if="!$v.newMessage.required">Please add a description.</p>
                <p class="error" v-if="!$v.newMessage.maxLength">The description is too long.</p>
            </div>
        </div>
    </div>
</template>

<script>
    import formValidationMixin from '../../mixins/form-validation-mixin'
    import { required, maxLength } from 'vuelidate/lib/validators';
    import Tiptap from './Components/Tiptap.vue'
    export default {

        mixins: [formValidationMixin],

        props: ['user', 'value', 'mobile'],

        components: { Tiptap },

        computed: {
            inputVal: {
                get() { return this.value },
                set(val) { this.$emit('input', val) }
            },
            event() {
                return this.value.event
            },
            messages() {
                return this.value.messages
            },
            submitObject() {
                return {
                    message: this.newMessage, 
                    type:this.isMessage ? 'message' : 'event',
                    event: this.event ? this.event : '',
                };
            },
        },

        data() {
            return {
                conversation: this.value,
                newMessage: null,
                disabled: false,
                envImageUrl: process.env.MIX_IMAGE_URL,
            }
        },

        methods: {
            async onSubmit() {
                if (this.checkVuelidate()) { return false }
                await axios.post(`/conversations/${this.value.id}`, this.submitObject)
                .then( res => {
                    console.log(res.data);
                    this.inputVal = res.data
                    this.reset()
                    this.scrollToEnd()
                })
            },
            onBack() {
                this.$emit('back');
            },
            cleanDate(data) {
                return this.$dayjs(data).format("MMM DD YYYY");
            },
            cleanTime(data) {
                return this.$dayjs(data).format("h:mm A");
            },
            reset() {
                this.newMessage = null
                this.disabled = false
                this.$v.$reset()
            },
            scrollToEnd: function() { 
                var container = this.$refs.container
                this.$nextTick(() => {
                    container.scrollTop = container.scrollHeight;
                });
            },
        },

        watch: {
            value() {
                this.scrollToEnd()
            }
        },

        mounted() {
            this.scrollToEnd()
        },

        validations: {
            newMessage: {
                required,
                maxLength: maxLength(40000)
            },
        },


    };
</script>