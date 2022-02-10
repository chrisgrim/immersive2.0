<template>
    <div class="mt-8 relative p-4">
        <CardImage
            :loading="disabled"
            @addImage="addImage" />
    </div>
</template>

<script>
    import CardImage from './Components/vue-add-image.vue'
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