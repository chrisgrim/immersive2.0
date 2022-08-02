<template>
    <div class="mt-4">
        <div class="grid grid-cols-12 border h-24 relative items-center p-4 bg-slate-200 rounded-t-2xl">
            <p class="text-center text-xl font-semibold">Status</p>
            <p class="col-span-5 text-xl font-semibold">Event</p>
            <p class="col-span-2 center text-xl font-semibold">To Do</p>
            <p class="col-span-2 center text-xl text-center font-semibold">Show Dates Remaining</p>
            <p class="text-xl font-semibold">Last Modified</p>
            <p class="text-xl font-semibold text-center">{{ archived ? 'Un-Archive' : 'Archive' }}</p>
        </div>
        <template v-if="events.length">
            <div
                :class="{'is-past': eventPassed(event), 'bg-slate-100' : archived }"
                class="grid grid-cols-12 border border-t-0 h-24 relative items-center p-4"
                v-for="(event, index) in events"
                :key="event.id">
                <!-- status -->
                <div 
                    @mouseover="active = index" 
                    @mouseleave="active = null"
                    class="m-auto relative">
                    <div
                        :class="statusCircle(event)"
                        class="w-4 h-4 rounded-full" />
                    <div 
                        v-if="active === index"
                        class="absolute bg-white top-[-3rem] left-8 border p-4 rounded-2xl">
                        <p>{{ statusDetail(event) }}</p>
                    </div>
                </div>

                <!-- event name -->
                <div class="lg col-span-5">
                    <div
                        :class="{ 'cursor-pointer' : event.status !== 'p' }"
                        class="flex items-center"
                        @click="editEvent(event)">
                        <img 
                            class="h-12 mr-4" 
                            v-if="event.thumbImagePath"
                            :src="`${envImageUrl}${event.thumbImagePath}`">
                        <p class="text-xl">
                            {{ event.name ? event.name : 'New Event' }}
                            <a  
                                v-if="event.status === 'p'"
                                :href="`/create/${event.slug}/title`" 
                                class="underline z-20 hover:text-2xl">
                                (edit live event)
                            </a>
                        </p>
                    </div>
                </div>

                <!-- status of event creation -->
                <div 
                    :class="{ 'cursor-pointer' : event.status !== 'p' }"
                    class="center col-span-2">
                    <p
                        class="underline text-xl" 
                        @click="editEvent(event)">
                        {{ status(event).progress }}
                    </p>
                </div>

                <!-- show dates remaining -->
                <div class="remaining col-span-2 text-center">
                    <button 
                        @click="addDates(event)"
                        v-if="eventPassed(event)"
                        class="border-none text-xl">
                        Add more show dates
                    </button>
                    <button 
                        @click="addDates(event)"
                        v-else
                        class="border-none text-xl">
                        {{ hasDates(event) ? datesRemaining(event).length : 'Ongoing' }}
                    </button>
                </div>

                <!-- updated at -->
                <div>
                    <p class="text-xl">{{ cleanDate(event.updated_at) }}</p>
                </div>

                <!-- archive -->
                <div class="m-auto">
                    <button 
                        class="border-none" 
                        v-if="!event.archived"
                        @click="onArchive(event)">
                        <svg class="w-8 h-8 fill-black hover:scale-125">
                            <use :xlink:href="`/storage/website-files/icons.svg#ri-archive-line`" />
                        </svg>
                    </button>
                    <button 
                        class="border-none"
                        v-if="event.archived"
                        @click="offArchive(event)">
                        <svg class="w-8 h-8 fill-black hover:scale-125">
                            <use :xlink:href="`/storage/website-files/icons.svg#ri-inbox-unarchive-line`" />
                        </svg>
                    </button>
                </div>
            </div>
        </template>
    </div>
</template>

<script>
    export default {

        props: ['events', 'archived'],
        
        data() {
            return {
                active: true,
                envImageUrl: process.env.MIX_IMAGE_URL,
            }
        },

        methods: {
            editEvent(event) {
                if (event.status === 'r' || event.status === 'p') return;
                window.location.href = this.status(event).url
            },
            addDates(event) {
                if (event.status === 'r' || event.status < '3') return;
                window.location.href = `/create/${event.slug}/shows`
            },
            async onArchive(event) {
                await axios.post(`/edit/event/${event.slug}/onArchive`)
                .then(res => {
                    this.$emit('update', res.data)
                })
            },
            async offArchive(event) {
                await axios.post(`/edit/event/${event.slug}/offArchive`)
                .then(res => {
                    this.$emit('update', res.data)
                })
            },
            eventPassed(event) {
                return event.status === 'p' && !event.isShowing
            },
            status(event) {
                if (this.eventPassed(event)) return { progress: 'event has no more dates', url: `/create/${event.slug}/shows` }
                if (event.status === '0') return { progress: 'add event title', url: `/create/${event.slug}/title` }
                if (event.status === '1') return { progress: 'add physical/remote location', url: `/create/${event.slug}/location` }
                if (event.status === '2') return { progress: 'add category', url: `/create/${event.slug}/category` }
                if (event.status === '3') return { progress: 'add show dates', url: `/create/${event.slug}/shows` }
                if (event.status === '4') return { progress: 'add pricing', url: `/create/${event.slug}/tickets` }
                if (event.status === '5') return { progress: 'add show description', url: `/create/${event.slug}/description` }
                if (event.status === '6') return { progress: 'add advisories', url: `/create/${event.slug}/advisories` }
                if (event.status === '7') return { progress: 'add image', url: `/create/${event.slug}/images` }
                if (event.status === '8') return { progress: 'submit event', url: `/create/${event.slug}/review` }
                if (event.status === 'p') return { progress: 'approved', url: `/events/${event.slug}` }
                if (event.status === 'r') return { progress: 'under moderator review', url: null }
                if (event.status === 'n') return { progress: 'revise and resubmit', url: `/create/${event.slug}/title` }
                if (event.status === 'e') return { progress: `event live at ${this.cleanDate(event.embargo_date)}`, url: `/create/${event.slug}/title` }
                return { progress: '-', url: `/create/${event.slug}/title` }
            },
            statusDetail(event) {
                if (this.eventPassed(event)) return 'No more show dates';
                if (event.status <= '8') return 'In progress';
                if (event.status === 'p') return 'Approved';
                if (event.status === 'r') return 'Under review';
                if (event.status === 'n') return 'Fix moderator notes';
                if (event.status === 'e') return `Visible on EI at ${this.cleanDate(event.embargo_date)}`;
                return '-'
            },
            statusCircle(event) {
                if (this.eventPassed(event)) return `bg-slate-200`;
                if (Number(event.status) <= 8 ) return `bg-yellow-300`;
                if (event.status === 'p') return `bg-green-500`;
                if (event.status === 'e') return `bg-green-500`;
                if (event.status === 'r') return `bg-black`;
                if (event.status === 'n') return `bg-red-500`;
                return `bg-white`;
            },
            hasDates(event) {
                if (event.showType !== 'a') return true;
            },
            datesRemaining(event) {
                return event.shows.filter( show => this.$dayjs().subtract(1, 'day').format('YYYY-MM-DD 23:59:00') < show.date )
            },
            isLocked(event) {
                if (event.status === 'r') return true;
            },
            cleanDate(data) {
                return this.$dayjs(data).format("MMM DD, YYYY");
            },
        },
    }
</script>