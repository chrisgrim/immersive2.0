<template>
    <div class="listing-index">
        <div class="li-header">
            <div class="li-edit">
                <a :href="`/communities/${community.slug}`">
                    <button>View Community</button>
                </a>
            </div>
            <div class="li-header__content">
                <div class="li-wrapper">
                    <div class="field h3">
                        <input 
                            type="text" 
                            v-model="community.name"
                            @input="clearName"
                            :class="{ 'error': $v.community.name.$error }"
                            placeholder="Community Names">
                        <div v-if="$v.community.name.$error" class="validation-error">
                            <p class="error" v-if="!$v.community.name.required">Please add a name.</p>
                            <p class="error" v-if="!$v.community.name.maxLength">The name is too long.</p>
                        </div>
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
            <div class="li-image">
                <CardImage
                    :height="800"
                    :width="1500"
                    :image="`/storage/${headerImage}`"
                    @addImage="addImage" />
            </div>
        </div>
        <Curators 
            v-if="curatorsTable"
            @close="curatorsTable = false"
            @update="updateCurators"
            :community="community"
            :loadowner="owner"
            :loadcurators="curators" />
        <div class="li-content">
            <div class="li-listing">
                <div class="add">
                    <div class="add-button">
                        <button 
                            @click="onAdd=!onAdd"
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
                                <a :href="`/create/${community.slug}/listing`">
                                    Listing
                                </a>
                            </div>
                        </template>
                    </div>
                </div>
                <h3>My Collections</h3>
                <div 
                    v-if="listings && listings.length"
                    class="listings">
                    <ListingAlbum
                        :edit="true"
                        :title="true"
                        :draggable="true"
                        :parent="community"
                        v-model="listings" />
                    <div 
                        v-if="paginate.current_page !== paginate.last_page"
                        class="loadmore">
                        <button @click="fetchListings">
                            Load More
                        </button>
                    </div>
                </div>
            </div>
            <div class="com-curators">
                <h4>Owner</h4>
                <div class="curator">
                    <template v-if="owner && owner.thumbImagePath">
                        <picture>
                            <source 
                                type="image/webp" 
                                :srcset="`/storage/${owner.thumbImagePath}`"> 
                            <img 
                                :src="`/storage/${owner.thumbImagePath.slice(0, -4)}`" 
                                :alt="`${owner.name} Community`">
                        </picture>
                    </template>
                    <p>{{ owner.name }}</p>
                </div>
                <h4>Curators</h4>
                <div 
                    v-for="curator in curators"
                    :key="curator.id"
                    class="curator">
                    <template v-if="curator && curator.thumbImagePath">
                        <picture>
                            <source 
                                type="image/webp" 
                                :srcset="`/storage/${curator.thumbImagePath}`"> 
                            <img 
                                :src="`/storage/${curator.thumbImagePath.slice(0, -4)}`" 
                                :alt="`${curator.name} Community`">
                        </picture>
                    </template>
                    <p>{{ curator.name }}</p>
                </div>
                <button @click="curatorsTable =! curatorsTable">Edit curators</button>
            </div>
        </div>
        <transition name="slide-fade">
            <div 
                v-if="updated" 
                class="updated-notifcation">
                <p>Your event has been updated.</p>
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
    import CardImage from '../../../components/Upload-Image.vue'
    import Curators from './curators.vue'
    import formValidationMixin from '../../../mixins/form-validation-mixin'
    import { required, maxLength } from 'vuelidate/lib/validators';
    import ListingAlbum from '../../../components/Vue-Album-Listing.vue'
    export default {
        
        props: [ 'loadcommunity', 'user', 'loadlistings', 'loadowner' ],

        mixins: [formValidationMixin],

        components: { CardImage, Curators, ListingAlbum },

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
                listings: this.loadlistings.data,
                community: this.loadcommunity,
                communityBackup: this.loadcommunity,
                headerImage: window.innerWidth < 768 ? this.loadcommunity.thumbImagePath : this.loadcommunity.largeImagePath,
                active: null,
                formData: new FormData(),
                buttonOptions: null,
                serverErrors: null,
                paginate: this.loadlistings,
                updated: false,
                owner: this.loadowner,
                curators: this.loadcommunity.curators.filter(u => u.id !== this.loadowner.id),
                curatorsTable: false,
                onAdd: false,
            }
        },

        methods: {
            async patchCommunity() {
                this.addListingData();
                await axios.post(`/communities/${this.community.slug}`, this.formData)
                .then( res => {
                    this.onUpdated();
                })
                .catch(err => {
                    console.log(err.response.data.errors);
                    this.onErrors(err);
                });
            },
            async updateListOrder() {
                var list = this.listings.map(function(item, index){
                    item.order = index;
                    return item;
                })
                await axios.patch(`/index/${this.community.slug}/order`, list)
                .then( res => {
                    console.log(res.data);
                })
            },
            async fetchListings() {
                await axios.get(`/index/${this.community.slug}/paginate?page=${this.paginate.to}`)
                .then( res => {
                    console.log(res.data);
                    this.paginate = res.data
                    this.listings = this.listings.concat(res.data.data);
                })
            },
            async resetCommunity() {
                await axios.get(`/communities/${this.community.slug}/fetch`)
                .then( res => { this.community = res.data });
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
            showAddButtonOptions() {
                this.buttonOptions =! this.buttonOptions;
            },
            addImage(image) {
                this.formData.append('image', image);
                this.updateCommunity();
            },
            addListingData() {
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
            updateCurators(owner, curators) {
                this.owner = owner;
                this.curators = curators
            }
        },

        watch: {
            listings() {
                this.updateListOrder();
            }
        },

        validations: {
            community: {
                name: {
                    required,
                    maxLength: maxLength(60),
                },
                blurb: {
                    required,
                    maxLength: maxLength(500)
                },
            },
        },

    }
</script>