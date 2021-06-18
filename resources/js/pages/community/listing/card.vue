<template>
    <div>
        <!-- Edit card -->
        <template v-if="onEdit">
            <CardImage
                :image="`/storage/${card.thumbImagePath}`"
                @addImage="addImage" />
            <div 
                v-if="card.name"
                class="field h3">
                <input 
                    type="text" 
                    v-model="card.name"
                    placeholder="Title">
            </div>
            <tiptap 
                @cancel="resetCard"
                @save="updateCard"
                v-model="card.blurb" />
        </template>
        <!-- Show Card -->
        <template v-else>
            <div 
                v-if="card.thumbImagePath"
                class="listing__card-image">
                <picture>
                    <source 
                        type="image/webp" 
                        :srcset="`/storage/${card.thumbImagePath}`"> 
                    <img 
                        :src="`/storage/${card.thumbImagePath.slice(0, -4)}`" 
                        :alt="`${card.name}`">
                </picture>
            </div>
            <div 
                v-if="card.name"
                class="card-name">
                <h2>{{ card.name }}</h2>
            </div>
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
    export default {
        
        props: [ 'parentCard', 'owner' ],

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
                await axios.patch(`/cards/${this.card.id}`, this.card)
                this.onEdit = null;
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
            addImage(image) {
                this.formData.append('image', image);
            },
        },

    }
</script>