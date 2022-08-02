<template>
    <div class="event-edit py-8 px-12 md:py-20 md:px-32">
        <div class="flex w-full items-center">
            <div class="w-full mr-8 md:w-4/12 relative">
                <v-select 
                    v-model="organizer"
                    label="name"
                    @input="selectOrganizer"
                    placeholder="Organizations"
                    :clearable="false"
                    :options="organizers" />
                <svg class="w-12 h-12 right-4 top-4 z-100 absolute">
                    <use :xlink:href="`/storage/website-files/icons.svg#ri-arrow-down-s-line`" />
                </svg>
            </div>
            <a 
                @mouseover="hover = true" 
                @mouseleave="hover = false"
                href="/organizer/create"
                class="flex rounded-full mr-8 justify-center items-center w-20 h-20 hover:bg-slate-200">
                <svg class="w-20 h-20">
                    <use :xlink:href="`/storage/website-files/icons.svg#ri-add-fill`" />
                </svg>
            </a>
            <p 
                class="border p-4 rounded-2xl" 
                v-if="hover">Add another organization</p>
        </div>
        <template v-if="organizer">
            <div class="mt-28">
                <div class="flex justify-between items-center">
                    <div class="flex items-center">
                        <div 
                            class="mr-8 w-20 h-20 bg-cover bg-no-repeat rounded-full flex items-end justify-center mr-8" 
                            :style="organizer.thumbImagePath ? `background-image:url('${envImageUrl}${organizer.thumbImagePath.slice(0, -4)}jpg')` : `background:${organizer.hexColor}`">
                            <p 
                                class="text-5xl text-white font-extrabold m-auto" 
                                v-if="!organizer.thumbImagePath">{{ organizer.name.charAt(0) }}
                            </p>
                        </div>
                        <div class="organizer-card__title">
                            {{ organizer.name }}
                        </div>
                    </div>
                    <div class="flex items-center gap-4">           
                        <button 
                            @click.prevent="newEvent" 
                            class="px-6 py-2 text-white float-right bg-gradient-to-r from-button-red-1 via-button-red-2 to-button-red-3 border-none"> 
                            Add Event 
                        </button>
                        <a 
                            v-if="organizer.status === 'p'" 
                            target="_blank" 
                            class="px-6 py-2 border hover:bg-slate-200"
                            :href="`/organizer/${organizer.slug}`">
                            Preview Organizer
                        </a>
                        <button 
                            v-if="archived"
                            @click="archived = false"
                            class="px-6 py-2 border hover:bg-slate-200">
                            Show Listed
                        </button>
                        <button 
                            v-if="!archived && organizer.archived_events.length"
                            @click="archived=true"
                            class="px-6 py-2 border hover:bg-slate-200">
                            Show Archived
                        </button>
                        <a 
                            class="px-6 py-2 border hover:bg-slate-200"
                            :href="`/organizer/${organizer.slug}/edit`">
                            Edit Organizer
                        </a>
                        <button      
                            v-if="!organizer.listed_events.length && !organizer.archived_events.length"             
                            @click.prevent="selectedModal=organizer" 
                            class="px-6 py-2 border hover:bg-slate-200">
                            Delete
                        </button>
                    </div>
                </div>
                <EditTable 
                    @update="updateOrganizer"
                    :archived="archived"
                    :events="events" />
            </div>
        </template>
        <VueModalAccept 
            @onSubmit="newUserLimited = false"
            @close="newUserLimited = false"
            :accept="false"
            v-if="newUserLimited">
            <h3>Notice</h3>
            <p>New users may only create additional events after their first couple of events are approved and published. This ensures that you’ve had the chance to try out every step of our process.</p>
        </VueModalAccept>
        <VueModalAccept 
            @onSubmit="limited = false"
            @close="limited = false"
            :accept="false"
            v-if="limited">
            <h3>Notice</h3>
            <p>We only allow up to 5 unpublished events at any time. If you would like to add more events, please submit your current events for approval.</p>
        </VueModalAccept>
        <VueModalDelete
            v-if="selectedModal"
            :item="selectedModal"
            :strict="true"
            body="You are deleting your organization. Please be sure you know what you are doing."
            @close="selectedModal=null"
            @ondelete="deleteOrganizer" />
    </div>
</template>

<script>
    import VueModalAccept from '../../components/modals/Vue-Modal-Accept'
    import VueModalDelete from '../../components/modals/Vue-Modal-Delete'
    import EditTable from './Edit/event-edit-table'
    export default {

        props: ['user', 'published', 'unpublished'],

        components: { VueModalAccept, VueModalDelete, EditTable },

        computed: {
            events() {
                return this.archived ? this.organizer.archived_events : this.organizer.listed_events
            }
        },

        data() {
            return {
                active: true,
                organizer: '',
                organizers: [],
                limited: false,
                newUserLimited: false,
                selectedModal: null,
                archived: false,
                hover: false,
                envImageUrl: process.env.MIX_IMAGE_URL,
            }
        },

        methods: {
            async onload() {
                await axios.get(`/create/organizers/fetch?timestamp=${new Date().getTime()}`)
                .then(res => {
                    this.organizers = res.data;
                    this.organizer = this.displayCurrentOrganizer(res.data);
                })
            },
            async selectOrganizer(organizer) {
                await axios.post(`/assign/organizer/${organizer.slug}`)
            },
            async newEvent() {
                if (this.checkPermission()) return ;
                await axios.post(`/events`, this.organizer)
                .then(res => { 
                    window.location.href = `/create/${res.data.slug}/title`; 
                })
                .catch(error => { this.serverErrors = error.response.data.errors; });
            },
            async deleteOrganizer() {
                await axios.delete(`/organizer/${this.selectedModal.slug}`, this.selectedModal)
                location.reload();
            },
            updateOrganizer(val) {
                this.organizer = val
            },
            displayCurrentOrganizer(organizers) {
                if (this.user.current_team_id) {
                    return organizers.find(organizer => organizer.id === this.user.current_team_id)
                }
                return organizers[0];
            },
            checkPermission() {
                if (this.user.type !== 'g') {return false}
                if (this.published === 0 && this.unpublished >= 2) {
                    this.newUserLimited = true
                    return true
                }
                if (this.unpublished > 4) {
                    this.limited = true;
                    return true;
                }
                return false;
            },
        },

        mounted() {
            this.onload();
        },
    }
</script>