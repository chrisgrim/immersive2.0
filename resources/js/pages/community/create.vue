<template>
    <div class="community-create">
        <div class="image">
            <CardImage
                :height="800"
                :width="1500"
                :validate="checkImage"
                @addImage="addImage" />
        </div>
        <div class="community-content">
            <div class="field h3">
                <input 
                    type="text" 
                    v-model="community.name"
                    @input="clearErrors"
                    :class="{ 'error': $v.community.name.$error }"
                    placeholder="Community Names">
                <div v-if="$v.community.name.$error" class="validation-error">
                    <p class="error" v-if="!$v.community.name.required">Please add a name.</p>
                    <p class="error" v-if="!$v.community.name.maxLength">The name is too long.</p>
                </div>
            </div>
            <div class="field">
                <textarea 
                    type="text"
                    name="description" 
                    v-model="community.blurb" 
                    placeholder="Community description."
                    :class="{ 'error': $v.community.blurb.$error }"
                    @input="$v.community.blurb.$touch"
                    rows="14" />
                <div v-if="$v.community.blurb.$error" class="validation-error">
                    <p class="error" v-if="!$v.community.blurb.required">Must provide a short description</p>
                    <p class="error" v-if="!$v.community.blurb.maxLength">Description is too long</p>
                </div>
            </div>
            <div class="buttons">
                <button 
                    :disabled="disabled"
                    @click="submitCommunity">Submit</button>
            </div>
        </div>
        <div v-if="serverErrors" class="updated-notifcation">
            <transition-group name="slide-fade">
                <ul 
                    v-for="(error, index) in serverErrors"
                    :key="`name${index}`">
                    <li>
                        <p> {{ error.toString() }}</p>
                    </li>
                </ul>
            </transition-group>
        </div>
    </div>
</template>

<script>
    import CardImage from '../../components/Upload-Image.vue'
    import formValidationMixin from '../../mixins/form-validation-mixin'
    import { required, maxLength } from 'vuelidate/lib/validators';
    export default {
        
        props: [ 'user', ],

        mixins: [formValidationMixin],

        components: { CardImage },

        computed: {

        },

        data() {
            return {
                community: this.initializeCommunityObject(),
                formData: new FormData(),
                checkImage: false,
                serverErrors: null,
                disabled: false,
            }
        },

        methods: {
            async submitCommunity() {
                if ( this.checkVuelidate() || this.checkForImage()) { return }
                this.addCommunityData();
                await axios.post(`/communities`, this.formData)
                .then( res => {
                    window.location.href = `/create/communities/thanks`
                })
                .catch(err => {
                    this.onErrors(err);
                });
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
                    maxLength: maxLength(60),
                },
                blurb: {
                    required,
                    maxLength: maxLength(500)
                },
            },
        },

    }
</script>