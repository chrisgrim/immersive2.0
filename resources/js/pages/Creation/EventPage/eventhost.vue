<template>
    <main class="transition ease-in duration-300">
        <div class="fixed w-[calc(100vw-26rem)] overflow-auto h-full left-[26rem]">
            <div class="p-12 h-28 w-full">
                <p class="font-semibold">Organizer</p>
            </div>
            <div class="flex justify-center h-full w-full p-8 mb-96">
                <div class="max-w-3xl">
                    <h2 class="mb-16">The event host information</h2>
                    <div class="mb-8">
                        <p class="text-black text-3xl font-semibold mb-4">Your Organization Profile</p>
                        <p class="font-light">This information is specific to your organization, not this event.</p>
                    </div>
                    <div class="flex w-full gap-4 mb-16">
                        <ImageElement
                            class="relative w-24 h-24 min-w-[6rem] py-4 min-h-[8rem]"
                            :loading="false"
                            text="The image must be at least 400px x 400px"
                            :image="`${$envImageUrl}${value.event.organizer.thumbImagePath}`"
                            @addImage="addImage" />
                        <div>
                            <h4 class="font-medium text-black mb-4 text-2xl">{{value.event.organizer.name}}</h4>
                            <ShowMore 
                                class="font-light text-lg"
                                :text="value.event.organizer.description"
                                :limit="20" />
                        </div>
                    </div>
                    <div class="mb-8">
                        <p class="text-black text-2xl mb-4">Are you part of a team?</p>
                        <div class="flex mb-4">
                            <input 
                                v-model="hasUsers"
                                value="false"
                                class="h-8 w-8 mr-6 appearance-none rounded-full border border-[6px] bg-white checked:border-black focus:rounded-full focus:border-[6px] focus:outline-none"
                                type="radio">
                            <p class="font-light">I am the only member of this organization</p>
                        </div>
                        <div class="flex mb-4">
                            <input 
                                v-model="hasUsers"
                                value="true"
                                class="h-8 w-8 mr-6 appearance-none rounded-full border border-[6px] bg-white checked:border-black focus:rounded-full focus:border-[6px] focus:outline-none"
                                type="radio">
                            <p class="font-light">I have a team who helps edit these events</p>
                        </div>
                    </div>
                    <div 
                        v-if="hasUsers"
                        class="mt-16">
                        <div class="mb-4 w-full flex items-center justify-between">
                            <h3>Team Members</h3>
                            <button 
                                @click="addMember=!addMember"
                                class="border-none w-16 h-16 flex p-0 rounded-full justify-center items-center hover:bg-slate-300" 
                                :class="{'bg-black hover:bg-slate-300': addMember}">
                                <svg 
                                    :class="{'fill-white rotate-45': addMember}"
                                    class="w-16 h-16">
                                    <use :xlink:href="`/storage/website-files/icons.svg#ri-add-fill`" />
                                </svg>
                            </button>
                        </div>
                        <div 
                            v-if="addMember"
                            class="flex w-full h-20">
                            <textarea 
                                name="Email"
                                placeholder="Team Member Email" 
                                class="border-black h-full my-0 mr-8" 
                                v-model="user"
                                @input="clear"
                                rows="1"
                                type="text" />
                            <div 
                                @click="onAddMember"
                                class="h-full w-24 bg-gray-400 rounded-full flex items-center justify-center cursor-pointer hover:bg-black">
                                <svg class="w-12 h-12 fill-white">
                                    <use :xlink:href="`/storage/website-files/icons.svg#ri-arrow-right-s-line`" />
                                </svg>
                            </div>
                        </div>
                        <Errors 
                            item=""
                            :server-errors="serverErrors"
                            :errors="$v.user" />
                        <ul class="ml-0 py-8">
                            <li 
                                :key="member.id"
                                v-for="member in inputVal.event.organizer.users"
                                class="flex items-center">
                                <img 
                                    v-if="member.thumbImagePath"
                                    :src="`${$envImageUrl}${member.thumbImagePath}`"
                                    class="w-16 h-16 object-cover rounded-full mr-8" 
                                    alt="">
                                <div class="w-full flex justify-between">
                                    <p>{{ member.name}}</p>
                                    <p 
                                        @click="onRemoveMember(member.id)"
                                        class="text-lg underline cursor-pointer">remove member</p>
                                </div>
                            </li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </main>
</template>

<script>
    import ImageElement from './Components/imageComponent.vue'
    import ShowMore  from '../../../components/ShowMore.vue'
    import Errors from '../creationError.vue'
    import { email } from 'vuelidate/lib/validators'
    export default {

        props: ['value'],

        components: { Errors, ImageElement, ShowMore },

        computed: {
            inputVal: {
                get() { return this.value },
                set(val) { this.$emit('input', val) }
            },
        },

        data() {
            return {
                hasUsers: this.value.event.organizer.users.length > 1,
                addMember: true,
                user:'',
                serverErrors:{},
            };
        },

        methods: {
            async onAddMember() {
                this.$v.$touch()
                await axios.patch(`/experience-hosting/listing/${this.value.event.slug}/host`, {newMember: this.user})
                .then( res => {
                    this.inputVal.event.organizer.users=res.data
                    this.user=''
                    this.addMember=false
                })
                .catch(err => {
                    err ? this.serverErrors = err.response.data.errors : '';
                });
            },
            async onRemoveMember(id) {
                await axios.patch(`/experience-hosting/listing/${this.value.event.slug}/host`, {removeMember: id})
                .then( res => {
                    this.inputVal.event.organizer.users=res.data
                })
                .catch(err => {
                    setTimeout(() => this.serverErrors = null, 10000)
                });
            },
            clear() {
                this.serverErrors = {}
            },
            addImage() {

            }
        },

        validations: {
            user: {
                email,
            }
        },
    }
</script>