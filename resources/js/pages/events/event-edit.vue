<template>
    <div class="event-edit">
        <div 
            v-for="(organizer) in organizerList"
            :key="organizer.id">
            <div class="event-edit__organizer">
                <div class="organizer-card grid">
                    <div 
                        class="organizer-card__image" 
                        :style="organizerStyle(organizer)">
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
                            v-if="organizer.status === 'p'"
                            @click.prevent="showOrganizer(organizer)" 
                            class="preview-organizer">
                            Preview Organizer
                        </button>
                        <button
                            v-else
                            @click.prevent="" 
                            class="preview-organizer">
                            Pending Approval
                        </button>
                        <a :href="`/organizer/${organizer.slug}/edit`">
                            <button class="edit-organizer">Edit</button>
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
                <div class="listing-details-block">
                    <tabs>
                        <tab 
                            title="Current Events" 
                            :active="true" 
                            :id="organizer.id" 
                            class="event-edit__eventlist">
                            <vue-event-edit-listing-item
                                :organizer="organizer" 
                                :events="organizer.in_progress_events.data" />
                            <pagination
                                :list="organizer.in_progress_events"
                                :limit="2"
                                @selectpage="selectPage($event, organizer,'current')" />
                            <modal 
                                v-if="modal == 'deleteOrg'" 
                                @close="modal = null">
                                <div slot="header">
                                    <div class="circle del">
                                        <p>X</p>
                                    </div>
                                </div>
                                <div slot="body"> 
                                    <h3>Are you sure?</h3>
                                    <p>You are deleting your {{ selectedModal.name }} organizer.</p>
                                </div>
                                <div slot="footer">
                                    <button class="btn del" @click="deleteOrg()">Delete</button>
                                </div>
                            </modal>
                            <modal 
                                v-if="modal == 'addreview'" 
                                @close="modal = null">
                                <div slot="header">
                                    <h3>Add Review</h3>
                                </div>
                                <div slot="body">
                                    <div class="review">
                                        <div class="field">
                                            <input 
                                                type="text" 
                                                v-model="reviewername" 
                                                placeholder="Reviewer's name"
                                                :class="{ active: activeItem == 'reviewername','error': $v.reviewername.$error}"
                                                @input="$v.reviewername.$touch()"
                                                @click="activeItem = 'reviewername'"
                                                @blur="activeItem = 'reviewername'">
                                            <div v-if="$v.reviewername.$error" class="validation-error">
                                                <p class="error" v-if="!$v.reviewername.required">Please add a title.</p>
                                            </div>
                                        </div>
                                        <div class="field">
                                            <input 
                                                type="text" 
                                                v-model="url" 
                                                @input="$v.url.$touch()"
                                                placeholder="Link to the review"
                                                :class="{ active: activeItem == 'url','error': $v.url.$error}"
                                                @click="activeItem = 'url'"
                                                @blur="activeItem = 'url'">
                                            <div v-if="$v.url.$error" class="validation-error">
                                                <p class="error" v-if="!$v.url.required">Please add a title.</p>
                                                <p class="error" v-if="!$v.url.url">Must be a url (https://...)</p>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="field">
                                        <textarea 
                                            type="textarea" 
                                            v-model="review"
                                            rows="6"
                                            @input="$v.review.$touch()"
                                            placeholder="Review snippet (no longer than 120 characters)"
                                            :class="{ active: activeItem == 'review','error': $v.review.$error}"
                                            @click="activeItem = 'review'"
                                            @blur="activeItem = 'review'" />
                                        <div v-if="$v.review.$error" class="validation-error">
                                            <p class="error" v-if="!$v.review.required">Must include review snippet</p>
                                            <p class="error" v-if="!$v.review.maxLength">The review snippit is too long.</p>
                                        </div>
                                    </div>
                                </div>
                                <div slot="footer">
                                    <button class="btn del" @click="submitReview()">Submit</button>
                                </div>
                            </modal>
                        </tab>
                        <tab 
                            title="Past Events" 
                            :id="organizer.id + 1" 
                            class="event-edit__eventlist">
                            <vue-event-edit-listing-item
                                tab="current"
                                :events="organizer.past_events.data" />
                            <pagination
                                :list="organizer.past_events"
                                :limit="2"
                                @selectpage="selectPage($event, organizer,'past')" />
                        </tab>
                    </tabs>
                </div>
            </div>
        </div>
        <div>
            <div class="new-organization">
                <div class="new-organization__title">
                    <a href="/organizer/create">
                        <button class="preview-organizer">Add another organization</button>
                    </a>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
    import { required, url, maxLength } from 'vuelidate/lib/validators'
    import Pagination  from '../../components/pagination.vue'

	export default {

        props: ['user'],

        components: { Pagination },

		data() {
			return {
				modal: '',
				selectedModal:'',
                organizerEvents: [],
                review: '',
                reviewername: '',
                url: '',
                activeItem: '',
                webp: false,
                limit: 8,
                organizerList: [],
			}
		},

        computed: {

            canDeleteOrganizer() {
                return organizer => !organizer.in_progress_events.data.length && !organizer.past_events.data.length
            },

            getUrl(event) {
                return this.events.in_progress_events;
            },

            organizerStyle() {
                return organizer => organizer.thumbImagePath ? this.webp ? `background-image:url('/storage/${organizer.thumbImagePath}')` : `background-image:url('/storage/${organizer.thumbImagePath.slice(0, -4)}jpg')` : `background:${organizer.hexColor}`
            },

            reviewData() {
                return {
                    event: this.selectedModal,
                    url: this.url,
                    review: this.review,
                    reviewername: this.reviewername
                }
            }
        },

		methods: {
            async onload() {
                await axios.get(`/create/organizers/fetch?timestamp=${new Date().getTime()}`)
                .then(res => {
                    this.organizerList = res.data;
                })
            },

            deleteOrg() {
                axios.delete(`/organizer/${this.selectedModal.slug}`)
                .then(res => {
                    this.organizerList = this.organizerList.filter(ele => ele.id !== this.selectedModal.id);
                })
            },

            submitReview(index) {
                axios.post(`/reviewevents`, this.reviewData)
                .then(this.closeModal());
            },

			showModal(event, arr) {
				this.selectedModal = event;
                this.modal = arr;
			},

			closeModal() {
                this.isModalVisible = false;
                this.selectedModal = null;
			},

            selectPage(page, organizer, state) {
                axios.post(`/create/${organizer.slug}/events/fetch`, {page:page, state: state})
                .then(res => {
                    this.organizerList.forEach(element => {
                        if (element.id === organizer.id) {
                            if (state === 'past') {element.past_events = res.data;}
                            if (state === 'current') {element.in_progress_events = res.data;}
                        }
                    });
                });
            },

			newEvent(organizer) {
				axios.post(`/events`, organizer)
				.then(res => { window.location.href = `/create/${res.data.slug}/title` })
                .catch(error => { this.serverErrors = error.response.data.errors; });
			},

            showOrganizer(organizer) {
                window.location.href = `/organizer/${organizer.slug}`;
            },

            canUseWebP() {
                let webp = (document.createElement('canvas').toDataURL('image/webp').indexOf('data:image/webp') == 0);
                if (webp) {
                    return this.webp = true
                }
            },
		},

        mounted() {
            this.canUseWebP();
            this.onload();
        },

        validations: {
            reviewername: {
                // required,
            },
            url: {
                // required,
                // url
            },
            review: {
                // required,
                // maxLength: maxLength(120)
            },
        },
    };
</script>