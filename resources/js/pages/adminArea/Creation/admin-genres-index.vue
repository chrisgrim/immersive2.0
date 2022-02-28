<template>
    <div>
        <div class="flex justify-between mb-8 items-center">
            <h2>Tags</h2>
            <button 
                @click="onAdd=!onAdd"
                class="border-none w-20 h-20 flex p-0 rounded-full justify-center items-center hover:bg-slate-300">
                <svg class="w-16 h-16">
                    <use :xlink:href="`/storage/website-files/icons.svg#ri-add-fill`" />
                </svg>
            </button>
        </div>

        <div>
            <input 
                type="text"
                v-model="genre"
                class="border mb-8 rounded-2xl p-4" 
                placeholder="search by tag name" 
                @keyup="debounce(genre)">
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
                                <template v-if="genres.data && genres.data.length">
                                    <tr
                                        v-for="(genre) in genres.data"
                                        :key="genre.id">
                                        <td class="border-b border-slate-100 dark:border-slate-700 p-4 pl-8 text-slate-500 dark:text-slate-400">
                                            <input 
                                                type="text" 
                                                v-model="genre.name"
                                                class="w-full p-2" 
                                                placeholder="Genre"
                                                @blur="onUpdate(genre)">
                                        </td>
                                        <td class="border-b border-slate-100 dark:border-slate-700 p-4 pl-8 text-slate-500 dark:text-slate-400">
                                            <select 
                                                class="text-xl px-4 py-2 rounded-xl hover:bg-slate-500 hover:text-white" 
                                                v-model="genre.rank"
                                                @change="onUpdate(genre)">
                                                <option 
                                                    v-for="pick in ['0','1', '2', '3', '4', '5']"
                                                    :key="pick">
                                                    {{ pick }}
                                                </option>
                                            </select>
                                        </td>
                                        <td class="border-b border-slate-100 dark:border-slate-700 p-4 pl-8 text-slate-500 dark:text-slate-400">
                                            <select 
                                                class="text-xl px-4 py-2 rounded-xl hover:bg-slate-500 hover:text-white" 
                                                v-model="genre.admin"
                                                @change="onUpdate(genre)">
                                                <option value="0">Guest</option>
                                                <option value="1">Admin</option>
                                            </select>
                                        </td>
                                        <td class="border-b border-slate-100 dark:border-slate-700 p-4 pl-8 text-slate-500 dark:text-slate-400">
                                            <button 
                                                @click.prevent="selectedModal=genre" 
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
                    :list="genres"
                    @selectpage="onLoad" />
                <AddGenre 
                    @update="update"
                    @close="onAdd=false"
                    v-if="onAdd" />
                <VueDeleteModal 
                    v-if="selectedModal"
                    :item="selectedModal"
                    :strict="true"
                    body="You are deleting the category. Please be sure you know what you are doing."
                    @close="selectedModal=null"
                    @ondelete="onDelete" />
            </div>
        </div>
    </div>
</template>

<script>
    
    import Pagination  from '../../../components/pagination.vue'
    import VueDeleteModal from '../../../components/modals/Vue-Modal-Delete'
    import AddGenre from './Components/genre-component'


    export default {

        components: { Pagination, VueDeleteModal, AddGenre },

        data() {
            return {
                genre: null,
                genres: [],
                selectedModal: '',
                onAdd: false,
                cols: [
                    { id:0, field: 'tag', class:''},
                    { id:1, field: 'rank(1 is most important, 0 is not ranked)', class:''},
                    { id:2, field: 'admin', class:'' },
                ],
            }
        },

        methods: {
            async onUpdate(genre) {
                await axios.patch(`/genres/${genre.id}`, genre)
                .then( res => { 
                    this.genres = res.data 
                })
            },
            async onDelete() {
                await axios.delete(`/genres/${this.selectedModal.id}`)
                location.reload();
            },
            async onLoad(page) {
                await axios.get(`/genres?page=${page}`)
                .then(res => { 
                    this.genres = res.data;
                })
            },
            async onSearch(value) {
                await axios.get('/api/admin/genres/search', { params: { keywords: value } })
                .then(res => {
                    this.genres = res.data;
                });
            },
            update(value) {
                this.genres = value
                this.onAdd = false
            },
            debounce(query) {
                if (this.timeout) 
                    clearTimeout(this.timeout); 
                this.timeout = setTimeout(() => {
                    this.onSearch(query);
                }, 400); // delay
            },
        },

        mounted() {
            this.onLoad()
        },
    }

</script>