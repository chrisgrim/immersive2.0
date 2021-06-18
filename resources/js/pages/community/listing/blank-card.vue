<template>
    <div ref="card" class="blank-card">
        <CardImage
            @addImage="addImage" />
        <tiptap 
            @save="saveCard"
            v-model="card.blurb" />
    </div>
</template>

<script>
    import Tiptap from '../../../components/Tiptap.vue'
    import CardImage from '../../../components/Upload-Image.vue'
    export default {
        
        props: [ 'listing' ],

        components: { Tiptap, CardImage },

        computed: {

        },

        data() {
            return {
                card: this.initializeCardObject(),
                formData: new FormData(),
            }
        },

        methods: {
            async saveCard() {
                this.addCardData();
                await axios.post('/cards', this.formData)
                .then( res => {
                    this.$emit('update', res.data)
                })
            },
            addImage(image) {
                this.formData.append('image', image);
            },
            addCardData() {
                this.formData.append('blurb', this.card.blurb);
                this.formData.append('thumbImagePath', this.card.thumbImagePath);
                this.formData.append('listing_id', this.card.listing_id);
            },
            initializeCardObject() {
                return {
                    blurb: null,
                    thumbImagePath: null,
                    listing_id: this.listing.id,
                }
            },
        },

        mounted() {
            this.$refs.card.scrollIntoView()
        }

    }
</script>