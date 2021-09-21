<template>
    <div class="post-card edit">
        <div class="header">
            <CardImage
                @addImage="addImage" />
        </div>
    </div>
</template>

<script>
    import CardImage from '../../../../components/Upload-Image.vue'
    import formValidationMixin from '../../../../mixins/form-validation-mixin'
    export default {
        
        props: [ 'post' ],

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
                await axios.post(`/cards/${this.post.slug}/create`, this.formData)
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