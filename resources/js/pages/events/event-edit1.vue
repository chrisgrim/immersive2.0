<template>
    <div class="event-edit">
        <template v-if="organizers && organizers.length">
            <div 
                v-for="(organizer) in organizers"
                :key="organizer.id">
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
                                @click.prevent="newEvent(organizer)" 
                                class="solid"> Add Event </button>
                            <a 
                                v-if="organizer.status === 'p'" 
                                :href="`/organizer/${organizer.slug}`">
                                <button class="outline">
                                    Preview Organizer
                                </button>
                            </a>
                            <button
                                v-else
                                @click.prevent="" 
                                class="outline">
                                Pending Approval
                            </button>
                            <a :href="`/organizer/${organizer.slug}/edit`">
                                <button class="outline">Edit</button>
                            </a>
                            <template v-if="canDeleteOrganizer(organizer)">
                                <button                   
                                    @click.prevent="showModal(organizer, 'deleteOrg')" 
                                    class="edit-organizer">
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
                            class="data-grid__row"
                            v-for="(event) in organizer.events"
                            :key="event.id">
                            <div class="center">
                                <div
                                    :style="getStatusCircle(event)"
                                    class="status-circle" />
                            </div>
                            <div>
                                <a 
                                    target="_blank"
                                    :href="`/create/${event.slug}/title`">
                                    <img 
                                        v-if="event.thumbImagePath"
                                        :src="`/storage/${event.thumbImagePath}`">
                                    <p v-if="event.name">{{ event.name }}</p>
                                    <p v-else>New Event</p>
                                </a>
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
                            <div 
                                v-if="event.shows"
                                class="remaining center">
                                {{ datesRemaining(event).length }}
                            </div>
                            <div>
                                <p>{{ cleanDate(event.updated_at) }}</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </template>
    </div>
</template>

<script>
    import IconSvg from '../../components/Svg-icon'
    export default {

        props: ['user'],

        components: { IconSvg },

        computed: {
            datesRemaining() {
                return event => event.shows.filter( show => this.$dayjs().subtract(1, 'day').format('YYYY-MM-DD 23:59:00') < show.date )
            },
            canDeleteOrganizer() {
                return organizer => !organizer.events.length
            },
            getStatus() {
                return event => this.status(event)
            },
            getUrl() {
                return event => this.url(event)
            },
            getStatusCircle() {
                return event => this.statusCircle(event)
            }

        },

        data() {
            return {
                organizers: [],
            }
        },

        methods: {
            async onload() {
                await axios.get(`/create/organizers/fetch?timestamp=${new Date().getTime()}`)
                .then(res => {
                    this.organizers = res.data;
                })
            },

            newEvent(organizer) {
                axios.post(`/events`, organizer)
                .then(response => { 
                    window.location.href = `/create/${response.data.slug}/title`; 
                })
                .catch(error => { this.serverErrors = error.response.data.errors; });
            },

            getDates() {
                if(this.event.shows) {
                    this.event.shows.forEach(event=> {
                        if (this.$dayjs().subtract(1, 'day').format('YYYY-MM-DD 23:59:00') < event.date) {
                            this.remaining.push(event.date);
                        } else {
                            this.config.disable.push(event.date);
                        }
                        this.dates.push(event.date);
                    });
                }
            },

            cleanDate(data) {
                return this.$dayjs(data).format("MMM DD, YYYY");
            },

            status(event) {
                if (event.status === '1') return 'add physical/remote location';
                if (event.status === '2') return 'add category';
                if (event.status === '3') return 'add show dates';
                if (event.status === '4') return 'add pricing';
                if (event.status === '5') return 'add show description';
                if (event.status === '6') return 'add advisories';
                if (event.status === '7') return 'add image';
                if (event.status === '8') return 'review event';
                if (event.status === 'p') return 'event is live';
                if (event.status === 'r') return 'under moderator review';
                if (event.status === 'e') return `event live at ${this.cleanDate(event.embargo_date)}`;
                return 'add event title'
            },

            url(event) {
                if (event.status === '1') return `/create/${event.slug}/location`;
                if (event.status === '2') return `/create/${event.slug}/category`;
                if (event.status === '3') return `/create/${event.slug}/shows`;
                if (event.status === '4') return `/create/${event.slug}/tickets`;
                if (event.status === '5') return `/create/${event.slug}/description`;
                if (event.status === '6') return `/create/${event.slug}/advisories`;
                if (event.status === '7') return `/create/${event.slug}/images`;
                if (event.status === '8') return `/create/${event.slug}/review`;
                if (event.status === 'p') return `/create/${event.slug}/title`;
                if (event.status === 'e') return `/create/${event.slug}/title`;
                return `/create/${event.slug}/title`
            },

            statusCircle(event) {
                if (event.status === '1') return `background: #dec237`;
                if (event.status === '2') return `background: #dec237`;
                if (event.status === '3') return `background: #dec237`;
                if (event.status === '4') return `background: #dec237`;
                if (event.status === '5') return `background: #dec237`;
                if (event.status === '6') return `background: #dec237`;
                if (event.status === '7') return `background: #dec237`;
                if (event.status === '8') return `background: #dec237`;
                if (event.status === 'p') return `background: #1bbb1b`;
                if (event.status === 'e') return `background: #1bbb1b`;
                return `background: #bf1515`;
            }
        },

        mounted() {
            this.onload();
        },
    }
</script>