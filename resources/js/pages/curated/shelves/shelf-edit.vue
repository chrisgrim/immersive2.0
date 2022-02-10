<template>
    <div class="rounded-2xl mb-8 p-4 border">
        <template v-if="editName && shelf.status !== 'a'">
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
                <button 
                    class="rounded-full py-2 px-4 bg-white hover:bg-black hover:text-white hover:border-black"
                    @click="resetShelf">Cancel</button>
                <button 
                    class="rounded-full py-2 px-4 bg-white hover:bg-black hover:text-white hover:border-black"
                    @click="patchShelf">Save</button>
            </div>
        </template>
        <template v-else>
            <div 
                @mouseover="hover = true"
                @mouseleave="hover = false"
                @click="editName=true"
                class="inline-flex">
                <template v-if="shelf.name">
                    <h3>{{ shelf.name }}</h3>
                </template>
                <template v-else>
                    <h3>Edit Name</h3>
                </template>
                <template v-if="!editName && hover && shelf.status !== 'a'">
                    <button class="border-none underline p-0 block w-8 h-8 rounded-full justify-center items-center">
                        <svg class="h-8 w-8">
                            <use :xlink:href="`/storage/website-files/icons.svg#ri-pencil-line`" />
                        </svg>
                    </button>
                </template>
            </div>
        </template>
        <div 
            v-if="posts && posts.length"
            class="posts">
            <CollectionAlbum
                @update="updateShelf"
                :edit="true"
                :title="true"
                :draggable="true"
                v-model="shelf"
                :archived="archived"
                :community="community"
                :loadposts="posts" />
        </div>
    </div>
</template>

<script>
    import CollectionAlbum from './Components/vue-album.vue'
    import formValidationMixin from '../../../mixins/form-validation-mixin'
    import { required, maxLength } from 'vuelidate/lib/validators';
    export default {
        
        props: [ 'loadshelf', 'owner', 'community', 'archived' ],

        mixins: [formValidationMixin],

        components: { CollectionAlbum },

        computed: {

        },

        data() {
            return {
                shelf: this.loadshelf,
                shelfBeforeEdit: { ...this.loadshelf },
                posts:this.loadshelf.posts.data,
                editName: false,
                hover: false,
                serverErrors: null,
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
            resetShelf() {
                this.shelf = { ...this.shelfBeforeEdit }
                this.clear();
            },
            updateShelf(value) {
                this.shelf = value;
                this.posts = value.posts
            },
            clear() {
                this.editName = false;
                this.hover = false;
            },
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