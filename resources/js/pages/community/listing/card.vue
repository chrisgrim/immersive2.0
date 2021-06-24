<template>
    <div>
        <!-- Edit card -->
        <template v-if="onEdit">
            <CardImage
                :image="`/storage/${card.thumbImagePath}`"
                @addImage="addImage" />
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
        <!-- Show Card -->
        <template v-else>
            <template v-if="card.url">
                <a target="_blank" rel="noopener noreferrer nofollow" :href="card.url">
                    <div 
                        v-if="card.thumbImagePath"
                        class="listing__card-image">
                        <picture v-if="card.thumbImagePath">
                            <source 
                                type="image/webp" 
                                :srcset="`/storage/${card.thumbImagePath}`"> 
                            <img 
                                :src="`/storage/${card.thumbImagePath.slice(0, -4)}`" 
                                :alt="`${card.name}`">
                        </picture>
                    </div>
                </a>
            </template>
            <template v-else>
                <div 
                    v-if="card.thumbImagePath"
                    class="listing__card-image">
                    <picture v-if="card.thumbImagePath">
                        <source 
                            type="image/webp" 
                            :srcset="`/storage/${card.thumbImagePath}`"> 
                        <img 
                            :src="`/storage/${card.thumbImagePath.slice(0, -4)}`" 
                            :alt="`${card.name}`">
                    </picture>
                </div>
            </template>
            <template v-if="card.url">
                <div 
                    v-if="card.name"
                    class="card-name">
                    <a target="_blank" rel="noopener noreferrer nofollow" :href="card.url">
                        <h2>{{ card.name }}</h2>
                    </a>
                </div>
            </template>
            <template v-else>
                <div 
                    v-if="card.name"
                    class="card-name">
                    <h2>{{ card.name }}</h2>
                </div>
            </template>
            <div 
                v-if="card.blurb"
                class="card-blurb">
                <div v-html="card.blurb" />
            </div>
            <template v-if="owner">
                <button
                    class="edit"
                    @click="onEdit=true">Edit</button>
                <button
                    class="delete"
                    @click="deleteCard">Delete</button>
            </template>
        </template>
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
            }
        },

        methods: {
            async updateCard() {
                if ( this.checkVuelidate()) { return }
                this.appendCardData();
                await axios.post(`/cards/${this.card.id}`, this.formData)
                location.reload();
            },
            async resetCard() {
                await axios.get(`/cards/${this.card.id}`)
                .then( res => { this.card = res.data })
                this.onEdit = null;
            },
            async deleteCard() {
                await axios.delete(`/cards/${this.card.id}`)
                .then( res => { 
                    this.$emit('update', res.data)
                })
            },
            appendCardData() {
                this.formData.append('_method', 'PUT');
                this.card.name ? this.formData.append('name', this.card.name) : null;
                this.card.url ? this.formData.append('url', this.card.url) : null;
                this.formData.append('blurb', this.card.blurb);
            },
            addImage(image) {
                this.formData.append('image', image);
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