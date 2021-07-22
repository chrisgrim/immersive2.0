<template>
    <div>
        <div 
            :class="{ hover: hover && !onEdit}"
            @mouseover="hover = true"
            @mouseleave="hover = false"
            class="block">


            <template v-if="card.thumbImagePath">
                <CardImage
                    :image="`/storage/${card.thumbImagePath}`"
                    @addImage="addImageSubmit" />
            </template>


            <template v-if="card.name">
                <template v-if="onEdit">
                    <div class="field h3">
                        <input 
                            type="text" 
                            v-model="card.name"
                            :class="{ 'error': $v.card.name.$error }"
                            placeholder="Name (optional)">
                        <div v-if="$v.card.name.$error" class="validation-error">
                            <p class="error" v-if="!$v.card.name.maxLength">The name is too long.</p>
                        </div>
                    </div>
                </template>
                <template v-else>
                    <div 
                        @click="onEdit=true"
                        class="card-name">
                        <h2>{{ card.name }}</h2>
                    </div>
                </template>
            </template>


            <template v-if="card.blurb">
                <template v-if="onEdit">
                    <tiptap 
                        @cancel="resetCard"
                        @save="updateCard"
                        :class="{ 'error': $v.card.blurb.$error }"
                        v-model="card.blurb" />
                    <div v-if="$v.card.blurb.$error" class="validation-error">
                        <p class="error" v-if="!$v.card.blurb.required">Please add a description.</p>
                        <p class="error" v-if="!$v.card.blurb.maxLength">The description is too long.</p>
                    </div>
                </template>
                <template v-else>
                    <div 
                        @click="onEdit=true"
                        class="card-blurb">
                        <div v-html="card.blurb" />
                    </div>
                </template>
            </template>

            <template v-if="hover && !onEdit">
                <div class="delete">
                    <button 
                        @click="deleteCard"
                        class="round">
                        <svg>
                            <use :xlink:href="`/storage/website-files/icons.svg#ri-close-line`" />
                        </svg>
                    </button>
                </div>
            </template>
        </div>
    </div>
</template>

<script>
    import Tiptap from '../../../components/Tiptap.vue'
    import CardImage from '../../../components/Upload-Image.vue'
    import formValidationMixin from '../../../mixins/form-validation-mixin'
    import { required, maxLength } from 'vuelidate/lib/validators';
    export default {
        
        props: [ 'parentCard', 'owner' ],

        mixins: [formValidationMixin],

        components: { Tiptap, CardImage },

        computed: {

        },

        data() {
            return {
                card: this.parentCard,
                onEdit: false,
                formData: new FormData(),
                image: null,
                onAdd: null,
                hover: false,
            }
        },

        methods: {
            async updateCard() {
                // if ( this.checkVuelidate()) { return }
                this.appendCardData();
                await axios.post(`/cards/${this.card.id}`, this.formData)
                this.onEdit = false;
                this.hover = false;
            },
            async resetCard() {
                await axios.get(`/cards/${this.card.id}`)
                this.onEdit = false;
                this.hover = false
            },
            async deleteCard() {
                await axios.delete(`/cards/${this.card.id}`)
                .then( res => { 
                    console.log(res.data);
                    this.$emit('update', res.data)
                })
            },
            appendCardData() {
                this.formData.append('_method', 'PUT');
                this.card.name ? this.formData.append('name', this.card.name) : null;
                this.card.url ? this.formData.append('url', this.card.url) : null;
                this.card.blurb ? this.formData.append('blurb', this.card.blurb) : null
            },
            addImage(image, src) {
                this.formData.append('image', image);
                this.image = src;
            },
            addImageSubmit(image, src) {
                this.formData.append('image', image);
                this.image = src;
                this.updateCard();
            },
        },

        validations: {
            card: {
                name: {
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