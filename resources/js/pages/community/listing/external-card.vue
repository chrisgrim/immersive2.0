<template>
    <div class="blank-card">
        <CardImage
            @addImage="addImage" />
        <div class="field h3">
            <input 
                type="text" 
                v-model="card.name"
                :class="{ 'error': $v.card.name.$error }"
                placeholder="Title (required)">
            <div v-if="$v.card.name.$error" class="validation-error">
                <p class="error" v-if="!$v.card.name.required">Please add a card name.</p>
                <p class="error" v-if="!$v.card.name.maxLength">The name is too long.</p>
            </div>
        </div>
        <div class="field">
            <input 
                type="text" 
                v-model="card.url"
                :class="{ 'error': $v.card.url.$error }"
                placeholder="Url (required)">
            <div v-if="$v.card.url.$error" class="validation-error">
                <p class="error" v-if="!$v.card.url.required">Please add a description.</p>
                <p class="error" v-if="!$v.card.url.url">Must be a url.</p>
            </div>
        </div>
        <tiptap 
            @save="saveCard"
            :class="{ 'error': $v.card.blurb.$error }"
            v-model="card.blurb" />
        <div v-if="$v.card.blurb.$error" class="validation-error">
            <p class="error" v-if="!$v.card.blurb.required">Please add a description.</p>
            <p class="error" v-if="!$v.card.blurb.maxLength">The description is too long.</p>
        </div>
    </div>
</template>

<script>
    import Tiptap from '../../../components/Tiptap.vue'
    import CardImage from '../../../components/Upload-Image.vue'
    import formValidationMixin from '../../../mixins/form-validation-mixin'
    import { required, maxLength, url } from 'vuelidate/lib/validators';
    export default {
        
        props: [ 'listing' ],

        mixins: [formValidationMixin],

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
                if ( this.checkVuelidate()) { return }
                this.addCardData();
                await axios.post(`/create/${this.listing.slug}/card`, this.formData)
                .then( res => {
                    this.$emit('update', res.data)
                })
            },
            addImage(image) {
                this.formData.append('image', image);
            },
            addCardData() {
                this.formData.append('blurb', this.card.blurb);
                this.formData.append('url', this.card.url);
                this.formData.append('name', this.card.name);
                this.card.thumbImagePath ? this.formData.append('thumbImagePath', this.card.thumbImagePath) : null;
            },
            initializeCardObject() {
                return {
                    blurb: null,
                    thumbImagePath: null,
                    listing_id: this.listing.id,
                }
            },
        },

        validations: {
            card: {
                name: {
                    required,
                    maxLength: maxLength(50),
                },
                blurb: {
                    required,
                    maxLength: maxLength(50000)
                },
                url: {
                    required,
                    url
                },
            },
        },

    }
</script>