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
                            placeholder="Who is this community for?"
                            :class="{ 'error': $v.community.blurb.$error }"
                            rows="2" />
                        <div v-if="$v.community.blurb.$error" class="validation-error">
                            <p class="error" v-if="!$v.community.blurb.required">Must provide a short description</p>
                            <p class="error" v-if="!$v.community.blurb.maxLength">Description is too long</p>
                        </div>
                    </div>
                    <div class="field">
                        <textarea 
                            type="text"
                            name="description" 
                            @input="$v.community.description.$touch;"
                            v-model="community.description" 
                            placeholder="Short community description (optional)"
                            :class="{ 'error': $v.community.description.$error }"
                            rows="6" />
                        <div v-if="$v.community.description.$error" class="validation-error">
                            <p class="error" v-if="!$v.community.description.maxLength">Description is too long</p>
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
                <li>Start with entering the title of what this Community will be called. It should be something clear, conscience, and short yet broad in topic and scope. Examples: “Staff Picks” or “Now Playing: Remote”.</li>
                <li>Next, in the larger textbox, provide details as to what content this Community will be showcasing. Ideally, it’ll be a tweet sized summary of this Community’s listings. Examples: “Curious to learn what the NoPro staff currently loves? Staff Picks will showcase what we can’t get enough of and highly recommend” and “A listing of all currently playing remote based experience, ranging from livestreams, team games, and podplays”.</li>
                <li>Finally, in the gray rectangle on the right, click the icon to upload an image. It must be at least 800px wide by 500px tall. In other words, it needs to be rectangular in shape, being twice as wide as it is tall.</li>
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
                    description: null,
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
                this.formData.append('description', this.community.description);
            },
            clearErrors() {
                this.$v.community.name.$touch();
                this.$v.community.blurb.$touch();
                this.$v.community.description.$touch();
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
                    maxLength: maxLength(254)
                },
                description: {
                    maxLength: maxLength(5000)
                },
            },
        },

    }
</script>