<template>
    <div class="listing-card edit">
        <div class="header">
            <CardImage
                :image="`/storage/${card.thumbImagePath}`"
                @addImage="addImage" />
            <div class="div">
                <div class="field">
                    <v-select
                        v-model="searchInput"
                        :options="searchOptions"
                        placeholder="Event Name"
                        label="name"
                        taggable
                        @search="debounce" 
                        @input="selectEvent"
                        @search:focus="debounce" />
                </div>
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
                <div class="field">
                    <input 
                        type="text" 
                        v-model="card.url"
                        placeholder="Url (Optional)">
                </div>
            </div>
        </div>
        <tiptap 
            @cancel="cancelCard"
            @save="saveCard"
            :disabled="disabled"
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
    import CardImage from '../../../../components/Upload-Image.vue'
    import formValidationMixin from '../../../../mixins/form-validation-mixin'
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
                searchInput: null,
                searchOptions: [],
                disabled: false,
            }
        },

        methods: {
            async saveCard() {
                if ( this.checkVuelidate()) { return }
                this.addCardData();
                await axios.post(`/cards/${this.listing.slug}/create`, this.formData)
                .then( res => {
                    this.$emit('update', res.data)
                    this.disabled = false;
                })
            },
            cancelCard() {
                this.$emit('cancel')
            },
            addImage(image) {
                this.formData.append('image', image);
            },
            addCardData() {
                this.formData.append('blurb', this.card.blurb);
                this.formData.append('url', this.card.url);
                this.formData.append('name', this.card.name);
                this.card.event_id ? this.formData.append('event_id', this.card.event_id) : ''
                this.card.thumbImagePath ? this.formData.append('thumbImagePath', this.card.thumbImagePath) : null;
            },
            initializeCardObject() {
                return {
                    blurb: null,
                    thumbImagePath: null,
                    listing_id: this.listing.id,
                    event_id: null,
                }
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
                this.card.event_id = this.searchInput.id
                this.card.blurb = this.searchInput.tag_line
                this.card.url = `/events/${this.searchInput.slug}`
                this.card.thumbImagePath = this.searchInput.largeImagePath
            },
        },

        validations: {
            card: {
                name: {
                    maxLength: maxLength(255),
                },
                blurb: {
                    required,
                    maxLength: maxLength(40000)
                },
            },
        },

    }
</script>