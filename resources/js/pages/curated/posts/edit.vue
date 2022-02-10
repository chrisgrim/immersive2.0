<template>
    <div class="m-auto w-full md:px-12 md:py-8 lg:py-0 lg:px-32 max-w-screen-xl">
        <div class="py-12">
            <p class="text-1xl">
                <a 
                    class="underline" 
                    :href="`/communities/${community.slug}/edit`">{{community.name}}</a> > {{post.name}}
            </p>
        </div>
        <div class="flex flex-col md:flex-row">
            <div class="w-full inline-block md:w-8/12 md:py-8 md:pr-8">
                <div class="mb-8">
                    <template v-if="postEdit">
                        <div class="field h3">
                            <input 
                                type="text" 
                                v-model="post.name"
                                @input="clearErrors"
                                :class="{ 'error': $v.post.name.$error }"
                                placeholder="Collection Name">
                            <div v-if="$v.post.name.$error" class="validation-error">
                                <p class="error" v-if="!$v.post.name.required">Please add a name.</p>
                                <p class="error" v-if="!$v.post.name.serverError">Your community already has a post with a similar name</p>
                                <p class="error" v-if="!$v.post.name.maxLength">The name is too long.</p>
                            </div>
                        </div>
                        <div class="field">
                            <input 
                                type="text"
                                v-model="post.blurb"
                                :class="{ 'error': $v.post.blurb.$error }"
                                placeholder="Collection tag line">
                            <div v-if="$v.post.blurb.$error" class="validation-error">
                                <p class="error" v-if="!$v.post.blurb.maxLength">The name is too long.</p>
                            </div>
                        </div>
                        <div class="flex justify-between">
                            <button 
                                class="px-4 py-2" 
                                @click="resetPost">Cancel</button>
                            <button 
                                @click="patchPost"
                                class="bg-black text-white px-4 py-2">Save</button>
                        </div>
                    </template>
                    <template v-else>
                        <div 
                            @click="postEdit=true"
                            class="">
                            <h2>{{ post.name }}</h2>
                        </div>
                        <div 
                            @click="postEdit=true"
                            class="mt-4 relative">
                            <p>{{ post.blurb }}</p>
                        </div>
                    </template>
                    <div class="relative">
                        <draggable
                            v-model="post.cards" 
                            @start="isDragging=true" 
                            @end="debounce">
                            <div 
                                v-for="card in post.cards"
                                :key="card.id"
                                class="mt-12">   
                                <EditCard 
                                    :mobile="mobile"
                                    @update="updatePost"
                                    :parent-card="card" />
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
                        <div class="top-0 bg-white flex-col flex w-96 mt-24 rounded-2xl p-4 border">
                            <button 
                                @click="showAddButtonOptions"
                                class="border-none h-16 items-center flex px-4">
                                Add card
                                <svg class="w-8 ml-2">
                                    <use v-if="!buttonOptions" :xlink:href="`/storage/website-files/icons.svg#ri-add-circle-line`" />
                                    <use v-else :xlink:href="`/storage/website-files/icons.svg#ri-close-circle-line`" />
                                </svg>
                            </button>
                            <template v-if="buttonOptions">
                                <button 
                                    class="w-full text-left border-none px-4 py-2 font-semibold text-3xl block rounded-xl hover:bg-gray-400 hover:text-white"
                                    @click="selectButton('i')">
                                    Image Block
                                </button>
                                <button 
                                    class="w-full text-left border-none px-4 py-2 font-semibold text-3xl block rounded-xl hover:bg-gray-400 hover:text-white"
                                    @click="selectButton('t')">
                                    Text Block
                                </button>
                                <button 
                                    class="w-full text-left border-none px-4 py-2 font-semibold text-3xl block rounded-xl hover:bg-gray-400 hover:text-white"
                                    @click="selectButton('e')">
                                    Event Block
                                </button>
                            </template>
                        </div>
                    </div>
                </div>
            </div>
            <SideBar 
                @update="patchPost"
                @deleteImage="deleteImage"
                @addImage="addImage"
                @addEventFeaturedImage="addEventFeaturedImage"
                @reOrder="debounce"
                :community="community"
                :shelves="shelves"
                v-model="post" />
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
    import EditCard  from './Cards/card-edit.vue'
    import EventBlock from './Cards/block-event.vue'
    import ImageBlock from './Cards/block-image.vue'
    import TextBlock from './Cards/block-text.vue'
    import SideBar from './Components/post-sidebar.vue'
    import Draggable from "vuedraggable";
    import formValidationMixin from '../../../mixins/form-validation-mixin'
    import { required, maxLength } from 'vuelidate/lib/validators';
    export default {
        
        props: [ 'value', 'user', 'curator', 'community', 'shelves', 'mobile'],

        mixins: [formValidationMixin],

        components: { EventBlock, Draggable, EditCard, ImageBlock, TextBlock, SideBar },

        data() {
            return {
                post: this.value,
                postBeforeEdit: { ...this.value },
                onEdit: false,
                buttonOptions:false,
                blockType:null,
                formData: new FormData(),
                serverErrors: null,
                updated: false,
                postEdit: false,
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
                    this.postBeforeEdit = { ...res.data }
                    this.onUpdated();
                    this.clear();
                })
                .catch(err => {
                    this.onErrors(err);
                });
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
            debounce() {
                if (this.timeout) 
                    clearTimeout(this.timeout); 
                this.timeout = setTimeout(() => {
                    this.updatePostOrder();
                }, 500); // delay
            },
            addImage(image) {
                this.formData.append('image', image);
                this.post.image_type = 'u'
                this.patchPost();
            },
            addEventFeaturedImage(event) {
                this.formData.append('largeImagePath', event.largeImagePath);
                this.formData.append('thumbImagePath', event.thumbImagePath);
                this.post.image_type = 'e'
                this.patchPost();
            },
            deleteImage() {
                this.formData.append('deleteImage', true)
                this.patchPost()
            },
            addPostData() {
                this.formData.append('_method', 'PUT');
                this.formData.append('name', this.post.name);
                this.formData.append('blurb', this.post.blurb);
                this.formData.append('community_id', this.community.id);
                this.formData.append('shelf_id', this.post.shelf_id);
                this.formData.append('status', this.post.status);
                this.formData.append('type', this.post.type);
                this.formData.append('image_type', this.post.image_type);
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
                this.postEdit = false;
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
                    maxLength: maxLength(100),
                    serverError() {
                        return this.serverErrors && this.serverErrors.name ? false : true
                    }
                },
                blurb: {
                    maxLength: maxLength(100)
                },
            },
        },

    }
</script>