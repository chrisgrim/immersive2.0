<template>
    <div class="listing-card edit">
        <tiptap 
            @cancel="cancelCard"
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
    import Tiptap from '../../../../components/Tiptap.vue'
    import formValidationMixin from '../../../../mixins/form-validation-mixin'
    import { required, maxLength } from 'vuelidate/lib/validators';
    export default {
        
        props: [ 'listing' ],

        mixins: [formValidationMixin],

        components: { Tiptap },

        computed: {

        },

        data() {
            return {
                card: this.initializeCardObject(),
            }
        },

        methods: {
            async saveCard() {
                if ( this.checkVuelidate()) { return }
                await axios.post(`/cards/${this.listing.slug}/create`, this.card)
                .then( res => {
                    this.$emit('update', res.data)
                })
            },
            cancelCard() {
                this.$emit('cancel')
            },
            initializeCardObject() {
                return {
                    blurb: null,
                    listing_id: this.listing.id,
                }
            },
        },

        validations: {
            card: {
                blurb: {
                    required,
                    maxLength: maxLength(40000)
                },
            },
        },

    }
</script>