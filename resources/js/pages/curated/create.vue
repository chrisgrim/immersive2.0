<template>
    <div class="com-create">
        <div class="header-a">
            <div class="header-a__content">
                <div class="header-a__wrapper">
                    <div class="field h3">
                        <input 
                            type="text" 
                            v-model="community.name"
                            @input="$v.community.name.$touch"
                            :class="{ 'error': $v.community.name.$error }"
                            placeholder="Please enter community name">
                        <div v-if="$v.community.name.$error" class="validation-error">
                            <p class="error" v-if="!$v.community.name.required">Please add a name.</p>
                            <p class="error" v-if="!$v.community.name.maxLength">The name is too long.</p>
                        </div>
                    </div>
                    <div class="field">
                        <textarea 
                            type="text"
                            name="description" 
                            @input="$v.community.blurb.$touch;"
                            v-model="community.blurb" 
                            placeholder="Please write a tweets worth of text summarizing what people can find in this community. Examples: New to VR? Unsure of what to play or experience next? Check these great options out., While I love Sleep No More, when I’m in NYC, these are the companies and shows I’m checking out."
                            :class="{ 'error': $v.community.blurb.$error }"
                            rows="6" />
                        <div v-if="$v.community.blurb.$error" class="validation-error">
                            <p class="error" v-if="!$v.community.blurb.required">Must provide a short description</p>
                            <p class="error" v-if="!$v.community.blurb.maxLength">Description is too long</p>
                        </div>
                    </div>
                    <div 
                        v-if="$v.$anyDirty"
                        class="buttons">
                        <button @click="submitCommunity">
                            Submit
                        </button>
                    </div>
                </div>
            </div>
            <div class="header-a__image">
                <CardImage
                    :height="500"
                    :width="800"
                    :external-submit="checkImage"
                    @addImage="addImage" />
            </div>
        </div>
        <div class="guide">
            <h3>Create a Community</h3>
            <ul>
                <li>Community info here blah blah</li>
                <li>Community image size is at least 800px wide by 500px tall</li>
            </ul>
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
            clearErrors() {
                this.$v.community.name.$touch();
                this.$v.community.blurb.$touch();
            }
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