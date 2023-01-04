<template>
    <div>
        <div class="m-auto w-full md:px-12 md:py-8 lg:py-0 lg:px-32 max-w-screen-xl">
            <div class="py-12">
                <p class="text-1xl">
                    <a class="underline" :href="`/`">Everything Immersive</a> > {{ community.name }} Community
                </p>
            </div>
            <div class="relative overflow-hidden mb-8 rounded-2xl block h-full w-full md:flex md:h-[45rem]">
                <div class="absolute right-20 bottom-20 z-10">
                    <a :href="`/communities/${community.slug}`">
                        <button class="border-none bg-white rounded-2xl mt-16 py-6 px-8">View Community</button>
                    </a>
                </div>
                <div class="p-8 items-center justify-center flex bg-black text-center md:justify-start md:text-left md:w-5/12 md:h-[45rem] md:px-24">
                    <div class="w-full">
                        <div>
                            <h2 class="text-white text-6xl mb-4">{{ community.name }}</h2>
                        </div>
                        <div class="field">
                            <textarea 
                                type="text"
                                v-model="community.blurb" 
                                placeholder="Community description."
                                :class="{ 'error': $v.community.blurb.$error }"
                                @input="$v.community.blurb.$touch"
                                rows="6" />
                            <div v-if="$v.community.blurb.$error" class="validation-error">
                                <p class="error" v-if="!$v.community.blurb.required">Must provide a short description</p>
                                <p class="error" v-if="!$v.community.blurb.maxLength">Description is too long</p>
                            </div>
                        </div>
                        <div 
                            v-if="$v.$anyDirty"
                            class="buttons">
                            <button 
                                class="border-black bg-white rounded-2xl mt-16 py-4 px-6 hover:bg-black hover:text-white hover:border-white" 
                                @click="patchCommunity">Save</button>
                            <button 
                                class="border-black bg-white rounded-2xl mt-16 py-4 px-6 hover:bg-black hover:text-white hover:border-white" 
                                @click="resetCommunity">Cancel</button>
                        </div>
                    </div>
                </div>
                <div class="relative inline-block bg-slate-400 md:h-[45rem] md:w-7/12">
                    <CardImage
                        :image="`${envImageUrl}${headerImage}`"
                        :loading="loading"
                        @addImage="addImage" />
                </div>
            </div>
        </div>
        <div class="m-auto w-full md:px-12 md:py-8 lg:py-0 lg:px-32 max-w-screen-xl">
            <div class="flex flex-col md:flex-row">
                <div class="w-full inline-block md:w-4/12 md:py-8 md:pr-8">
                    <div class="sticky top-16">
                        <div class="mb-8">
                            <Curators
                                @update="updateCurators"
                                :loadowner="owner"
                                :user="user"
                                :community="community"
                                :loadcurators="curators" />
                        </div>
                        <div class="p-8 rounded-2xl my-8 border">
                            <div class="field">
                                <textarea 
                                    type="text"
                                    v-model="community.description" 
                                    placeholder="Community description."
                                    :class="{ 'error': $v.community.description.$error }"
                                    @input="$v.community.description.$touch"
                                    rows="6" />
                                <div v-if="$v.community.description.$error" class="validation-error">
                                    <p class="error" v-if="!$v.community.description.maxLength">Description is too long</p>
                                </div>
                            </div>
                            <div 
                                v-if="$v.$anyDirty"
                                class="buttons">
                                <button 
                                    class="px-4 py-2 hover:bg-black hover:text-white" 
                                    @click="patchCommunity">Save</button>
                                <button 
                                    class="px-4 py-2 hover:bg-black hover:text-white"
                                    @click="resetCommunity">Cancel</button>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="inline-block w-full md:w-8/12">
                    <div class="relative top-[-1rem]">
                        <div class="flex justify-end relative z-50">
                            <button 
                                @click="toggleButton"
                                ref="addButton"
                                class="border-none w-20 h-20 flex p-0 rounded-full justify-center items-center hover:bg-slate-300" 
                                :class="{'bg-black hover:bg-slate-300': onAdd}">
                                <svg 
                                    :class="{'fill-white rotate-45': onAdd}"
                                    class="w-16 h-16">
                                    <use :xlink:href="`/storage/website-files/icons.svg#ri-add-fill`" />
                                </svg>
                                <template v-if="onAdd">
                                    <div class="p-4 rounded-2xl shadow-custom-1 absolute right-0 flex flex-col  bg-white min-w-[20rem] top-[115%]">
                                        <div class="mt-2 px-4 py-2">
                                            <p class="text-lg w-full text-left">Add New</p>
                                        </div>
                                        <button 
                                            class="w-full text-left border-none px-4 py-2 font-semibold text-3xl block rounded-xl hover:bg-gray-400 hover:text-white"
                                            @click="addShelf">
                                            Shelf
                                        </button>
                                        <a 
                                            class="w-full text-left px-4 py-2 font-semibold text-3xl block rounded-xl hover:bg-gray-400 hover:text-white"
                                            :href="`/posts/${community.slug}/create`">
                                            Post
                                        </a>
                                    </div>
                                </template>
                            </button>
                        </div>
                    </div>
                    <draggable
                        v-model="shelves"
                        @start="isDragging=true" 
                        @end="debounce">
                        <div 
                            class="relative" 
                            v-for="(shelf, index) in shelves"
                            @mouseover="showDelete = index"
                            @mouseleave="showDelete = null"
                            :key="shelf.id">
                            <button 
                                v-if="showDelete === index && shelf.posts.data.length < 1 && shelf.name !== 'Archived'"
                                @click="deleteShelf(shelf)"
                                class="items-center justify-center rounded-full p-0 w-12 h-12 flex border-2 bg-white border-black absolute top-[-1rem] right-[-1rem] hover:bg-black hover:fill-white">
                                <svg class="w-12 h-12">
                                    <use :xlink:href="`/storage/website-files/icons.svg#ri-close-line`" />
                                </svg>
                            </button>
                            <Shelf 
                                :archived="archived"
                                @updated="onUpdated"
                                :community="community"
                                :loadshelf="shelf" />
                        </div>
                    </draggable>
                </div>
            </div>
        </div>
        <transition name="slide-fade">
            <div 
                v-if="updated" 
                class="updated-notifcation">
                <p>Your changes have been saved.</p>
            </div>
        </transition>
        <div 
            v-if="serverErrors" 
            class="updated-notifcation">
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
    import CardImage from './Components/add-image.vue'
    import Curators from './Posts/curators.vue'
    import Shelf from './Shelves/shelf-edit.vue'
    import formValidationMixin from '../../mixins/form-validation-mixin'
    import { required, maxLength } from 'vuelidate/lib/validators';
    export default {
        
        props: [ 'loadcommunity', 'user', 'loadshelves', 'loadowner', 'mobile'],

        mixins: [formValidationMixin],

        components: { CardImage, Curators, Shelf },

        computed: {
            getStatus() {
                return post => this.status(post)
            },
            getStatusCircle() {
                return post => this.statusCircle(post)
            },
            archived() {
                return this.loadshelves.find( shelf => shelf.status === 'a')
            }
        },

        data() {
            return {
                shelves: this.loadshelves,
                community: this.loadcommunity,
                communityBeforeEdit: { ...this.loadcommunity },
                headerImage: this.mobile ? this.loadcommunity.thumbImagePath : this.loadcommunity.largeImagePath,
                active: null,
                formData: new FormData(),
                serverErrors: null,
                updated: false,
                owner: this.loadowner,
                curators: this.loadcommunity.curators.filter(u => u.id !== this.loadowner.id),
                onAdd: false,
                loading: false,
                showDelete: null,
            }
        },

        methods: {
            async patchCommunity() {
                this.addCommunityData();
                await axios.post(`/communities/${this.community.slug}`, this.formData)
                .then( res => {
                    this.onUpdated();
                })
                .catch(err => {
                    this.onErrors(err);
                });
            },
            async resetCommunity() {
                this.community = { ...this.communityBeforeEdit }
                this.$v.$reset();
            },
            async addShelf() {
                await axios.post(`/shelves/${this.community.slug}`)
                .then( res => { 
                    this.shelves = res.data 
                });
                this.clear();
            },
            async updateShelfOrder() {
                var list = this.shelves.map(function(item, index){
                    item.order = index;
                    return item;
                })
                await axios.put(`/shelves/${this.community.slug}/order`, list)
                .then( res => {
                    this.onUpdated();
                })
            },
            async deleteShelf(shelf) {
                if (shelf.name === 'Archived') { return alert('Cannot delete archived shelf') }
                if (this.shelves.length <= 1) { return alert('Communities must have at least one shelf') }
                if (shelf.posts.length) { return alert(`Can't delete shelf with posts`)}
                await axios.delete(`/shelves/${shelf.id}`)
                .then( res => { 
                    this.shelves = res.data
                });
                this.$v.$reset();
            },
            toggleButton() {
                this.onAdd =! this.onAdd
            },
            status(post) {
                if (post.status === 'p') return 'live';
                return 'draft'
            },
            statusCircle(post) {
                if (post.status === 'p') return `background: rgb(27, 187, 27)`;
                if (post.status === 'd') return `background: rgb(255 194 21)`;
            },
            addImage(image) {
                this.formData.append('image', image);
                this.patchCommunity();
            },
            addCommunityData() {
                this.formData.append('_method', 'PUT');
                this.formData.append('name', this.community.name);
                this.formData.append('blurb', this.community.blurb);
                this.formData.append('description', this.community.description);
            },
            onUpdated() {
                this.$v.$reset();
                this.updated = true;
                setTimeout(() => this.updated = false, 3000);
            },
            clearName() {
                this.$v.community.name.$touch();
                this.serverErrors = null;
            },
            clear() {
                this.onAdd = false;
            },
            updateCurators(owner, curators) {
                this.owner = owner;
                this.curators = curators
            },
            debounce() {
                if (this.timeout) 
                    clearTimeout(this.timeout); 
                this.timeout = setTimeout(() => {
                    this.updateShelfOrder();
                }, 500); // delay
            },
            onClickOutside(event) {
                let arr =  this.$refs.addButton;
                if (!arr || arr.contains(event.target)) return
                this.onAdd = false
            },
        },

        validations: {
            community: {
                blurb: {
                    required,
                    maxLength: maxLength(254)
                },
                description: {
                    maxLength: maxLength(5000)
                },
            },
        },

        mounted() {
            setTimeout(() => document.addEventListener("click", this.onClickOutside), 200)
        },

        destroyed() {
            document.removeEventListener("click", this.onClickOutside)
        },

    }
</script>