<template>
    <div class="post-card edit">
        <div class="header">
            <CardImage
                text="Image must be at least 800px by 450px"
                :height="450"
                :width="800"
                :loading="disabled"
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
                disabled: false,
            }
        },

        methods: {
            async saveCard() {
                await axios.post(`/cards/${this.post.slug}/create`, this.formData)
                .then( res => {
                    this.$emit('update', res.data)
                    this.disabled = false
                })
            },
            addImage(image) {
                this.disabled = true
                this.formData.append('image', image);
                this.formData.append('type', 'i');
                this.saveCard();
            },
        },

    }
</script>