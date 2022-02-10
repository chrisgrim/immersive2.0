<template>
    <div class="mt-8 relative p-4">
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
    import Tiptap from './Components/Tiptap.vue'
    import formValidationMixin from '../../../../mixins/form-validation-mixin'
    import { required, maxLength } from 'vuelidate/lib/validators';
    export default {
        
        props: [ 'post' ],

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
                await axios.post(`/cards/${this.post.slug}/create`, this.card)
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
                    post_id: this.post.id,
                    type: 't'
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