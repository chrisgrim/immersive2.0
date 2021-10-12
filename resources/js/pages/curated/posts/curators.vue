<template>
    <div class="curator-table">
        <h4>Curators</h4>
        <div class="table">
            <div class="head row">
                <div class="col c-30"><p>Curator</p></div>
                <div class="col c-10"></div>
            </div>
            <div class="row">
                <div class="col c-30">
                    <p>{{ owner.name }} (Owner)</p>
                </div>
            </div>
            <div 
                v-for="curator in curators"
                :key="curator.id"
                class="row">
                <div class="col c-30">
                    <p>{{ curator.name }}</p> 
                    <button 
                        @click="updateOwner(curator)"
                        class="noBox">
                        (Make Owner)
                    </button>
                </div>
                <div class="col c-10">
                    <button 
                        class="noBox" 
                        @click="removeCurator(curator)">
                        Remove
                    </button>
                </div>
            </div>
        </div>
        <button @click="showInviteForm = true">Invite New Curator</button>
        <div v-if="showInviteForm">
            <div class="field">
                <input 
                    type="text" 
                    v-model="curator"
                    @input="clearErrors"
                    :class="{ 'error': $v.curator.$error }"
                    placeholder="User email">
                <div v-if="$v.curator.$error" class="validation-error">
                    <p class="error" v-if="!$v.curator.required">Please add a name.</p>
                    <p class="error" v-if="!$v.curator.email">Must be an email</p>
                </div>
            </div>
            <button @click="addCurator">
                Submit
            </button>
        </div>
        <div 
            v-if="serverErrors" 
            class="updated-notifcation">
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
    import { required, email } from 'vuelidate/lib/validators';
    export default {
        
        props: [ 'loadcurators', 'loadowner', 'community' ],

        mixins: [formValidationMixin],

        computed: {
        },

        data() {
            return {
                curators: this.loadcurators.filter(u => u.id !== this.loadowner.id),
                serverErrors:null,
                owner: this.loadowner,
                showInviteForm: false,
                inviteSent: false,
                curator: null,
            }
        },

        methods: {
            async addCurator() {
                if ( this.checkVuelidate() ) { return }
                await axios.post(`/communities/${this.community.slug}/curators/add`, {email: this.curator})
                .then( res => {
                    this.showInviteForm = false;
                    this.curators = res.data.curators.filter(u => u.id !== this.owner.id);
                    this.$emit("update", this.owner, this.curators );
                })
                .catch(err => {
                    this.onErrors(err);
                    setTimeout(() => this.serverErrors = null, 10000)
                });
            },
            async updateOwner(curator) {
                await axios.post(`/communities/${this.community.slug}/curators/owner`, curator)
                .then( res => {
                    this.owner = res.data.owner
                    this.curators = res.data.curators.filter(u => u.id !== this.owner.id);
                    this.$emit("update", this.owner, this.curators );
                })
                .catch(err => {
                    this.onErrors(err);
                });
            },
            async removeCurator(curator) {
                await axios.post(`/communities/${this.community.slug}/curators/remove`, curator)
                .then( res => {
                    this.owner = res.data.owner
                    this.curators = res.data.curators.filter(u => u.id !== this.owner.id);
                    this.$emit("update", this.owner, this.curators );
                })
                .catch(err => {
                    this.onErrors(err);
                });
            },
        },

        validations: {
            curator: {
                required,
                email
            },
        },

    }
</script>