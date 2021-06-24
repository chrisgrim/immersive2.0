<template>
    <div class="listing-show">
        <div class="listing-content">
            <div>
                <CardImage
                    :validate="checkImage"
                    @addImage="addImage" />
            </div>
            <div class="field">
                <input 
                    type="text" 
                    v-model="listing.name"
                    :class="{ 'error': $v.listing.name.$error }"
                    placeholder="Listing Name">
                <div v-if="$v.listing.name.$error" class="validation-error">
                    <p class="error" v-if="!$v.listing.name.required">Please add a name.</p>
                    <p class="error" v-if="!$v.listing.name.maxLength">The name is too long.</p>
                </div>
            </div>
            <div class="listing-blurb">    
                <tiptap 
                    @cancel="onEdit=false"
                    @save="submitListing"
                    :class="{ 'error': $v.listing.blurb.$error }"
                    v-model="listing.blurb" />
                <div v-if="$v.listing.blurb.$error" class="validation-error">
                    <p class="error" v-if="!$v.listing.blurb.required">Please add a description.</p>
                    <p class="error" v-if="!$v.listing.blurb.maxLength">The description is too long.</p>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
    import Tiptap from '../../../components/Tiptap.vue'
    import CardImage from '../../../components/Upload-Image.vue'
    import formValidationMixin from '../../../mixins/form-validation-mixin'
    import { required, maxLength } from 'vuelidate/lib/validators';
    export default {
        
        props: [ 'community', 'user', ],

        mixins: [formValidationMixin],

        components: { CardImage, Tiptap },

        computed: {

        },

        data() {
            return {
                listing: this.initializeListingObject(),
                formData: new FormData(),
                checkImage: false,
            }
        },

        methods: {

            async submitListing() {
                if ( this.checkVuelidate() || this.checkForImage()) { return }
                this.addListingData();
                await axios.post(`/create/${this.community.slug}/listing`, this.formData)
                .then( res => {
                    window.location.href = `/communities/${this.community.slug}/${res.data.slug}`
                })
            },
            addImage(image) {
                this.formData.append('image', image);
            },
            addListingData() {
                this.formData.append('name', this.listing.name);
                this.formData.append('blurb', this.listing.blurb);
            },
            checkForImage() {
                if (!this.formData.has('image')) {
                    this.checkImage = true;
                    return true
                }
            },
            initializeListingObject() {
                return this.listing = {
                    name: null,
                    blurb: null,
                    url: null,
                    thumbImagePath: null,
                    largeImagePath: null,
                    community_id: this.community.id,
                }
            },
        },

        validations: {
            listing: {
                name: {
                    required,
                    maxLength: maxLength(50),
                },
                blurb: {
                    required,
                    maxLength: maxLength(50000)
                },
            },
        },

    }
</script>