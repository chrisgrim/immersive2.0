<template>
    <div class="shelf">
        <template v-if="editName">
            <div class="field h3">
                <input 
                    type="text" 
                    v-model="shelf.name"
                    :class="{ 'error': $v.shelf.name.$error }"
                    placeholder="Shelf Name">
                <div v-if="$v.shelf.name.$error" class="validation-error">
                    <p class="error" v-if="!$v.shelf.name.required">Please add a name.</p>
                    <p class="error" v-if="!$v.shelf.name.maxLength">The name is too long.</p>
                </div>
            </div>
            <div class="editor__footer borderless">
                <button class="outline" @click="resetShelf">Cancel</button>
                <button @click="patchShelf">Save</button>
            </div>
        </template>
        <template v-else>
            <div 
                @mouseover="hover = true"
                @mouseleave="hover = false"
                @click="editName=true"
                class="name">
                <h3>{{ shelf.name }}</h3>
                <template v-if="!editName && hover">
                    <button class="round noBox">
                        <svg>
                            <use :xlink:href="`/storage/website-files/icons.svg#ri-pencil-line`" />
                        </svg>
                    </button>
                </template>
            </div>
        </template>
        <div 
            v-if="listings && listings.length"
            class="listings">
            <CollectionAlbum
                @update="updateShelf"
                :edit="true"
                :title="true"
                :draggable="true"
                :shelf="shelf"
                :community="community"
                v-model="listings" />
            <div 
                v-if="shelf.listings_with_cards.length > 4 && paginate.next_page_url"
                class="loadmore">
                <button @click="fetchListings">
                    Load More
                </button>
            </div>
        </div>
    </div>
</template>

<script>
    import CollectionAlbum from './vue-album-collection.vue'
    import formValidationMixin from '../../../../mixins/form-validation-mixin'
    import { required, maxLength } from 'vuelidate/lib/validators';
    export default {
        
        props: [ 'loadshelf', 'owner', 'community' ],

        mixins: [formValidationMixin],

        components: { CollectionAlbum },

        computed: {

        },

        data() {
            return {
                shelf: this.loadshelf,
                shelfBeforeEdit: { ...this.loadshelf },
                listings:this.loadshelf.listings_with_cards.slice(0,4),
                editName: false,
                hover: false,
                serverErrors: null,
                paginate: this.generatePaginateObject(),
            }
        },

        methods: {
            async patchShelf() {
                if ( this.checkVuelidate()) { return }
                await axios.put(`/shelves/${this.shelf.id}`, this.shelf)
                .then( res => {
                    this.shelf = res.data;
                    this.clear();
                    this.$emit('updated')
                })
                .catch(err => {
                    this.onErrors(err);
                });
            },
            async fetchListings() {
                await axios.get(this.paginate.next_page_url)
                .then( res => {
                    this.paginate = res.data
                    this.listings = this.listings.concat(res.data.data);
                })
            },
            resetShelf() {
                this.shelf = { ...this.shelfBeforeEdit }
                this.clear();
            },
            updateShelf(value) {
                this.shelf = value;
                this.listings = value.listings_with_cards
            },
            clear() {
                this.editName = false;
                this.hover = false;
            },
            generatePaginateObject() {
                return {
                    to: 2,
                    next_page_url: `/shelves/${this.loadshelf.id}/paginate?page=2`
                }
            }
        },

        validations: {
            shelf: {
                name: {
                    required,
                    maxLength: maxLength(60),
                },
            },
        },
    }
</script>