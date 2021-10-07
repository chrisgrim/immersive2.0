<template>
    <div class="post-index">
        <div class="lay-a wide">
            <div class="breadcrumbs">
                <p>
                    <a :href="`/`">Everything Immersive</a> > {{ community.name }} Community
                </p>
            </div>
            <div class="header-a">
                <div class="header-a__edit">
                    <a :href="`/communities/${community.slug}`">
                        <button>View Community</button>
                    </a>
                </div>
                <div class="header-a__content">
                    <div class="header-a__wrapper">
                        <div class="header-a__name">
                            <h2>{{ community.name }}</h2>
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
                            <button @click="patchCommunity">Save</button>
                            <button @click="resetCommunity">Cancel</button>
                        </div>
                    </div>
                </div>
                <div class="header-a__image">
                    <CardImage
                        :height="500"
                        :width="800"
                        :image="`/storage/${headerImage}`"
                        @addImage="addImage" />
                </div>
            </div>
        </div>
        <div class="lay-a">
            <div class="wrapper">
                <div class="sidebar left">
                    <div class="sticky">
                        <div class="com-curators">
                            <Curators
                                @update="updateCurators"
                                :community="community"
                                :loadowner="owner"
                                :loadcurators="curators" />
                        </div>
                        <div class="com-description">
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
                                <button @click="patchCommunity">Save</button>
                                <button @click="resetCommunity">Cancel</button>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="content">
                    <div class="add">
                        <div class="add-button">
                            <button 
                                @click="toggleButton"
                                ref="addButton"
                                class="add__icon" 
                                :class="{active: onAdd}">
                                <svg>
                                    <use :xlink:href="`/storage/website-files/icons.svg#ri-add-fill`" />
                                </svg>
                                <template v-if="onAdd">
                                    <div class="options">
                                        <div class="title">
                                            <p>Add New</p>
                                        </div>
                                        <button 
                                            class="add__post"
                                            @click="addShelf">
                                            Shelf
                                        </button>
                                        <a :href="`/posts/${community.slug}/create`">
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
                            class="shelves" 
                            v-for="(shelf, index) in shelves"
                            @mouseover="showDelete = index"
                            @mouseleave="showDelete = null"
                            :key="shelf.id">
                            <div 
                                v-if="showDelete === index && shelf.posts.data.length < 1 && shelf.name !== 'Archived'"
                                class="delete-btn">
                                <button 
                                    @click="deleteShelf(shelf)"
                                    class="btn-icon">
                                    <svg>
                                        <use :xlink:href="`/storage/website-files/icons.svg#ri-close-line`" />
                                    </svg>
                                </button>
                            </div>
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
    import CardImage from '../../components/Upload-Image.vue'
    import Curators from './posts/curators.vue'
    import Shelf from './shelves/shelf-edit.vue'
    import formValidationMixin from '../../mixins/form-validation-mixin'
    import { required, maxLength } from 'vuelidate/lib/validators';
    export default {
        
        props: [ 'loadcommunity', 'user', 'loadowner', 'loadshelves'],

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
                headerImage: window.innerWidth < 768 ? this.loadcommunity.thumbImagePath : this.loadcommunity.largeImagePath,
                active: null,
                formData: new FormData(),
                serverErrors: null,
                updated: false,
                owner: this.loadowner,
                curators: this.loadcommunity.curators.filter(u => u.id !== this.loadowner.id),
                onAdd: false,
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
                    console.log(err.response.data.errors);
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