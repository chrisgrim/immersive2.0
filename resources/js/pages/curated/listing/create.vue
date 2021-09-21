<template>
    <div>
        <div class="listing-create lay-a">
            <div class="wrapper">
                <div class="content">
                    <div class="field h3">
                        <input 
                            type="text" 
                            v-model="listing.name"
                            input="clearError"
                            :class="{ 'error': $v.listing.name.$error }"
                            placeholder="Collection Name... ie: Top 20 Horror Events">
                        <div v-if="$v.listing.name.$error" class="validation-error">
                            <p class="error" v-if="!$v.listing.name.required">Please add a name.</p>
                            <p class="error" v-if="!$v.listing.name.maxLength">The name is too long.</p>
                        </div>
                    </div>
                    <div class="field">
                        <input 
                            type="text" 
                            v-model="listing.blurb"
                            input="clearError"
                            :class="{ 'error': $v.listing.blurb.$error }"
                            placeholder="Tag Line">
                        <div v-if="$v.listing.blurb.$error" class="validation-error">
                            <p class="error" v-if="!$v.listing.blurb.required">Please add short tag line.</p>
                            <p class="error" v-if="!$v.listing.blurb.maxLength">The tag line is too long.</p>
                        </div>
                    </div>
                    <div class="field">
                        <v-select
                            v-model="listing.shelf_id"
                            :reduce="shelf => shelf.id"
                            :options="shelves"
                            :class="{ 'error': $v.listing.shelf_id.$error }"
                            placeholder="Shelf"
                            label="name" />
                        <div v-if="$v.listing.shelf_id.$error" class="validation-error">
                            <p class="error" v-if="!$v.listing.shelf_id.required">Please select the shelf.</p>
                        </div>
                    </div>
                    <br>
                    <button @click="submitListing">Submit</button>
                </div>
                <div class="sidebar">
                    <div class="sticky">
                        <h3>Create a collection</h3>
                        <ul>
                            <li>Once the collection has been created you can edit it at anytime.</li>
                            <li>Collections aren't visible until you make them live.</li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
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
    import formValidationMixin from '../../../mixins/form-validation-mixin'
    import { required, maxLength } from 'vuelidate/lib/validators';
    export default {
        
        props: [ 'community', 'user', 'shelves' ],

        mixins: [formValidationMixin],

        computed: {

        },

        data() {
            return {
                listing: this.initializeListingObject(),
                checkImage: false,
                serverErrors: null,
            }
        },

        methods: {

            async submitListing() {
                if ( this.checkVuelidate()) { return }
                await axios.post(`/listings/${this.community.slug}/store`, this.listing)
                .then( res => {
                    window.location.href = `/communities/${this.community.slug}/${res.data.slug}/edit`
                })
                .catch(err => {
                    this.onErrors(err);
                });
            },
            initializeListingObject() {
                return this.listing = {
                    name: null,
                    blurb: null,
                    community_id: this.community.id,
                    shelf_id: null,
                }
            },
        },

        validations: {
            listing: {
                name: {
                    required,
                    maxLength: maxLength(100)
                },
                blurb: {
                    required,
                    maxLength: maxLength(100)
                },
                shelf_id: {
                    required,
                }
            },
        },

    }
</script>