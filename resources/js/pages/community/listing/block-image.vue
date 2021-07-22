<template>
    <div class="listing-card edit">
        <div class="header">
            <CardImage
                @addImage="addImage" />
        </div>
    </div>
</template>

<script>
    import CardImage from '../../../components/Upload-Image.vue'
    import formValidationMixin from '../../../mixins/form-validation-mixin'
    export default {
        
        props: [ 'listing' ],

        mixins: [formValidationMixin],

        components: { CardImage },

        computed: {

        },

        data() {
            return {
                formData: new FormData(),
            }
        },

        methods: {
            async saveCard() {
                await axios.post(`/create/${this.listing.slug}/card`, this.formData)
                .then( res => {
                    this.$emit('update', res.data)
                })
            },
            addImage(image) {
                this.formData.append('image', image);
                this.saveCard();
            },
        },

    }
</script>