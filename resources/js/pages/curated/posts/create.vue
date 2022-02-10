<template>
    <div>
        <div class="mx-auto my-32 w-full md:px-12 md:py-8 lg:py-0 lg:px-32 max-w-screen-xl">
            <div class="flex flex-col md:flex-row">
                <div class="inline-block w-full md:w-4/6">
                    <div class="field h3">
                        <input 
                            type="text" 
                            v-model="post.name"
                            @input="clearError"
                            :class="{ 'error': $v.post.name.$error }"
                            placeholder="Post Name... ie: Top 20 Horror Events">
                        <div v-if="$v.post.name.$error" class="validation-error">
                            <p class="error" v-if="!$v.post.name.required">Please add a name.</p>
                            <p class="error" v-if="!$v.post.name.serverError">Your community already has a post with a similar name</p>
                            <p class="error" v-if="!$v.post.name.maxLength">The name is too long.</p>
                        </div>
                    </div>
                    <div class="field">
                        <input 
                            type="text" 
                            v-model="post.blurb"
                            @input="clearError"
                            :class="{ 'error': $v.post.blurb.$error }"
                            placeholder="Tag Line">
                        <div v-if="$v.post.blurb.$error" class="validation-error">
                            <p class="error" v-if="!$v.post.blurb.required">Please add short tag line.</p>
                            <p class="error" v-if="!$v.post.blurb.maxLength">The tag line is too long.</p>
                        </div>
                    </div>
                    <div class="field">
                        <v-select
                            v-model="post.shelf_id"
                            :reduce="shelf => shelf.id"
                            :options="shelves"
                            :class="{ 'error': $v.post.shelf_id.$error }"
                            placeholder="Shelf"
                            @input="clearError"
                            label="name" />
                        <div v-if="$v.post.shelf_id.$error" class="validation-error">
                            <p class="error" v-if="!$v.post.shelf_id.required">Please select the shelf.</p>
                        </div>
                    </div>
                    <br>
                    <button 
                        class="rounded-full py-2 px-4 bg-white hover:bg-black hover:text-white hover:border-black"
                        @click="submitPost">Submit</button>
                </div>
                <div class="w-full inline-block md:w-2/6 p-8">
                    <div class="sticky top-16">
                        <h3>Create a Post</h3>
                        <ul>
                            <li>Once the post has been created you can edit it at anytime.</li>
                            <li>Posts aren't visible until you make them live.</li>
                        </ul>
                    </div>
                </div>
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
    import formValidationMixin from '../../../mixins/form-validation-mixin'
    import { required, maxLength } from 'vuelidate/lib/validators';
    export default {
        
        props: [ 'community', 'user', 'shelves' ],

        mixins: [formValidationMixin],

        computed: {

        },

        data() {
            return {
                post: this.initializePostObject(),
                checkImage: false,
                serverErrors: null,
            }
        },

        methods: {

            async submitPost() {
                if ( this.checkVuelidate()) { return }
                await axios.post(`/posts/${this.community.slug}/store`, this.post)
                .then( res => {
                    window.location.href = `/communities/${this.community.slug}/${res.data.slug}/edit`
                })
                .catch(err => {
                    this.onErrors(err);
                });
            },
            initializePostObject() {
                return this.post = {
                    name: null,
                    blurb: null,
                    community_id: this.community.id,
                    shelf_id: null,
                }
            },
            clearError() {
                this.$v.post.touch
                this.serverErrors = null
            }
        },

        validations: {
            post: {
                name: {
                    required,
                    maxLength: maxLength(100),
                    serverError() {
                        return this.serverErrors && this.serverErrors.name ? false : true
                    }
                },
                blurb: {
                    required,
                    maxLength: maxLength(100)
                },
                shelf_id: {
                    required,
                }
            },
        },

    }
</script>