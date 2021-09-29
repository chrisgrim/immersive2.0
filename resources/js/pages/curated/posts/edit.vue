<template>
    <div class="post-edit lay-a">
        <div class="breadcrumbs">
            <p v-if="owner">
                <a :href="`/communities/${community.slug}/edit`">{{community.name}}</a> > {{post.name}}
            </p>
            <p v-else>
                <a :href="`/communities/${community.slug}`">{{community.name}}</a> > {{post.name}}
            </p>
        </div>
        <div class="wrapper">
            <div class="content">
                <div class="edit-post__body grid">
                    <template v-if="nameEdit">
                        <div class="field h3">
                            <input 
                                type="text" 
                                v-model="post.name"
                                @input="clearErrors"
                                :class="{ 'error': $v.post.name.$error }"
                                placeholder="Collection Name">
                            <div v-if="$v.post.name.$error" class="validation-error">
                                <p class="error" v-if="!$v.post.name.required">Please add a name.</p>
                                <p class="error" v-if="!$v.post.name.maxLength">The name is too long.</p>
                            </div>
                        </div>
                        <div class="editor__footer borderless">
                            <button class="outline" @click="resetPost">Cancel</button>
                            <button @click="patchPost">Save</button>
                        </div>
                    </template>
                    <template v-else>
                        <div 
                            @click="nameEdit=true"
                            class="post-name">
                            <h2>{{ post.name }}</h2>
                        </div>
                    </template>
                    <template v-if="tagEdit">
                        <div class="field">
                            <input 
                                type="text"
                                @blur="patchPost"
                                v-model="post.blurb"
                                :class="{ 'error': $v.post.blurb.$error }"
                                placeholder="Collection tag line">
                            <div v-if="$v.post.blurb.$error" class="validation-error">
                                <p class="error" v-if="!$v.post.blurb.maxLength">The name is too long.</p>
                            </div>
                        </div>
                        <div class="editor__footer borderless">
                            <button class="outline" @click="resetPost">Cancel</button>
                            <button @click="patchPost">Save</button>
                        </div>
                    </template>
                    <template v-else>
                        <div 
                            @click="tagEdit=true"
                            class="post-blurb blurb">
                            <p>{{ post.blurb }}</p>
                        </div>
                    </template>
                    <draggable
                        v-model="post.cards" 
                        @start="isDragging=true" 
                        @end="debounce">
                        <div 
                            v-for="card in post.cards"
                            :key="card.id"
                            class="post-card">
                            <div class="edit-block">
                                <EditCard 
                                    @update="updatePost"
                                    :parent-card="card" />
                            </div>
                        </div>
                    </draggable>
                    <EventBlock 
                        v-if="blockType==='e'"
                        @cancel="clear"
                        @update="patchPost"
                        :post="post" />
                    <ImageBlock 
                        v-if="blockType==='i'"
                        @update="patchPost"
                        :post="post" />
                    <TextBlock 
                        v-if="blockType==='t'"
                        @cancel="clear"
                        @update="patchPost"
                        :post="post" />
                    <div class="add-new-post-card">
                        <button 
                            @click="showAddButtonOptions"
                            class="main">
                            add new card
                            <svg class="remix">
                                <use v-if="!buttonOptions" :xlink:href="`/storage/website-files/icons.svg#ri-add-circle-line`" />
                                <use v-else :xlink:href="`/storage/website-files/icons.svg#ri-close-circle-line`" />
                            </svg>
                        </button>
                        <template v-if="buttonOptions">
                            <button class="sub" @click="selectButton('i')">
                                Image Block
                            </button>
                            <button class="sub" @click="selectButton('t')">
                                Text Block
                            </button>
                            <button class="sub" @click="selectButton('e')">
                                Event Block
                            </button>
                        </template>
                    </div>
                </div>
            </div>
            <div class="sidebar">
                <div class="sticky">
                    <template>
                        <div class="menu">
                            <a :href="`/communities/${community.slug}/${value.slug}`">
                                <button class="outline">
                                    view
                                </button>
                            </a>
                        </div>
                    </template>
                    <div class="menu">
                        <button 
                            @click="showStatus=!showStatus"
                            class="component-title">
                            <p>Status</p>
                            <svg v-if="showStatus"><use :xlink:href="`/storage/website-files/icons.svg#ri-arrow-up-s-line`" /></svg>
                            <svg v-else><use :xlink:href="`/storage/website-files/icons.svg#ri-arrow-down-s-line`" /></svg>
                        </button>
                        <template v-if="showStatus">
                            <div class="component-body">
                                <div class="flex btw">
                                    <p>Visibility:</p>
                                    <button @click="updateStatus">
                                        {{ postStatus }}
                                    </button>
                                </div>
                            </div>
                        </template>
                    </div>
                    <div class="menu">
                        <button 
                            @click="showShelf=!showShelf"
                            class="component-title">
                            <p>Shelf</p>
                            <svg v-if="showShelf"><use :xlink:href="`/storage/website-files/icons.svg#ri-arrow-up-s-line`" /></svg>
                            <svg v-else><use :xlink:href="`/storage/website-files/icons.svg#ri-arrow-down-s-line`" /></svg>
                        </button>
                        <template v-if="showShelf">
                            <div class="component-body">
                                <v-select
                                    v-model="post.shelf_id"
                                    :reduce="shelf => shelf.id"
                                    :options="shelves"
                                    :class="{ 'error': $v.post.shelf_id.$error }"
                                    placeholder="Shelf"
                                    @input="patchPost"
                                    label="name" />
                                <div v-if="$v.post.shelf_id.$error" class="validation-error">
                                    <p class="error" v-if="!$v.post.shelf_id.required">Please select the shelf.</p>
                                </div>
                            </div>
                        </template>
                    </div>
                    <div class="menu">
                        <button 
                            @click="showFeatured=!showFeatured"
                            class="component-title">
                            <p>Featured Image</p>
                            <svg v-if="showFeatured"><use :xlink:href="`/storage/website-files/icons.svg#ri-arrow-up-s-line`" /></svg>
                            <svg v-else><use :xlink:href="`/storage/website-files/icons.svg#ri-arrow-down-s-line`" /></svg>
                        </button>
                        <template v-if="showFeatured">
                            <div class="component-body">
                                <div class="post-image">
                                    <CardImage
                                        :width="600"
                                        :height="300"
                                        @onDelete="deleteFeaturedImage"
                                        :can-delete="true"
                                        :image="`/storage/${post.thumbImagePath}`"
                                        @addImage="addImage" />
                                </div>
                                <div class="flex btw">
                                    <p>Featured visible:</p>
                                    <button @click="updateType">
                                        {{ postType }}
                                    </button>
                                </div>
                            </div>
                        </template>
                    </div>
                    <div class="menu">
                        <button 
                            @click="showOrder=!showOrder"
                            class="component-title">
                            <p>Card Order</p>
                            <svg v-if="showOrder"><use :xlink:href="`/storage/website-files/icons.svg#ri-arrow-up-s-line`" /></svg>
                            <svg v-else><use :xlink:href="`/storage/website-files/icons.svg#ri-arrow-down-s-line`" /></svg>
                        </button>
                        <template v-if="showOrder">
                            <div class="component-body">
                                <draggable
                                    v-model="post.cards" 
                                    @start="isDragging=true" 
                                    @end="debounce">
                                    <div 
                                        v-for="card in post.cards"
                                        :key="`list${card.id}`"
                                        class="nav-card__item">
                                        <span v-if="card.name">event</span>
                                        <span v-else> {{ card.blurb ? `text` : `image` }} </span>
                                    </div>
                                </draggable>
                            </div>
                        </template>
                    </div>
                </div>
            </div>
        </div>
        <transition name="slide-fade">
            <div 
                v-if="updated" 
                class="updated-notifcation">
                <p>Your collection has been updated.</p>
            </div>
        </transition>
        <div v-if="serverErrors" class="updated-notifcation">
            <transition-group name="slide-fade">
                <ul 
                    v-for="(error, index) in serverErrors"
                    :key="`name${index}`">
                    <li>
                        <p> {{ error.toString() }}</p>
                    </li>
                </ul>
            </transition-group>
        </div>
    </div>
</template>

<script>
    import EditCard  from './cards/card-edit.vue'
    import EventBlock from './cards/block-event.vue'
    import ImageBlock from './cards/block-image.vue'
    import TextBlock from './cards/block-text.vue'
    import CardImage from '../../../components/Upload-Image.vue'
    import Draggable from "vuedraggable";
    import formValidationMixin from '../../../mixins/form-validation-mixin'
    import { required, maxLength } from 'vuelidate/lib/validators';
    export default {
        
        props: [ 'value', 'user', 'owner', 'community', 'shelves'],

        mixins: [formValidationMixin],

        components: { CardImage, EventBlock, Draggable, EditCard, ImageBlock, TextBlock },

        computed: {
            postStatus() {
                if (this.post.status === 'p') { return 'Live'}
                return 'Draft'
            },
            postType() {
                if (this.post.type === 'h') { return 'Hidden'}
                return 'Visible'
            }
        },

        data() {
            return {
                post: this.value,
                postBeforeEdit: { ...this.value },
                onEdit: false,
                buttonOptions:false,
                blockType:null,
                formData: new FormData(),
                serverErrors: null,
                showStatus: false,
                showFeatured: false,
                showOrder: false,
                showShelf: false,
                updated: false,
                nameEdit: false,
                tagEdit: false,
            }
        },

        methods: {

            async patchPost() {
                if ( this.checkVuelidate()) { return }
                this.addPostData();
                await axios.post(`/communities/${this.community.slug}/${this.post.slug}/update`, this.formData)
                .then( res => {
                    window.history.pushState(null, null, "/communities" + `/${this.community.slug}/${res.data.slug}/edit`);
                    this.post = res.data;
                    this.onUpdated();
                    this.clear();
                })
                .catch(err => {
                    this.onErrors(err);
                });
            },
            async deleteFeaturedImage() {
                this.formData.append('deleteImage', true)
                this.patchPost()
            },
            async updatePostOrder() {
                var list = this.post.cards.map(function(item, index){
                    item.order = index;
                    return item;
                })
                await axios.put(`/cards/${this.post.slug}/order`, list)
                this.onUpdated();
            },
            resetPost() {
                this.post = { ...this.postBeforeEdit }
                this.clear();
            },
            updateStatus() {
                if (this.post.status === 'd') {
                    this.post.status = 'p'
                } else {
                    this.post.status = 'd'
                }
                this.patchPost();
            },
            updateType() {
                if (this.post.type === 's') {
                    this.post.type = 'h'
                } else {
                    this.post.type = 's'
                }
                this.patchPost();
            },
            debounce() {
                if (this.timeout) 
                    clearTimeout(this.timeout); 
                this.timeout = setTimeout(() => {
                    this.updatePostOrder();
                }, 500); // delay
            },
            addImage(image) {
                this.formData.append('image', image);
                this.patchPost();
            },
            addPostData() {
                this.formData.append('_method', 'PUT');
                this.formData.append('name', this.post.name);
                this.formData.append('blurb', this.post.blurb);
                this.formData.append('community_id', this.community.id);
                this.formData.append('shelf_id', this.post.shelf_id);
                this.formData.append('status', this.post.status);
                this.formData.append('type', this.post.type);
            },
            updatePost(value) {
                this.clear()
                this.post = value
                this.onUpdated();
            },
            onUpdated() {
                this.$v.$reset();
                this.updated = true;
                setTimeout(() => this.updated = false, 3000);
            },
            showAddButtonOptions() {
                this.clear();
                this.buttonOptions =! this.buttonOptions;
            },
            selectButton(val) {
                this.buttonOptions = false;
                this.blockType = val;
            },
            clear() {
                this.onEdit = false;
                this.blockType = null;
                this.nameEdit = false;
                this.tagEdit = false;
                this.formData = new FormData()
            },
            clearErrors() {
                this.serverErrors = null
            }
        },

        validations: {
            post: {
                name: {
                    required,
                    maxLength: maxLength(80),
                },
                blurb: {
                    maxLength: maxLength(100)
                },
                shelf_id: {
                    required
                }
            },
        },

    }
</script>