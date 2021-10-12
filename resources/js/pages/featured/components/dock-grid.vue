<template>
    <div class="data-grid">
        <div class="data-grid__row header">
            <p>Dock Location</p>
            <p>Dock Type</p>
            <p>Dock Name</p>
            <p>Content</p>
            <p>Rank</p>
            <p>Update</p>
        </div>
        <template v-if="docks && docks.length">
            <div 
                class="data-grid__row"
                v-for="(dock, i) in docks"
                :set="v = $v.docks.$each[i]"
                :key="dock.id">
                <div>
                    <select 
                        v-model="dock.location" 
                        placeholder="Location"
                        @change="onUpdate(dock)">
                        <option value="none"> None </option>
                        <option value="home"> Home </option>
                        <option value="search"> Search </option>
                    </select>
                </div>
                <div>
                    <select 
                        v-model="dock.type" 
                        placeholder="Type"
                        @change="onUpdate(dock)">
                        <option value="f"> Four </option>
                        <option value="t"> Three </option>
                        <option value="i"> Icon </option>
                        <option value="h"> Hero </option>
                        <option value="s"> Spotlight </option>
                    </select>
                </div>
                <div class="field">
                    <input 
                        type="text" 
                        v-model="dock.name"
                        :class="{ 'error': v.name.$error }"
                        @blur="onUpdate(dock)"
                        placeholder="Dock Name">
                    <div v-if="v.name.$error" class="validation-error">
                        <p class="error" v-if="!v.name.maxLength">The name is too long.</p>
                    </div>
                </div>
                <div>
                    <template v-if="dock.posts.length">
                        <h4>Post:</h4>
                        <div 
                            :key="`p-${post.id}`"
                            v-for="post in dock.posts">
                            <p> {{ post.name}} </p>
                        </div>
                    </template>
                    <template v-if="dock.shelves.length">
                        <h4>Shelf:</h4>
                        <div 
                            :key="`s-${shelf.id}`"
                            v-for="shelf in dock.shelves">
                            <p> {{ shelf.name}} </p>
                        </div>
                    </template>
                    <template v-if="dock.communities.length">
                        <h4>Community:</h4>
                        <div 
                            :key="`c-${community.id}`"
                            v-for="community in dock.communities">
                            <p> {{ community.name}} </p>
                        </div>
                    </template>
                </div>
                <div>
                    <div class="field">
                        <select 
                            v-model="dock.order"
                            @change="onUpdate(dock)"
                            placeholder="Leave blank for default Rank of 5 (1 being most important)">
                            <option 
                                v-for="pick in ['0', '1', '2', '3', '4', '5', '6', '7', '8', '9']"
                                :key="pick">
                                {{ pick }}
                            </option>
                        </select>
                    </div>
                </div>
                <div>
                    <button @click="modalAdd=dock">
                        Add Content
                    </button>
                </div>
                <button 
                    @click.prevent="modalDelete=dock" 
                    class="delete">
                    <svg>
                        <use :xlink:href="`/storage/website-files/icons.svg#ri-close-line`" />
                    </svg>
                </button>
            </div>
        </template>
        <AddModal
            :dock="modalAdd"
            @update="onRefresh"
            @close="modalAdd=null"
            v-if="modalAdd" />
        <VueDeleteModal 
            v-if="modalDelete"
            :item="modalDelete"
            :strict="true"
            body="You are deleting the Dock. Please be sure you know what you are doing."
            @close="modalDelete=null"
            @ondelete="onDelete" />
    </div>
</template>

<script>
    import { maxLength } from 'vuelidate/lib/validators';
    import formValidationMixin from '../../../mixins/form-validation-mixin'
    import VueDeleteModal from '../../../components/modals/Vue-Modal-Delete'
    import AddModal from './add-modal'

    export default {
        props: ['docks'],

        mixins: [formValidationMixin],

        components: { VueDeleteModal, AddModal },

        data() {
            return {
                feature: null,
                options: [],
                modalDelete: null,
                modalAdd: false,
            }
        },

        methods: {
            async onUpdate(dock) {
                if ( this.checkVuelidate()) { return }
                await axios.post(`/admin/docks/${dock.id}`, dock)
                .then( res => {
                    this.$emit('update', res.data);
                })
            },
            async onDelete() {
                await axios.delete(`/admin/docks/${this.modalDelete.id}`)
                .then( res => {
                    this.$emit('update', res.data);
                    this.modalDelete = null
                })
            },
            onRefresh(val) {
                this.$emit('update', val);
                this.modalAdd = null
            },
        },

        validations: {
            docks: {
                $each: {
                    name: { 
                        maxLength: maxLength(100), 
                    },
                }
            }
        },
    }

</script>