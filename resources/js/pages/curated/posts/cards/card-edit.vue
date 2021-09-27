<template>
    <div>
        <div 
            :class="{ hover: hover && !onEdit}"
            @mouseover="hover = true"
            @mouseleave="hover = false"
            class="block">
            <template v-if="hasImage || onEdit && card.url">
                <CardImage
                    :image="`/storage/${image}`"
                    :can-delete="card.type==='e'"
                    @onDelete="hideImage"
                    @addImage="addImageSubmit" />
            </template>

            <template v-if="hasName || cardBeforeEdit.name">
                <template v-if="onEdit">
                    <div class="field h3">
                        <input 
                            type="text" 
                            v-model="card.name"
                            :class="{ 'error': $v.card.name.$error }"
                            :placeholder="hasName">
                        <div v-if="$v.card.name.$error" class="validation-error">
                            <p class="error" v-if="!$v.card.name.maxLength">The name is too long.</p>
                        </div>
                    </div>
                </template>
                <template v-else>
                    <div 
                        @click="onEdit=true"
                        class="card-name">
                        <h2>{{ hasName }}</h2>
                    </div>
                </template>
            </template>

            <template v-if="hasUrl || cardBeforeEdit.url">
                <template v-if="onEdit">
                    <div class="field h3">
                        <input 
                            type="text" 
                            v-model="card.url"
                            :class="{ 'error': $v.card.url.$error }"
                            :placeholder="hasUrl">
                        <div v-if="$v.card.url.$error" class="validation-error">
                            <p class="error" v-if="!$v.card.url.maxLength">The url is too long.</p>
                        </div>
                    </div>
                </template>
            </template>

            <template v-if="card.event_id">
                <div 
                    style="display:flex;" 
                    @click="onEdit=true">
                    <p>Closing Date: {{ cleanDate(card.event.closingDate) }}</p>
                    <template v-if="onEdit">
                        <a target="_blank" :href="`/create/${card.event.slug}/shows`"><button>Edit dates</button></a>
                        <a target="_blank" :href="`${card.event.ticketUrl}`"><button>Check Event</button></a>
                    </template>
                </div>
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
                        <p v-html="card.blurb" />
                    </div>
                </template>
            </template>

            <template v-if="hover && !onEdit">
                <div class="delete">
                    <button 
                        @click="deleteCard"
                        class="btn-icon">
                        <svg>
                            <use :xlink:href="`/storage/website-files/icons.svg#ri-close-line`" />
                        </svg>
                    </button>
                </div>
            </template>
        </div>
        <transition name="slide-fade">
            <div 
                v-if="updated" 
                class="updated-notifcation">
                <p>Your Post has been updated.</p>
            </div>
        </transition>
    </div>
</template>

<script>
    import Tiptap from '../../../../components/Tiptap.vue'
    import CardImage from '../../../../components/Upload-Image.vue'
    import formValidationMixin from '../../../../mixins/form-validation-mixin'
    import { maxLength } from 'vuelidate/lib/validators';
    export default {
        
        props: [ 'parentCard', 'owner' ],

        mixins: [formValidationMixin],

        components: { Tiptap, CardImage },

        computed: {
            hasImage() {
                return this.card.type === 'i' || this.card.type === 'e' || this.card.type === 'h'
            },
            image() {
                if (this.card.type === 'i') { return this.card.thumbImagePath }
                if (this.card.type === 'h' || this.card.type === 't') { return }
                return this.card.type === 'e' && !this.card.thumbImagePath ? this.card.event.thumbImagePath : this.card.thumbImagePath
            },
            hasName() {
                return this.card.event && !this.card.name ? this.card.event.name : this.card.name
            },
            hasUrl() {
                return this.card.event && !this.card.url ?`/events/${this.card.event.slug}` : this.card.url
            }
        },

        data() {
            return {
                card: this.parentCard,
                cardBeforeEdit: { ...this.parentCard },
                onEdit: false,
                formData: new FormData(),
                hover: false,
                updated: false,
            }
        },

        methods: {
            async updateCard() {
                if ( this.checkVuelidate()) { return }
                this.appendCardData();
                await axios.post(`/cards/${this.card.id}`, this.formData)
                .then( res => { 
                    this.card = res.data;
                    this.cardBeforeEdit = res.data ;
                })
                this.onUpdated();
                this.clear();
            },
            async resetCard() {
                this.card = { ...this.cardBeforeEdit }
                this.clear();
            },
            async deleteCard() {
                await axios.delete(`/cards/${this.card.id}`)
                .then( res => { 
                    console.log(res.data);
                    this.$emit('update', res.data)
                })
            },
            hideImage() {
                if (this.card.type === 'e') {
                    this.formData.append('type', 'h')
                    this.updateCard()
                }
            },
            appendCardData() {
                this.formData.append('_method', 'PUT');
                this.card.name ? this.formData.append('name', this.card.name) : null;
                this.card.url ? this.formData.append('url', this.card.url) : null;
                this.card.blurb ? this.formData.append('blurb', this.card.blurb) : null
            },
            addImageSubmit(image, src) {
                this.formData.append('image', image);
                this.card.type === 'h' ? this.formData.append('type', 'e') : null
                this.updateCard();
            },
            clear() {
                this.onEdit = false;
                this.hover = false
            },
            onUpdated() {
                this.$v.$reset();
                this.updated = true;
                setTimeout(() => this.updated = false, 3000);
            },
            cleanDate(data) {
                return this.$dayjs(data).format("dddd, MMMM D YYYY");
            }
        },

        validations: {
            card: {
                name: {
                    maxLength: maxLength(50),
                },
                blurb: {
                    maxLength: maxLength(50000)
                },
                url: {
                    maxLength: maxLength(100),
                },
            },
        },
    }
</script>