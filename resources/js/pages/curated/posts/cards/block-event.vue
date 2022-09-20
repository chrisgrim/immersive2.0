<template>
    <div class="mt-8 relative p-4">
        <div class="flex gap-8">
            <CardImage
                :image="thumbImagePath"
                :loading="disabled"
                @addImage="addImage" />
            <div class="w-full">
                <div class="field mb-4">
                    <v-select
                        v-model="searchInput"
                        :options="searchOptions"
                        :get-option-label="searchInput => searchInput.model.name"
                        :reduce="searchInput => searchInput.model"
                        placeholder="Select event"
                        label="name"
                        @search="debounce" 
                        @input="selectEvent"
                        @search:focus="debounce">
                        <template #option="{ model }">
                            <div class="flex p-3">
                                <svg class="w-8 h-8 mr-4">
                                    <use :xlink:href="`/storage/website-files/icons.svg#ri-map-pin-line`" />
                                </svg>
                                <p>{{ model.name }}</p>
                            </div>
                        </template>
                    </v-select>
                </div>
                <div class="field">
                    <input 
                        type="text" 
                        v-model="card.name"
                        :class="{ 'error': $v.card.name.$error }"
                        :placeholder="name">
                    <div v-if="$v.card.name.$error" class="validation-error">
                        <p class="error" v-if="!$v.card.name.maxLength">The name is too long.</p>
                    </div>
                </div>
                <div class="field">
                    <input 
                        type="text" 
                        v-model="card.url"
                        :placeholder="url">
                </div>
                <div 
                    class="mb-4" 
                    v-if="searchInput">
                    <p> Booking Through: {{ cleanDate(searchInput.closingDate) }} </p>
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
    import Tiptap from './Components/Tiptap.vue'
    import CardImage from './Components/vue-add-image.vue'
    import formValidationMixin from '../../../../mixins/form-validation-mixin'
    import { required, maxLength, url } from 'vuelidate/lib/validators';
    export default {
        
        props: [ 'post' ],

        mixins: [formValidationMixin],

        components: { Tiptap, CardImage },

        computed: {
            url() {
                return this.searchInput ? `/events/${this.searchInput.slug}` : `Url (Optional)`
            },
            name() {
                return this.searchInput ? this.searchInput.name : `Event name`
            },
            thumbImagePath() {
                return this.searchInput ? `${this.envImageUrl}${this.searchInput.thumbImagePath}` : null
            }

        },

        data() {
            return {
                card: this.initializeCardObject(),
                formData: new FormData(),
                searchInput: null,
                searchOptions: [],
                disabled: false,
                envImageUrl: process.env.MIX_IMAGE_URL,
            }
        },

        methods: {
            async saveCard() {
                if ( this.checkVuelidate()) { return }
                this.addCardData();
                await axios.post(`/cards/${this.post.slug}/create`, this.formData)
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
                this.card.url ? this.formData.append('url', this.card.url) : null;
                this.card.name ? this.formData.append('name', this.card.name) : null;
                this.card.type ? this.formData.append('type', this.card.type) : null;
                this.card.event_id ? this.formData.append('event_id', this.card.event_id) : ''
                this.card.thumbImagePath ? this.formData.append('thumbImagePath', this.card.thumbImagePath) : null;
            },
            initializeCardObject() {
                return {
                    blurb: null,
                    thumbImagePath: null,
                    post_id: this.post.id,
                    event_id: null,
                    url: null,
                    name: null,
                    type: 'e'
                }
            },
            debounce(query) {
                if (this.timeout) 
                    clearTimeout(this.timeout); 
                this.timeout = setTimeout(() => {
                    this.generateSearchList(query);
                }, 300); // delay
            },
            async generateSearchList (query) {
                await axios.get('/api/search/navbar/events', { params: { keywords: query } })
                .then( res => {
                    this.searchOptions = res.data;
                })
            },
            selectEvent() {
                this.card.event_id = this.searchInput.id
                this.card.blurb = this.searchInput.tag_line
            },
            cleanDate(data) {
                return this.$dayjs(data).format("dddd, MMMM D YYYY");
            }
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