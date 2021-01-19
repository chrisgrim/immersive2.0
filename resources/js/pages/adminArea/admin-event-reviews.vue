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

        <div class="listing">
            <div>
                <h2>Latest Reviews</h2>
            </div>
            <div 
                class="list" 
                :key="review.id"
                v-for="review in reviews.data">
                <div class="field">
                    {{ review.event.name }}
                </div>
                <div class="field">
                    <input 
                        type="text" 
                        v-model="review.reviewer_name" 
                        placeholder="Reviewer's name"
                        :class="{ active: active == 'reviewername'}"
                        @click="active = 'reviewername'"
                        @blur="onUpdate(review)">
                </div>
                <div class="field">
                    <input 
                        type="text" 
                        v-model="review.url" 
                        placeholder="Link to the review"
                        :class="{ active: active == 'url'}"
                        @click="active = 'url'"
                        @blur="onUpdate(review)">
                </div>
                <div class="field">
                    <textarea 
                        type="textarea" 
                        v-model="review.review"
                        rows="6"
                        placeholder="Review snippet"
                        :class="{ active: active == 'review'}"
                        @click="active = 'review'"
                        @blur="onUpdate(review)" />
                </div>
                <div class="field">
                    <v-select 
                        v-model="review.rank"
                        :options="rankOptions"
                        placeholder="Leave blank for default Rank of 5 (1 being most important)"
                        @search:blur="active = null"
                        @search:focus="active = 'rank'"
                        @input="onUpdate(review)"
                        :class="{ active: active == 'rank'}" />
                </div>
                <button 
                    @click.prevent="showModal(review, 'delete')" 
                    class="delete-circle">
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
                rankOptions: ['1', '2', '3', '4', '5'],
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