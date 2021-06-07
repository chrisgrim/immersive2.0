<template>
    <div class="event-edit">
        <div class="organizer--select">
            <v-select 
                v-model="organizer"
                label="name"
                @input="selectOrganizer"
                placeholder="Organizations"
                :clearable="false"
                :options="organizers" />
        </div>
        <a href="/organizer/create">
            <button style="height:3.9rem;" class="outline"> Add another organization </button>
        </a>
        <template v-if="organizer">
            <div class="event-edit__organizer">
                <div class="organizer-card grid">
                    <div 
                        class="organizer-card__image" 
                        :style="organizer.thumbImagePath ? `background-image:url('/storage/${organizer.thumbImagePath.slice(0, -4)}jpg')` : `background:${organizer.hexColor}`">
                        <div 
                            class="organizer-card__image-icontext" 
                            v-if="!organizer.thumbImagePath">
                            <h2>{{ organizer.name.charAt(0) }}</h2>
                        </div>
                    </div>
                    <div class="organizer-card__title">
                        {{ organizer.name }}
                    </div>
                    <div class="organizer-card__nav">           
                        <button 
                            @click.prevent="newEvent" 
                            class="solid"> Add Event </button>
                        <a 
                            v-if="organizer.status === 'p'" 
                            target="_blank" 
                            :href="`/organizer/${organizer.slug}`">
                            <button class="outline">
                                Preview Organizer
                            </button>
                        </a>
                        <a :href="`/organizer/${organizer.slug}/edit`">
                            <button class="outline">Edit</button>
                        </a>
                        <template v-if="!organizer.events.length">
                            <button                   
                                @click.prevent="showModal(organizer, 'deleteOrg')" 
                                class="outline">
                                Delete
                            </button>
                        </template>
                    </div>
                </div>


                <div class="data-grid">
                    <div class="data-grid__row header">
                        <p>Status</p>
                        <p>Event</p>
                        <p class="center">To Do</p>
                        <p class="center">Show Dates Remaining</p>
                        <p>Last Modified</p>
                    </div>
                    <div
                        :class="{'is-past': isShowing(event)}"
                        class="data-grid__row"
                        v-for="(event, index) in organizer.events"
                        :key="event.id">
                        <div class="center">
                            <div
                                @mouseover="active = index" 
                                @mouseleave="active = ''"
                                :style="getStatusCircle(event)"
                                class="status-circle" />
                            <div 
                                v-if="active === index"
                                class="hover-box">
                                <p>{{ getStatusDetail(event) }}</p>
                            </div>
                        </div>
                        <div class="lg">
                            <template v-if="locked(event)">
                                <img 
                                    v-if="event.thumbImagePath"
                                    :src="`/storage/${event.thumbImagePath}`">
                                <p v-if="event.name">{{ event.name }}</p>
                                <p v-else>New Event</p>
                            </template>
                            <template v-else>
                                <a 
                                    target="_blank"
                                    :href="`/create/${event.slug}/title`">
                                    <img 
                                        v-if="event.thumbImagePath"
                                        :src="`/storage/${event.thumbImagePath}`">
                                    <p v-if="event.name">{{ event.name }} 
                                        <span 
                                            style="text-decoration: underline;" 
                                            v-if="event.status === 'p'">
                                            (edit live event)
                                        </span>
                                    </p>
                                    <p v-else>New Event</p>
                                </a>
                            </template>
                        </div>
                        <div class="center">
                            <p v-if="event.status === 'r'">
                                {{ getStatus(event) }}
                            </p>
                            <a 
                                v-else
                                target="_blank"
                                :href="getUrl(event)">
                                <button class="noBox">
                                    {{ getStatus(event) }}
                                </button>
                            </a>
                        </div>
                        <div class="remaining center">
                            <template v-if="locked(event)">
                                <div v-if="event.status > '3'">
                                    <template v-if="event.showtype === 'a' || event.showtype === 'o'">
                                        <p>Ongoing</p>
                                    </template>
                                    <template v-else>
                                        <p>{{ datesRemaining(event).length }}</p>
                                    </template>
                                </div>
                                <p v-else>
                                    -
                                </p>
                            </template>
                            <template v-else>
                                <a 
                                    v-if="event.status > '3'"
                                    :href="`/create/${event.slug}/shows`">
                                    <template v-if="event.showtype === 'a' || event.showtype === 'o'">
                                        <template v-if="isShowing(event)">
                                            <button class="noBox">
                                                Add more time
                                            </button>
                                        </template>
                                        <template v-else>
                                            <button class="noBox">
                                                Ongoing
                                            </button>
                                        </template>
                                    </template>
                                    <template v-else>
                                        <button class="noBox">
                                            {{ datesRemaining(event).length }}
                                        </button>
                                    </template>
                                </a>
                                <p v-else>
                                    -
                                </p>
                            </template>
                        </div>
                        <div>
                            <p>{{ cleanDate(event.updated_at) }}</p>
                        </div>
                    </div>
                </div>
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
    </div>
</template>

<script>
    import VueModalAccept from '../../components/Vue-Modal-Accept'
    export default {

        props: ['user', 'published', 'unpublished'],

        components: { VueModalAccept },

        computed: {
            datesRemaining() {
                return event => event.shows.filter( show => this.$dayjs().subtract(1, 'day').format('YYYY-MM-DD 23:59:00') < show.date )
            },
            getStatus() {
                return event => this.status(event)
            },
            getStatusDetail() {
                return event => this.statusDetail(event)
            },
            getUrl() {
                return event => this.url(event)
            },
            getStatusCircle() {
                return event => this.statusCircle(event)
            },
            locked() {
                return event => this.isLocked(event)
            },
            isShowing() {
                return event => event.status === 'p' && !event.isShowing
            },
        },

        data() {
            return {
                active: true,
                organizer: '',
                organizers: [],
                limited: false,
                newUserLimited: false,
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

            cleanDate(data) {
                return this.$dayjs(data).format("MMM DD, YYYY");
            },

            status(event) {
                if (this.isShowing(event)) return 'expiring soon';
                if (event.status === '0') return 'add event title';
                if (event.status === '1') return 'add physical/remote location';
                if (event.status === '2') return 'add category';
                if (event.status === '3') return 'add show dates';
                if (event.status === '4') return 'add pricing';
                if (event.status === '5') return 'add show description';
                if (event.status === '6') return 'add advisories';
                if (event.status === '7') return 'add image';
                if (event.status === '8') return 'submit event';
                if (event.status === 'p') return 'approved';
                if (event.status === 'r') return 'under moderator review';
                if (event.status === 'n') return 'revise and resubmit';
                if (event.status === 'e') return `event live at ${this.cleanDate(event.embargo_date)}`;
                return '-'
            },

            statusDetail(event) {
                if (this.isShowing(event)) return 'expiring soon';
                if (event.status === '0') return 'In progress';
                if (event.status === '1') return 'In progress';
                if (event.status === '2') return 'In progress';
                if (event.status === '3') return 'In progress';
                if (event.status === '4') return 'In progress';
                if (event.status === '5') return 'In progress';
                if (event.status === '6') return 'In progress';
                if (event.status === '7') return 'In progress';
                if (event.status === '8') return 'In progress';
                if (event.status === 'p') return 'Approved';
                if (event.status === 'r') return 'Under review';
                if (event.status === 'n') return 'Fix moderator notes';
                if (event.status === 'e') return `Visible on EI at ${this.cleanDate(event.embargo_date)}`;
                return '-'
            },

            url(event) {
                if (event.status === '0') return `/create/${event.slug}/title`;
                if (event.status === '1') return `/create/${event.slug}/location`;
                if (event.status === '2') return `/create/${event.slug}/category`;
                if (event.status === '3') return `/create/${event.slug}/shows`;
                if (event.status === '4') return `/create/${event.slug}/tickets`;
                if (event.status === '5') return `/create/${event.slug}/description`;
                if (event.status === '6') return `/create/${event.slug}/advisories`;
                if (event.status === '7') return `/create/${event.slug}/images`;
                if (event.status === '8') return `/create/${event.slug}/review`;
                if (event.status === 'p') return `/events/${event.slug}`;
                if (event.status === 'e') return `/create/${event.slug}/title`;
                if (event.status === 'n') return `/create/${event.slug}/title`;
                return `/create/${event.slug}/title`
            },

            statusCircle(event) {
                if (this.isShowing(event)) return `background: rgb(228 230 147)`;
                if (event.status === '0') return `background: #ffc215`;
                if (event.status === '1') return `background: #ffc215`;
                if (event.status === '2') return `background: #ffc215`;
                if (event.status === '3') return `background: #ffc215`;
                if (event.status === '4') return `background: #ffc215`;
                if (event.status === '5') return `background: #ffc215`;
                if (event.status === '6') return `background: #ffc215`;
                if (event.status === '7') return `background: #ffc215`;
                if (event.status === '8') return `background: #ffc215`;
                if (event.status === 'p') return `background: #1bbb1b`;
                if (event.status === 'e') return `background: #1bbb1b`;
                if (event.status === 'r') return `background: black`;
                if (event.status === 'n') return `background: #bf1515`;
                return `background: #bf1515`;
            },

            isLocked(event) {
                if (event.status === 'r') return true;
            }
        },

        mounted() {
            this.onload();
        },
    }
</script>