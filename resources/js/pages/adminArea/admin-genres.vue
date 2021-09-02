<template>
    <div class="admin-tags">
        <div class="">
            <div class="title">
                <h1>Tags</h1>
                <div class="add-button">
                    <button 
                        @click="onAdd=!onAdd"
                        class="add__icon" 
                        :class="{active: onAdd}">
                        <svg>
                            <use :xlink:href="`/storage/website-files/icons.svg#ri-add-fill`" />
                        </svg>
                    </button>
                </div>
            </div>
        </div>

        <div class="c-tags__list">
            <div class="field c-tags__list--filter">
                <input 
                    type="text"
                    v-model="genre"
                    placeholder="search by tag name" 
                    @keyup="onSearch(genre)">
            </div>
            <div class="data-grid">
                <div class="data-grid__row header">
                    <p>tag</p>
                    <p>rank (1 is most important)</p>
                    <p>admin</p>
                </div>
                <div 
                    class="data-grid__row"
                    :key="genre.id"
                    v-for="genre in genres.data">
                    <div class="field">
                        <input 
                            type="text" 
                            v-model="genre.name" 
                            placeholder="Genre"
                            @blur="onUpdate(genre)">
                    </div>
                    <div class="field">
                        <select 
                            v-model="genre.rank"
                            @change="onUpdate(genre)"
                            placeholder="Leave blank for default Rank of 5 (1 being most important)">
                            <option 
                                v-for="pick in ['1', '2', '3', '4', '5']"
                                :key="pick">
                                {{ pick }}
                            </option>
                        </select>
                    </div>
                    <div class="field">
                        <select 
                            v-model="genre.admin"
                            @change="onUpdate(genre)">
                            <option value="0">Guest</option>
                            <option value="1">Admin</option>
                        </select>
                    </div>
                    <button 
                        @click.prevent="selectedModal=genre" 
                        class="delete">
                        <svg>
                            <use :xlink:href="`/storage/website-files/icons.svg#ri-close-line`" />
                        </svg>
                    </button>
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
    
    import Pagination  from '../../components/pagination.vue'
    import VueDeleteModal from '../../components/modals/Vue-Modal-Delete'
    import AddGenre from './components/add-genre'


    export default {

        components: { Pagination, VueDeleteModal, AddGenre },

        data() {
            return {
                genre: null,
                genres: [],
                selectedModal: '',
                onAdd: false,
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
            }
        },

        mounted() {
            this.onLoad()
        },
    }

</script>