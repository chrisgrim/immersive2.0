<template>
<div>
    <div class="create-title">
        <h2>Event Organizer</h2>
        <p>What organizer or production company is putting this immersive show on?</p>
    </div>
    <div class="organization-form">
    </div>
   
    
        
    <div v-show="showFormFields" v-if="this.userOwnsOrganization">
        <div class="image-upload-field create-field">
            <label>Add Organizers Image</label>
            <label
            class="profile-upload-wrapper"
            :style="{ backgroundImage: `url('${organizationImageModel ? organizationImageModel : defaultImage}')` }" >
                            <span class="profile-upload-layover">
                <div class="add-profile-image"><p>+</p></div>
            </span>
                <image-upload @loaded="onImageUpload" />
            </label>
            <input 
            type="hidden" 
            name="organizationImagePath"
            v-model="organizationImageModel"
            @input="$v.organizationImageModel.$touch()"
            />
            <div v-if="$v.organizationImageModel.$error" class="validation-error">
                <p class="error" v-if="!$v.organizationImageModel.required">An image is required</p>
                <p class="error" v-if="!$v.organizationImageModel.fileSize">Your image should be under 2MB</p>
            </div>
        </div>
        <div class="floating-form">
            <div class="create-field">
                <label>Enter Production Company name</label>
                <input 
                type="text" 
                class="create-input" 
                placeholder=" " 
                name="name"
                :class="{ active: nameActive,'error': $v.organizer.name.$error }"
                @input="$v.organizer.name.$touch"
                @click="toggleName()"
                @blur="nameActive = false"
                v-model="organizer.name"
                />
                <div v-if="$v.organizer.name.$error" class="validation-error">
                    <p class="error" v-if="!$v.organizer.name.required">The Name is required</p>
                    <p class="error" v-if="!$v.organizer.name.serverFailed">The Name needs to be unique</p>
                </div>
            </div>
            <div class="create-field">
                <label>Description of Production Company</label>
                <textarea 
                type="text" 
                class="create-input area"  
                name="description" 
                v-model="organizer.description" 
                placeholder=" "
                :class="{ active: descriptionActive,'error': $v.organizer.description.$error }"
                @input="$v.organizer.description.$touch"
                @click="descriptionActive = true"
                @blur="descriptionActive = false" 
                rows="8">
                </textarea>
                <div v-if="$v.organizer.description.$error" class="validation-error">
                    <p class="error" v-if="!$v.organizer.description.required">The Description is required</p>
                </div>
            </div>
            <div class="create-field">

                <label>Production Website</label>
                <input 
                class="create-input" 
                type="url" 
                v-model="organizer.website" 
                name="website"
                :class="{ active: websiteActive,'error': $v.organizer.website.$error }"
                @input="$v.organizer.website.$touch"
                @click="toggleWebsite()"
                @blur="websiteActive = false" 
                placeholder=" "
                />
                <div v-if="$v.organizer.website.$error" class="validation-error">
                    <p class="error" v-if="!$v.organizer.website.url">Must be a Url (Needs http://)</p>
                    <p class="error" v-if="!$v.organizer.website.required">The Website is required</p>
                    <p class="error" v-if="!$v.organizer.website.serverFailed">The Website needs to be unique</p>
                </div>
            </div>
            <div class="create-field">
                <label>Twitter handle (optional)</label>
                <input 
                type="text" 
                class="create-input" 
                v-model="organizer.twitterHandle" 
                name="twitterHandle" 
                placeholder=" "
                />
            </div>
            <div class="create-field">
                <label>Facebook handle (optional)</label>
                <input 
                type="text" 
                class="create-input" 
                v-model="organizer.facebookHandle" 
                name="facebookHandle" 
                placeholder=" "
                />
            </div>
            <div class="create-field">
                <label>Instagram handle (optional)</label>
                <input 
                type="text" 
                class="create-input" 
                v-model="organizer.instagramHandle" 
                name="instagramHandle" 
                placeholder=" "
                />
            </div>
        </div>
    </div>
    <div v-else="this.userOwnsOrganization">
        <img class="" :src="organizationImageModel" alt="">
        <h2>{{organizer.name}}</h2>
        <p>{{organizer.description}}</p>
        <p>{{organizer.website}}</p>
        <p>{{organizer.twitterHandle}}</p>
        <p>{{organizer.facebookHandle}}</p>
        <p>{{organizer.instagramHandle}}</p>

    </div>
    <div>
        <button class="create" @click.prevent="createOrganizer"> Save and Continue </button>
    </div>
</div>

</template>

<script>
import _ from 'lodash'
import ImageUpload from '../layouts/image-upload.vue'
import { required, maxLength, url } from 'vuelidate/lib/validators'

export default {
    components: { 
        ImageUpload,
    },
    
    props: {
        event: { type: Object },
        user: { type: Object }
    },

    computed: {
        slug() {
            return this.slugify(this.organizer.name)
        },
        userOwnsOrganization() {
            return this.organizer && (this.searchModel.user_id == this.user.id) ? true : false;
        },
    },
    
    data() {
        return {
            searchModel: '',
            organizationImageModel: '',
            finalImage: '',
            defaultImage: '/storage/website-files/upload.png',
            showSearchField: _.isEmpty(this.searchOptions) ? false : true,
            showFormFields: false,
            organizer: this.initializeOrganizerObject(),
            searchOptions: [this.initializeOrganizerObject()],
            eventUrl:_.has(this.event, 'slug') ? `/create/${this.event.slug}` : null,
            nameActive: false,
            descriptionActive: false,
            websiteActive: false,
            serverErrors: [],
            isLoading: false,

        };
    },

    methods: {

        //shows the option for new organizer when the dropdown is clicked
        addNewOrganizer() {
            const newOrganizer = this.initializeOrganizerObject();
            newOrganizer.name = 'Create New Organizer';
            newOrganizer.user_id = this.user.id;
            this.searchOptions = [newOrganizer];
        },

        //on page load creates an empty organizer object
        initializeOrganizerObject() {
            return {
                id: '',
                name: '',
                description: '',
                website: '',
                imagePath: '',
                twitterHandle: '',
                facebookHandle: '',
                instagramHandle: '',
                user_id:''
            }
        },
        //displays the fields for the user to edit
        //fills the field forms with data from the selected organizer
         //adds the image
        onOrganizerSelect(organizer) {
            if ((organizer !== null) && (typeof organizer === "object") && (organizer.id !== null)) {
                this.showFormFields = true;
                this.organizer = _.pick(organizer, _.intersection( _.keys(this.organizer), _.keys(organizer) ));
                this.organizationImageModel = this.organizer.imagePath ? `/storage/${this.organizer.imagePath}` : '';
                if(organizer.id === '') { this.organizer.name = '' }
            }
        },

        // makes the name field class active and clears any vuelidate server errors 
        toggleName() {
            this.nameActive = true;
            this.serverErrors = [];
        },

        // makes the organizer website field class active and clears any vuelidate server errors 
        toggleWebsite() {
            this.websiteActive = true;
            this.serverErrors = [];
        },

        //creates a slug that is sent to be checked by database
        slugify (text, ampersand = 'and') {
            const a = 'àáäâèéëêìíïîòóöôùúüûñçßÿỳýœæŕśńṕẃǵǹḿǘẍźḧ'
            const b = 'aaaaeeeeiiiioooouuuuncsyyyoarsnpwgnmuxzh'
            const p = new RegExp(a.split('').join('|'), 'g')

            return text.toString().toLowerCase()
                .replace(/[\s_]+/g, '-')
                .replace(p, c =>
                  b.charAt(a.indexOf(c)))
                .replace(/&/g, `-${ampersand}-`)
                .replace(/[^\w-]+/g, '')
                .replace(/--+/g, '-')
                .replace(/^-+|-+$/g, '')
        },

        //checks to see if passed variable is in the server errors
        hasServerError(field) {
            return (field && _.has(this, 'serverErrors.' + field) && !_.isEmpty(this.serverErrors[field]));
        },

        //this is the ajax search for the organizers name when the user starts entering letters
        //sets the isloading spinning dial to true
        //axios post the users search to find similar organization in database
        //I create a new organizer named Create New organizer and add it to the returned list in case the user wants to make a new organizer
        //turn off spinning wheel
        asyncFind (query) {
            this.isLoading = true
            axios.get('/api/search/organizer', { params: { keywords: query } })
            .then(response => {
                if (query.length) {
                    this.searchOptions = response.data;
                } else {
                    const newOrganizer = this.initializeOrganizerObject();
                    newOrganizer.name = 'Create New Organizer';
                    newOrganizer.user_id = this.user.id;
                    this.searchOptions = _.concat(newOrganizer, response.data);
                };
                this.isLoading = false;
            });
        },

        // adds image to the page so user can see it
        //adds file to organizer object for upl
        async onImageUpload(image) {
            this.organizationImageModel = image.src;
            this.organizer.imagePath = image.file;
            this.finalImage = image.file;
        },

        //submits if is new Organizer
        submitNewOrganizer(params, headers) {
            axios.post(`${this.eventUrl}/organizer`, params, headers)
            .then(response => { 
                window.location.href = `${this.eventUrl}/title`; 
            })
            .catch(error => {         
                this.serverErrors = error.response.data.errors;  
            });
        },

        //submits to edit an old organizer
        submitEditOrganizer(params, headers) {
            axios.post(`${this.eventUrl}/${this.organizer.id}/organizer`, params, headers)
            .then(response => { 
                window.location.href = `${this.eventUrl}/title`; 
            })
            .catch(error => {            
                this.serverErrors = error.response.data.errors;  
            });
        },


        //checks if validation passes
        //creates a new Form data called params
        //creates a headers variable with correct info
        // adds the organizer fields to formdata
        //adds the slug to the form data
        // submits to database. If statement is for new organizer or if updating organizer
        async createOrganizer() {
            this.$v.$touch(); 
            if (this.$v.$invalid) { return false };

            const params = new FormData();
            const headers = {'Content-Type': 'application/x-www-form-urlencoded'};
            for (var field in this.organizer) {
                params.append(field, this.organizer[field]);
            }
            params.append('slug', this.slug);
            this.finalImage ? params.append('newImageUpload', true) : params.append('newImageUpload', false);
            this.organizer.id == '' ? this.submitNewOrganizer(params, headers) : this.submitEditOrganizer(params, headers);
        },
    },
    //if event already has organizer(being updated) this fills in the fields on the page with that organizer
    mounted() {
         if (_.has(this.event, 'organizer.id') && this.event.organizer.id !== null) {
            this.onOrganizerSelect(this.event.organizer);
            this.searchModel = this.event.organizer;
        }
    },

    validations: {
        organizationImageModel: {
            required,
            fileSize() { return this.finalImage ? this.finalImage.size < 2097152 : true },
        },

        organizer: {
            description: {
               required,
               maxLength: maxLength(10000)
            },
            name: {
               required,
               serverFailed : function(){ return !this.hasServerError('name'); },
            },
            website: {
                required,
                url,
                serverFailed : function(){ return !this.hasServerError('website'); },
            },
        },
    },
};
</script>