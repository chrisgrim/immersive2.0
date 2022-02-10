<template>
    <div class="p-4 rounded-2xl my-4 relative bg-gray-300">
        <h4 class="mt-8 text-2xl">Curators</h4>
        <div class="overflow-auto flex flex-col pb-8">
            <div class="items-center inline-flex mb-2 h-14">
                <div class="flex w-96 items-center relative mr-2">
                    <p class="text-xl">{{ owner.name }} (Owner)</p>
                </div>
            </div>
            <div 
                v-for="curator in curators"
                :key="curator.id"
                class="items-center inline-flex h-10">
                <div class="flex w-96 items-center relative mr-2">
                    <p class="text-lg mr-2">{{ curator.name }}</p> 
                    <button 
                        v-if="isOwner"
                        @click="updateOwner(curator)"
                        class="border-none underline text-lg">
                        (Make Owner)
                    </button>
                </div>
                <div 
                    v-if="isOwner"
                    class="flex w-40 items-center relative mr-2">
                    <button 
                        @click="removeCurator(curator)"
                        class="border-none underline text-lg">
                        Remove
                    </button>
                </div>
            </div>
        </div>
        <button 
            class="rounded-full py-2 px-4 bg-white hover:bg-black hover:text-white hover:border-black"
            @click="showInviteForm = true">Invite New Curator</button>
        <div 
            class="mt-4" 
            v-if="showInviteForm">
            <div class="field">
                <input 
                    type="text" 
                    v-model="curator"
                    @input="clearErrors"
                    :class="{ 'error': $v.curator.$error }"
                    placeholder="curator email">
                <div v-if="$v.curator.$error" class="validation-error">
                    <p class="error" v-if="!$v.curator.required">Please add a name.</p>
                    <p class="error" v-if="!$v.curator.email">Must be an email</p>
                </div>
            </div>
            <button 
                class="rounded-full py-2 px-4 bg-white hover:bg-black hover:text-white hover:border-black"
                @click="addCurator">
                Submit
            </button>
        </div>
        <div 
            v-if="serverErrors" 
            class="fixed top-8 right-8 p-8 bg-green-400 rounded-2xl z-[2005]">
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
        
        props: [ 'loadcurators', 'loadowner', 'community', 'user' ],

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
                isOwner: this.user.id === this.loadowner.id,
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