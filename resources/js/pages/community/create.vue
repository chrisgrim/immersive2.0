<template>
    <div class="community-create">
        <div class="image">
            <CardImage
                :validate="checkImage"
                @addImage="addImage" />
        </div>
        <div class="community-content">
            <div class="field h3">
                <input 
                    type="text" 
                    v-model="community.name"
                    :class="{ 'error': $v.community.name.$error }"
                    placeholder="Community Names">
                <div v-if="$v.community.name.$error" class="validation-error">
                    <p class="error" v-if="!$v.community.name.required">Please add a name.</p>
                    <p class="error" v-if="!$v.community.name.maxLength">The name is too long.</p>
                </div>
            </div>
            <div class="community-blurb">    
                <tiptap 
                    @cancel="onEdit=false"
                    @save="submitCommunity"
                    :class="{ 'error': $v.community.blurb.$error }"
                    v-model="community.blurb" />
                <div v-if="$v.community.blurb.$error" class="validation-error">
                    <p class="error" v-if="!$v.community.blurb.required">Please add a description.</p>
                    <p class="error" v-if="!$v.community.blurb.maxLength">The description is too long.</p>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
    import Tiptap from '../../components/Tiptap.vue'
    import CardImage from '../../components/Upload-Image.vue'
    import formValidationMixin from '../../mixins/form-validation-mixin'
    import { required, maxLength } from 'vuelidate/lib/validators';
    export default {
        
        props: [ 'user', ],

        mixins: [formValidationMixin],

        components: { CardImage, Tiptap },

        computed: {

        },

        data() {
            return {
                community: this.initializeCommunityObject(),
                formData: new FormData(),
                checkImage: false,
            }
        },

        methods: {
            async submitCommunity() {
                if ( this.checkVuelidate() || this.checkForImage()) { return }
                this.addCommunityData();
                await axios.post(`/communities`, this.formData)
                .then( res => {
                    window.location.href = `/communities/${res.data.slug}`
                })
            },

            initializeCommunityObject() {
                return this.community = {
                    name: null,
                    blurb: null,
                }
            },

            addImage(image) {
                this.formData.append('image', image);
            },

            checkForImage() {
                if (!this.formData.has('image')) {
                    this.checkImage = true;
                    return true
                }
            },

            addCommunityData() {
                this.formData.append('name', this.community.name);
                this.formData.append('blurb', this.community.blurb);
            },
        },

        validations: {
            community: {
                name: {
                    required,
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