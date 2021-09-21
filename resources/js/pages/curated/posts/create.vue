<template>
    <div>
        <div class="post-create lay-a">
            <div class="wrapper">
                <div class="content">
                    <div class="field h3">
                        <input 
                            type="text" 
                            v-model="post.name"
                            input="clearError"
                            :class="{ 'error': $v.post.name.$error }"
                            placeholder="Post Name... ie: Top 20 Horror Events">
                        <div v-if="$v.post.name.$error" class="validation-error">
                            <p class="error" v-if="!$v.post.name.required">Please add a name.</p>
                            <p class="error" v-if="!$v.post.name.maxLength">The name is too long.</p>
                        </div>
                    </div>
                    <div class="field">
                        <input 
                            type="text" 
                            v-model="post.blurb"
                            input="clearError"
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
                            label="name" />
                        <div v-if="$v.post.shelf_id.$error" class="validation-error">
                            <p class="error" v-if="!$v.post.shelf_id.required">Please select the shelf.</p>
                        </div>
                    </div>
                    <br>
                    <button @click="submitPost">Submit</button>
                </div>
                <div class="sidebar">
                    <div class="sticky">
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
        },

        validations: {
            post: {
                name: {
                    required,
                    maxLength: maxLength(100)
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