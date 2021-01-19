<template>
    <div class="c-tags">
        <div class="">
            <div class="title">
                <h1>Tags</h1>
                <div class="add">
                    <button @click.prevent="modal = 'new'">
                        <IconSvg type="add" />
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
            <div class="c-tags__list--grid-top">
                <p>tag</p>
                <p>rank (1 is most important)</p>
                <p>admin</p>
            </div>
            <div 
                class="list c-tags__list--grid" 
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
                    <v-select 
                        v-model="genre.rank"
                        label="name"
                        placeholder="Leave blank for default Rank of 5 (1 being most important)"
                        @search:focus="active = 'rank'"
                        @search:blur="active = null"
                        @input="onUpdate(genre)"
                        :clearable="false"
                        :class="{ active: active == 'rank'}"
                        :options="[1,2,3,4,5]" />
                </div>
                <div class="field">
                    <v-select 
                        v-model="genre.admin"
                        placeholder="admin"
                        @search:focus="active = `${genre.id}rank`"
                        @search:blur="active = null"
                        @input="onUpdate(genre)"
                        :clearable="false"
                        :class="{ active: active == `${genre.id}rank`}"
                        :options="[1,2,3,4,5]" />
                </div>
                <button 
                    @click.prevent="showModal(genre, 'delete')" 
                    class="delete-circle">
                    <IconSvg type="delete" />
                </button>
            </div>
            <pagination 
                :limit="1"
                :list="genres"
                @selectpage="onLoad" />
            <VueDeleteModal 
                v-if="modal == 'delete'"
                :item="selectedModal"
                :strict="true"
                body="Deleting this Tag will delete it everywhere"
                @close="modal = null"
                @ondelete="onDelete" />
            <div class="pin noimg">
                <modal 
                    v-if="modal == 'new'"
                    @close="modal = null">
                    <div slot="header">
                        <div />
                    </div>
                    <div slot="body" class="body">
                        <div class="text">
                            <div class="name">
                                <input 
                                    type="text" 
                                    v-model="name" 
                                    placeholder="Genre"
                                    :class="{ active: active}"
                                    @click="active = true"
                                    @blur="active = false"
                                    @input="$v.name.$touch">
                                <div v-if="$v.name.$error" class="validation-error">
                                    <p class="error" v-if="!$v.name.required">Please Add Genre </p>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div slot="footer">
                        <button 
                            @click.prevent="onSubmit()" 
                            class="btn sub">
                            Submit
                        </button>
                    </div>
                </modal>
            </div>
        </div>
    </div>
</template>

<script>
    
    import { required } from 'vuelidate/lib/validators'
    import IconSvg from '../../components/Svg-icon'
    import Pagination  from '../../components/pagination.vue'
    import VueDeleteModal from '../../components/Vue-Delete-Modal'
    import formValidationMixin from '../../mixins/form-validation-mixin'

    export default {

        components: { IconSvg, Pagination, VueDeleteModal },

        mixins: [ formValidationMixin ],

        data() {
            return {
                genre: '',
                genres: [],
                active: false,
                name: '',
                modal: null,
                selectedModal: '',
            }
        },

        methods: {

            async onSubmit() {
                if (this.checkVuelidate()) { return false }
                await axios.post('/genres', {name: this.name})
                location.reload();
            },

            onUpdate(genre) {
                axios.patch(`/genres/${genre.id}`, genre)
                .then( res => { this.genres = res.data.data })
            },

            async onDelete() {
                await axios.delete(`/genres/${this.selectedModal.id}`)
                location.reload();
            },

            onLoad(page) {
                axios.get(`/genres?page=${page}`)
                .then(res => { 
                    this.genres = res.data;
                })
            },

            onSearch(value) {
                axios.get('/api/admin/genres/search', { params: { keywords: value } })
                .then(res => {
                    this.genres = res.data;
                });
            },
            
            showModal(genre, arr) {
                this.selectedModal = genre;
                this.modal = arr;
            },
        },

        mounted() {
            this.onLoad()
        },

        validations: {
            name: {
                required,
            },
        },
    }

</script>