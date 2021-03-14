<template>
    <div class="reviewevent">
        <div class="">
            <div class="title">
                <h1>Add Reviews to events</h1>
                <div class="add">
                    <button @click.prevent="add = true">
                        <IconSvg type="add" />
                    </button>
                </div>
            </div>
        </div>

        <Vue-Add-Review v-if="add" />

        <div class="data-grid">
            <div class="data-grid__row header">
                <p>Name</p>
                <p>Reviewer</p>
                <p>Review URL</p>
                <p>Review</p>
                <p>Rank</p>
            </div>
            <div 
                class="data-grid__row" 
                :key="review.id"
                v-for="(review, index) in reviews.data">
                <div class="field">
                    <p v-if="review.event">{{ review.event.name }}</p>
                </div>
                <div class="field">
                    <input 
                        type="text" 
                        v-model="review.reviewer_name" 
                        placeholder="Reviewer's name"
                        :class="{ active: active == `name${index}`}"
                        @click="active = `name${index}`"
                        @blur="onUpdate(review)">
                </div>
                <div class="field">
                    <input 
                        type="text" 
                        v-model="review.url" 
                        placeholder="Link to the review"
                        :class="{ active: active == `url${index}`}"
                        @click="active = `url${index}`"
                        @blur="onUpdate(review)">
                </div>
                <div class="field">
                    <textarea 
                        type="textarea" 
                        v-model="review.review"
                        rows="3"
                        placeholder="Review snippet"
                        :class="{ active: active == `review${index}`}"
                        @click="active = `review${index}`"
                        @blur="onUpdate(review)" />
                </div>
                <select 
                    v-model="review.rank"
                    placeholder="User Type"
                    @change="onUpdate(review)">
                    <option value="1">1</option>
                    <option value="2">2</option>
                    <option value="3">3</option>
                    <option value="4">4</option>
                    <option value="5">5</option>
                </select>
                <button 
                    @click.prevent="showModal(review, 'delete')" 
                    class="delete">
                    <IconSvg type="delete" />
                </button>
            </div>
            <pagination 
                :limit="1"
                :list="reviews"
                @selectpage="onLoad" />
        </div>
        <VueDeleteModal 
            v-if="modal == 'delete'"
            :item="selectedModal"
            :strict="true"
            body="You are deleting the review"
            @close="modal = null"
            @ondelete="onDelete" />
    </div>
</template>

<script>
    import IconSvg from '../../components/Svg-icon'
    import Pagination  from '../../components/pagination.vue'
    import VueAddReview from './components/vue-add-event-review'
    import VueDeleteModal from '../../components/Vue-Delete-Modal'

    export default {

        components: { IconSvg, Pagination, VueAddReview, VueDeleteModal },

        data() {
            return {
                add: false,
                modal: '',
                active: '',
                reviews: [],
                selectedModal: '',
            }
        },

        methods: {

            async onDelete() {
                await axios.delete(`/reviewevents/${this.selectedModal.id}`)
                location.reload()
            },

            onLoad(page) {
                axios.get(`/reviewevents?page=${page}`)
                .then( res => { this.reviews = res.data })
            },

            onUpdate(review) {
                axios.patch(`/reviewevents/${review.id}`, review)
                .then( res => { this.reviews = res.data; this.active = null })
            },

            showModal(item, arr) {
                this.selectedModal = item;
                this.modal = arr;
            },

        },

        created() {
            this.onLoad()
        },

    }

</script>