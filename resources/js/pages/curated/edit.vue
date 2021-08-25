<template>
    <div class="listing-index">
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
        <div class="li-content">
            <div class="li-listing">
                <div class="add">
                    <div class="add-button">
                        <button 
                            @click="onAdd=!onAdd"
                            class="add__icon" 
                            :class="{active: onAdd}">
                            <svg>
                                <use :xlink:href="`/storage/website-files/icons.svg#ri-add-fill`" />
                            </svg>
                        </button>
                        <template v-if="onAdd">
                            <div class="options">
                                <div class="title">
                                    <p>Add New</p>
                                </div>
                                <button 
                                    class="add__listing"
                                    @click="addSection">
                                    Shelf
                                </button>
                                <a :href="`/listings/${community.slug}/create`">
                                    Listing
                                </a>
                            </div>
                        </template>
                    </div>
                </div>
                <draggable
                    v-model="sections"
                    @start="isDragging=true" 
                    @end="debounce">
                    <div 
                        class="shelves" 
                        v-for="(section, index) in sections"
                        @mouseover="showDelete = index"
                        @mouseleave="showDelete = null"
                        :key="section.id">
                        <div 
                            v-if="showDelete === index && sections.length > 1"
                            class="delete">
                            <button 
                                @click="deleteSection(section)"
                                class="btn-icon">
                                <svg>
                                    <use :xlink:href="`/storage/website-files/icons.svg#ri-close-line`" />
                                </svg>
                            </button>
                        </div>
                        <Shelf 
                            @updated="onUpdated"
                            :community="community"
                            :loadshelf="section" />
                    </div>
                </draggable>
            </div>
            <div class="com-curators">
                <Curators
                    @update="updateCurators"
                    :community="community"
                    :loadowner="owner"
                    :loadcurators="curators" />
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
    import Curators from './listing/curators.vue'
    import Shelf from './listing/shelves/shelf-edit.vue'
    import formValidationMixin from '../../mixins/form-validation-mixin'
    import { required, maxLength } from 'vuelidate/lib/validators';
    export default {
        
        props: [ 'loadcommunity', 'user', 'loadowner', 'loadsections'],

        mixins: [formValidationMixin],

        components: { CardImage, Curators, Shelf },

        computed: {
            getStatus() {
                return listing => this.status(listing)
            },
            getStatusCircle() {
                return listing => this.statusCircle(listing)
            },
        },

        data() {
            return {
                sections: this.loadsections,
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
            async addSection() {
                await axios.post(`/sections/community/${this.community.slug}`)
                .then( res => { 
                    this.sections = res.data 
                });
                this.clear();
            },
            async updateSectionOrder() {
                var list = this.sections.map(function(item, index){
                    item.order = index;
                    return item;
                })
                await axios.put(`/sections/order/reorder`, list)
                .then( res => {
                    this.onUpdated();
                })
            },
            async deleteSection(section) {
                if (this.sections.length <= 1) { return alert('Communities must have at least one shelf') }
                if (section.featured.length) { return alert(`Can't delete shelf with posts`)}
                await axios.delete(`/sections/${section.id}`)
                .then( res => { 
                    this.sections = this.sections.filter( sec => sec.id !== section.id )
                });
                this.$v.$reset();
            },
            status(listing) {
                if (listing.status === 'p') return 'live';
                return 'draft'
            },
            statusCircle(listing) {
                if (listing.status === 'p') return `background: rgb(27, 187, 27)`;
                if (listing.status === 'd') return `background: rgb(255 194 21)`;
            },
            addImage(image) {
                this.formData.append('image', image);
                this.patchCommunity();
            },
            addCommunityData() {
                this.formData.append('_method', 'PUT');
                this.formData.append('name', this.community.name);
                this.formData.append('blurb', this.community.blurb);
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
                    this.updateSectionOrder();
                }, 500); // delay
            },
        },

        validations: {
            community: {
                blurb: {
                    required,
                    maxLength: maxLength(500)
                },
            },
        },

    }
</script>