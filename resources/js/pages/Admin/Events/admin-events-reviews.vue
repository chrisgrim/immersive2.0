<template>
    <div>
        <div class="flex justify-between items-center mb-8">
            <h2>Event Reviews</h2>
            <button 
                @click.prevent="add=!add"
                class="border-none w-20 h-20 flex p-0 rounded-full justify-center items-center hover:bg-slate-300">
                <svg class="w-16 h-16">
                    <use :xlink:href="`/storage/website-files/icons.svg#ri-add-fill`" />
                </svg>
            </button>
        </div>

        <Vue-Add-Review v-if="add" />

        <div class="not-prose relative bg-slate-50 rounded-xl overflow-hidden dark:bg-slate-800/25">
            <div class="relative rounded-xl overflow-auto border">
                <div class="shadow-sm overflow-hidden my-4">
                    <table class="table-auto border-collapse w-full">
                        <thead>
                            <tr>
                                <th 
                                    :key="col.id"
                                    v-for="col in cols"
                                    class="border-b dark:border-slate-600 font-medium p-4 pl-8 pt-0 pb-3 text-slate-400 dark:text-slate-200 text-left">
                                    <p 
                                        class="text-xl" 
                                        :class="col.class">{{ col.field }}</p>
                                </th>
                            </tr>
                        </thead>
                        <tbody class="bg-white dark:bg-slate-800">
                            <template v-if="reviews.data && reviews.data.length">
                                <tr
                                    v-for="(review, index) in reviews.data"
                                    :key="review.id">
                                    <td class="border-b border-slate-100 dark:border-slate-700 p-4 pl-8 text-slate-500 dark:text-slate-400">
                                        <p 
                                            v-if="review.event" 
                                            class="text-xl">{{ review.event.name }}
                                        </p>
                                    </td>
                                    <td class="border-b border-slate-100 dark:border-slate-700 p-4 pl-8 text-slate-500 dark:text-slate-400">
                                        <input 
                                            class="text-xl px-4 py-2 rounded-xl hover:bg-slate-500 hover:text-white" 
                                            type="text" 
                                            v-model="review.reviewer_name" 
                                            placeholder="Reviewer's name"
                                            @click="active = `name${index}`"
                                            @blur="onUpdate(review)">
                                    </td>
                                    <td class="border-b border-slate-100 dark:border-slate-700 p-4 pl-8 text-slate-500 dark:text-slate-400">
                                        <input 
                                            class="text-xl px-4 py-2 rounded-xl hover:bg-slate-500 hover:text-white" 
                                            type="text" 
                                            v-model="review.url" 
                                            placeholder="Link to the review"
                                            @click="active = `url${index}`"
                                            @blur="onUpdate(review)">
                                    </td>
                                    <td class="border-b border-slate-100 dark:border-slate-700 p-4 pl-8 text-slate-500 dark:text-slate-400">
                                        <textarea 
                                            type="textarea" 
                                            v-model="review.review"
                                            rows="3"
                                            placeholder="Review snippet"
                                            @click="active = `review${index}`"
                                            @blur="onUpdate(review)" />
                                    </td>
                                    <td class="border-b border-slate-100 dark:border-slate-700 p-4 pl-8 text-slate-500 dark:text-slate-400">
                                        <select 
                                            class="text-xl px-4 py-2 rounded-xl hover:bg-slate-500 hover:text-white" 
                                            v-model="review.rank"
                                            placeholder="User Type"
                                            @change="onUpdate(review)">
                                            <option value="1">1</option>
                                            <option value="2">2</option>
                                            <option value="3">3</option>
                                            <option value="4">4</option>
                                            <option value="5">5</option>
                                        </select>
                                    </td>
                                    <td class="border-b border-slate-100 dark:border-slate-700 p-4 pl-8 text-slate-500 dark:text-slate-400">
                                        <button 
                                            @click.prevent="showModal(review, 'delete')"
                                            class="rounded-full p-2 hover:bg-black hover:border-black">
                                            <svg class="w-8 h-8 hover:fill-white">
                                                <use :xlink:href="`/storage/website-files/icons.svg#ri-close-line`" />
                                            </svg>
                                        </button>
                                    </td>
                                </tr>
                            </template>
                        </tbody>
                    </table>
                </div>
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
    import Pagination  from '../../../components/pagination.vue'
    import VueAddReview from './Components/event-review-component'
    import VueDeleteModal from '../../../components/modals/Vue-Modal-Delete'

    export default {

        components: { Pagination, VueAddReview, VueDeleteModal },

        data() {
            return {
                add: false,
                modal: '',
                active: '',
                reviews: [],
                selectedModal: '',
                cols: [
                    { id:0, field: 'Name', class:''},
                    { id:1, field: 'Reviewer', class:''},
                    { id:2, field: 'Review Url', class:'' },
                    { id:3, field: 'Review', class:'' },
                    { id:4, field: 'Rank', class:'' },
                ],
            }
        },

        methods: {
            async onDelete() {
                await axios.delete(`/reviewevents/${this.selectedModal.id}`)
                location.reload()
            },
            async onLoad(page) {
                await axios.get(`/reviewevents?page=${page}`)
                .then( res => { this.reviews = res.data })
            },
            async onUpdate(review) {
                await axios.patch(`/reviewevents/${review.id}`, review)
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