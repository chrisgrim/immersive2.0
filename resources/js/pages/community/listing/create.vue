<template>
    <div class="listing-show">
        <div class="listing-content">
            <div>
                <CardImage
                    @addImage="addImage" />
            </div>
            <div class="field">
                <input 
                    type="text" 
                    v-model="listing.name"
                    placeholder="Listing Name">
            </div>
            <div class="listing-blurb">    
                <tiptap 
                    @cancel="onEdit=false"
                    @save="submitListing"
                    v-model="listing.blurb" />
            </div>
        </div>
    </div>
</template>

<script>
    import Tiptap from '../../../components/Tiptap.vue'
    import CardImage from '../../../components/Upload-Image.vue'
    export default {
        
        props: [ 'community', 'user', ],

        components: { CardImage, Tiptap },

        computed: {

        },

        data() {
            return {
                listing: this.initializeListingObject(),
                formData: new FormData(),
            }
        },

        methods: {

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

            addImage(image) {
                this.formData.append('image', image);
            },

            async submitListing() {
                this.addListingData();
                await axios.post(`/listings/`, this.formData)
                .then( res => {
                    console.log(res.data);
                    window.location.href = `/${this.community.slug}/${res.data.slug}`
                })
            },

            addListingData() {
                this.formData.append('name', this.listing.name);
                this.formData.append('blurb', this.listing.blurb);
                this.formData.append('thumbImagePath', this.listing.thumbImagePath);
                this.formData.append('largeImagePath', this.listing.largeImagePath);
                this.formData.append('url', this.listing.url);
                this.formData.append('community_id', this.listing.community_id);
            },

        }

    }
</script>