<template>
    <div class="event-edit__eventlist--top">
        <div class="event-index__eventlist edit" ref="list">
            <div class="event-index__eventlist--middle vertical">
                <div 
                    v-if="organizer"
                    class="eventlist__element edit" 
                    :style="`width:${width}`">
                    <div class="card new">
                        <div 
                            @click.prevent="newEvent(organizer)" 
                            class="card-url" />
                        <div class="card-image__top">
                            <div class="card-details">
                                <div class="card-details__content new">
                                    <div>
                                        <svg 
                                            class="b" 
                                            height="32" 
                                            width="32" 
                                            viewBox="0 0 24 24" 
                                            aria-label="Add an Event" 
                                            role="img">
                                            <path d="M24 12c0-6.627-5.372-12-12-12C5.373 0 0 5.373 0 12s5.373 12 12 12c6.628 0 12-5.373 12-12zm-10.767 3.75a1.25 1.25 0 0 1-2.5 0v-3.948l-1.031 1.031a1.25 1.25 0 0 1-1.768-1.768L12 7l4.066 4.065a1.25 1.25 0 0 1-1.768 1.768l-1.065-1.065v3.982z" />
                                        </svg>
                                    </div>
                                    <div>
                                        <p>Add New Event</p>
                                    </div>
                                </div>
                            </div>
                            <div 
                                class="card-image__middle new" 
                                style="padding-top: 64.3%;">
                                <div class="card-image" />
                            </div>
                        </div>
                    </div>
                </div><div 
                    v-for="(event) in events" 
                    :key="event.id" 
                    class="eventlist__element edit" 
                    :style="`width:${width}`">
                    <div 
                        :class="{ 'dis': isDisabled(event) }" 
                        class="card">
                        <div 
                            itemprop="itemListElement" 
                            itemscope="" 
                            itemtype="http://schema.org/ListItem">
                            <meta 
                                :content="event.name" 
                                itemprop="name">
                            <meta 
                                :content="`https://www.everythingimmersive.com/events/${event.slug}`" 
                                itemprop="url">
                            <a 
                                :href="`/create/${event.slug}/title`" 
                                class="card-url" />
                            <div class="card-image__top">
                                <template v-if="inProgress(event)">
                                    <div class="card-details">
                                        <div class="card-details__content">
                                            <p><b>Your Draft</b></p>
                                        </div>
                                    </div>
                                </template>
                                <template v-if="event.status == 'n'">
                                    <div class="card-details">
                                        <div class="card-details__content">
                                            <p><b>Needs Changes</b></p>
                                        </div>
                                    </div>
                                </template>
                                <template v-if="event.status == 'e'">
                                    <div class="card-details">
                                        <div class="card-details__content">
                                            <p><b>Goes Live <br>  {{ cleanDate(event.embargo_date) }} </b></p>
                                        </div>
                                    </div>
                                </template>
                                <template v-if="event.status == 'r'">
                                    <div class="card-details">
                                        <div class="card-details__content">
                                            <p><b>Under Review</b><br>(Locked)</p>
                                        </div>
                                    </div>
                                </template>
                                <template v-if="event.thumbImagePath">
                                    <div class="card-image__middle" style="padding-top: 64.3%;">
                                        <div class="card-image">
                                            <picture>
                                                <source 
                                                    type="image/webp" 
                                                    :srcset="`/storage/${event.thumbImagePath}`"> 
                                                <img 
                                                    style="object-fit:cover" 
                                                    loading="lazy" 
                                                    class="card-image__img" 
                                                    :src="`/storage/${event.thumbImagePath.slice(0, -4)}jpg`" 
                                                    :alt="`${event.name} Immersive Event`">
                                            </picture>
                                        </div>
                                    </div>
                                </template>
                                <template v-else>
                                    <div class="card-image__middle empty" style="padding-top: 64.3%;">
                                        <div class="card-image" />
                                    </div>
                                </template>
                            </div>
                            <div class="card-title">
                                <p>{{ event.name ? event.name : 'New Event' }}</p>
                            </div>
                            <div class="card-price">
                                <p>{{ event.price_range }}</p>
                            </div>
                        </div>
                    </div>
                    <div class="edit-event__buttons">
                        <template v-if="canView(event)">
                            <a :href="`/events/${event.slug}`">
                                <button class="edit-event__sub-button">
                                    View
                                </button>
                            </a>
                        </template>
                        <template v-if="status(event)">
                            <a :href="`/create/${event.slug}/title`">
                                <button class="edit-event__sub-button">
                                    Edit
                                </button>
                            </a>
                        </template>
                        <template v-if="status(event)">
                            <button 
                                @click.prevent="showModal(event, 'delete')" 
                                class="edit-event__sub-button">
                                Delete
                            </button>
                        </template>
                        <template v-if="false">
                            <button 
                                @click.prevent="showModal(event, 'addreview')" 
                                class="edit-event__sub-button">
                                Add Review
                            </button>
                        </template>
                    </div>
                </div>
                <modal v-if="modal == 'delete'" @close="modal = null">
                    <div slot="header">
                        <div class="circle del">
                            <p>X</p>
                        </div>
                    </div>
                    <div slot="body"> 
                        <h3>Are you sure?</h3>
                        <p>You are deleting your {{selectedModal.name}} event.</p>
                    </div>
                    <div slot="footer">
                        <button class="btn del" @click="deleteRow()">Delete</button>
                    </div>
                </modal>
            </div>
        </div>
    </div>
</template>

<script>
    export default {
        props: ['events', 'loadurl', 'organizer'],

        computed: {
            inProgress() {
                return event => this.progress.includes(event.status) ? true : false;
            },
            status() {
                return event => event.status !== 'r' ? true : false;
            },
            canView() {
                return event => event.status == 'p' ? true : false;
            },
            isDisabled() {
                return event => event.status == 'r' ? true : false 
            }

        },

        data() {
            return {
                showEventClass: 'heart',
                progress: ['0','1','2','3','4','5','6','7','8','n','d'],
                modal: '',
                selectedModal:'',
                width: '',
            }
        },

        methods: {
            showModal(event, arr) {
                this.selectedModal = event;
                this.modal = arr;
            },

            deleteRow(index) {
                axios.delete(`/events/${this.selectedModal.slug}`)
                .then(res => {
                    location.reload();
                })
                .catch(errorResponse => { 
                    errorResponse.response.data.errors 
                });
            },

            divWidth() {
                if (this.$refs.list.clientWidth > 1000) {
                    return this.width = '25%'
                }
                if (this.$refs.list.clientWidth > 600) {
                    return this.width = '33.3333%'
                }
                return this.width = '75%';
            },

            newEvent(organizer) {
                axios.post(`/events`, organizer)
                .then(response => { 
                    window.location.href = `/create/${response.data.slug}/title`; 
                })
                .catch(error => { this.serverErrors = error.response.data.errors; });
            },

            cleanDate(data) {
                return this.$dayjs(data).format("MMM DD YYYY");
            }
        },

        mounted() {
            this.divWidth();
        }



    };
</script>