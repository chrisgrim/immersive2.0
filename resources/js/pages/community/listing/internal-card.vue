<template>
    <div class="internal">
        <v-select
            v-model="searchInput"
            :options="searchOptions"
            placeholder="Select Event"
            label="name"
            :filterable="false"
            @search="debounce" 
            @input="selectEvent"
            @search:focus="debounce" />
        <template v-if="searchInput">
            <CardImage
                :image="`/storage/${card.thumbImagePath}`"
                @addImage="addImage" />
            <div class="field h3">
                <input 
                    type="text" 
                    v-model="card.name"
                    :class="{ 'error': $v.card.name.$error }"
                    placeholder="Title">
                <div v-if="$v.card.name.$error" class="validation-error">
                    <p class="error" v-if="!$v.card.name.maxLength">The name is too long.</p>
                </div>
            </div>
            <div>
                <tiptap 
                    @save="saveCard"
                    v-model="card.blurb" />
                <div v-if="$v.card.blurb.$error" class="validation-error">
                    <p class="error" v-if="!$v.card.blurb.required">Please add a description.</p>
                    <p class="error" v-if="!$v.card.blurb.maxLength">The description is too long.</p>
                </div>
            </div>
        </template>
    </div>
</template>

<script>
    import Tiptap from '../../../components/Tiptap.vue'
    import CardImage from '../../../components/Upload-Image.vue'
    import formValidationMixin from '../../../mixins/form-validation-mixin'
    import { required, maxLength } from 'vuelidate/lib/validators';
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
                searchInput: null,
                searchOptions: [],

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
                this.formData.append('name', this.card.name);
                this.formData.append('blurb', this.card.blurb);
                this.formData.append('thumbImagePath', this.card.thumbImagePath);
                this.formData.append('url', this.card.url);
            },
            debounce(query) {
                if (this.timeout) 
                    clearTimeout(this.timeout); 
                this.timeout = setTimeout(() => {
                    this.generateSearchList(query);
                }, 500); // delay
            },
            async generateSearchList (query) {
                await axios.get('/api/search/events', { params: { keywords: query } })
                .then( res => {
                    this.searchOptions = res.data.data;
                })
            },
            selectEvent() {
                this.card.name = this.searchInput.name
                this.card.blurb = this.searchInput.tag_line
                this.card.url = `/events/${this.searchInput.slug}`
                this.card.thumbImagePath = this.searchInput.largeImagePath
            },
            initializeCardObject() {
                return this.card = {
                    name: null,
                    blurb: null,
                    url: null,
                    thumbImagePath: null,
                    listing_id: this.listing.id,
                }
            }
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