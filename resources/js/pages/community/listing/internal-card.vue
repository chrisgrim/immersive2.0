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
                    placeholder="Title">
            </div>
            <div>
                <tiptap 
                    @save="saveCard"
                    v-model="card.blurb" />
            </div>
        </template>
    </div>
</template>

<script>
    import Tiptap from '../../../components/Tiptap.vue'
    import CardImage from '../../../components/Upload-Image.vue'
    export default {
        
        props: [ 'listing' ],

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
                this.addCardData();
                await axios.post('/cards', this.formData)
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
                this.formData.append('listing_id', this.card.listing_id);
            },
            debounce(query) {
                if (this.timeout) 
                    clearTimeout(this.timeout); 
                this.timeout = setTimeout(() => {
                    this.generateSearchList(query);
                }, 200); // delay
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
        }

    }
</script>