<template>
    <div class="community-show">
        <div class="com-header">
            <div class="com-image">
                <template v-if="onEdit">
                    <CardImage
                        :image="`/storage/${headerImage}`"
                        @addImage="addImage" />
                </template>
                <picture>
                    <source 
                        type="image/webp" 
                        :srcset="`/storage/${headerImage}`"> 
                    <img 
                        :src="`/storage/${headerImage.slice(0, -4)}`" 
                        :alt="`${community.name} Community`">
                </picture>
            </div>
        </div>
        <div class="com-intro">
            <div class="com-content">
                <template v-if="onEdit">
                    <div class="field h2">
                        <input 
                            type="text" 
                            v-model="community.name"
                            placeholder="Listing Name">
                    </div>
                    <div class="com-blurb blurb">
                        <tiptap 
                            @cancel="resetCommunity"
                            @save="updateCommunity"
                            v-model="community.blurb" />
                    </div>
                </template>
                <template v-else>
                    <div class="com-name">
                        <h2>{{ community.name }}</h2>
                    </div>
                    <div class="com-blurb">
                        <div v-html="community.blurb" />
                        <template v-if="owner">
                            <button
                                class="edit"
                                @click="onEdit=true">Edit</button>
                        </template>
                    </div>
                </template>
            </div>
            <div class="com-curators">
                <h4>Meet your curators</h4>
                <div 
                    v-for="curator in community.curators"
                    :key="curator.id"
                    class="curator">
                    <template v-if="curator && curator.thumbImagePath">
                        <picture>
                            <source 
                                type="image/webp" 
                                :srcset="`/storage/${curator.thumbImagePath}`"> 
                            <img 
                                :src="`/storage/${curator.thumbImagePath.slice(0, -4)}`" 
                                :alt="`${curator.name} Community`">
                        </picture>
                    </template>
                    {{ curator.name}}
                </div>
            </div>
        </div>
        <div class="add-listing">
            <div 
                v-if="owner"
                class="add-new-listing-card">
                <button 
                    @click="showAddButtonOptions"
                    class="main">
                    add new 
                    <svg class="remix">
                        <use v-if="!buttonOptions" :xlink:href="`/storage/website-files/icons.svg#ri-add-circle-line`" />
                        <use v-else :xlink:href="`/storage/website-files/icons.svg#ri-close-circle-line`" />
                    </svg>
                </button>
                <template v-if="buttonOptions">
                    <a :href="`/create/${community.slug}/listing`">
                        <button class="sub" @click="selectButton('i')">
                            Curated Collection
                        </button>
                    </a>
                </template>
            </div>
        </div>
        <div class="listings">
            <template v-if="listings && listings.length">
                <VueCards
                    :community="community"
                    :cards="listings"/>
            </template>
        </div>
    </div>
</template>

<script>
    import VueCards  from './card-index.vue'
    import Tiptap from '../../components/Tiptap.vue'
    import CardImage from '../../components/Upload-Image.vue'
    import formValidationMixin from '../../mixins/form-validation-mixin'
    import { required, maxLength } from 'vuelidate/lib/validators';
    export default {
        
        props: [ 'value', 'user', 'owner', 'listings' ],

        mixins: [formValidationMixin],

        components: { VueCards, Tiptap, CardImage },

        computed: {

        },

        data() {
            return {
                community: this.value,
                headerImage: window.innerWidth < 768 ? this.value.thumbImagePath : this.value.largeImagePath,
                content: `<h2>${this.value.name} community! 🎉</h2><p>Go ahead and edit this text to say whatever you want!</p>`,
                onEdit: false,
                formData: new FormData(),
                buttonOptions: null,
            }
        },

        methods: {
            async updateCommunity() {
                this.addListingData();
                await axios.post(`/communities/${this.community.slug}`, this.formData)
                .then( res => {
                    this.community = res.data
                })
                this.onEdit = false;
            },
            async resetCommunity() {
                await axios.get(`/communities/${this.community.slug}/fetch`)
                .then( res => { this.community = res.data });
                this.onEdit = false;
            },
            showAddButtonOptions() {
                this.buttonOptions =! this.buttonOptions;
            },
            addImage(image) {
                this.formData.append('image', image);
            },
            addListingData() {
                this.formData.append('_method', 'PUT');
                this.formData.append('name', this.community.name);
                this.formData.append('blurb', this.community.blurb);
            },
        },

        validations: {
            community: {
                name: {
                    required,
                    maxLength: maxLength(100),
                },
                blurb: {
                    required,
                    maxLength: maxLength(100)
                },
            },
        },

    }
</script>