<template>
    <div v-if="docks && docks.length">
        <div class="not-prose relative bg-slate-50 rounded-xl overflow-hidden dark:bg-slate-800/25">
            <div class="relative rounded-xl overflow-auto border">
                <div class="shadow-sm overflow-hidden my-4">
                    <table class="table-auto border-collapse w-full">
                        <thead>
                            <tr>
                                <th 
                                    :key="col.id"
                                    v-for="col in cols"
                                    class="border-b dark:border-slate-600 font-medium p-4 pt-0 pb-3 text-slate-400 dark:text-slate-200 text-left">
                                    <p 
                                        class="text-xl" 
                                        :class="col.class">{{ col.field }}</p>
                                </th>
                            </tr>
                        </thead>
                        <tbody class="bg-white dark:bg-slate-800">
                            <tr
                                v-for="(dock, i) in docks"
                                :set="v = $v.docks.$each[i]"
                                :key="dock.id">
                                <td class="border-b border-slate-100 dark:border-slate-700 p-4 pl-8 text-slate-500 dark:text-slate-400">
                                    <select 
                                        class="text-xl px-4 py-2 rounded-xl hover:bg-slate-500 hover:text-white" 
                                        v-model="dock.location" 
                                        placeholder="Location"
                                        @change="onUpdate(dock)">
                                        <option value="none"> None </option>
                                        <option value="home"> Home </option>
                                        <option value="search"> Search </option>
                                    </select>
                                </td>
                                <td class="border-b border-slate-100 dark:border-slate-700 p-4 pl-8 text-slate-500 dark:text-slate-400">
                                    <select 
                                        class="text-xl px-4 py-2 rounded-xl hover:bg-slate-500 hover:text-white"
                                        v-model="dock.type" 
                                        placeholder="Type"
                                        @change="onUpdate(dock)">
                                        <option value="f"> Four </option>
                                        <option value="t"> Three </option>
                                        <option value="i"> Icon </option>
                                        <option value="h"> Hero </option>
                                        <option value="s"> Spotlight </option>
                                    </select>
                                </td>
                                <td class="border-b border-slate-100 dark:border-slate-700 p-4 pl-8 text-slate-500 dark:text-slate-400">
                                    <input 
                                        type="text" 
                                        v-model="dock.name"
                                        :class="{ 'error': v.name.$error }"
                                        class="text-xl px-4 py-2 rounded-xl hover:bg-slate-500 hover:text-white" 
                                        @blur="onUpdate(dock)"
                                        placeholder="Dock Name">
                                </td>
                                <td class="border-b border-slate-100 dark:border-slate-700 p-4 pl-8 text-slate-500 dark:text-slate-400">
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
                                </td>
                                <td class="border-b border-slate-100 dark:border-slate-700 p-4 pl-8 text-slate-500 dark:text-slate-400">
                                    <select 
                                        class="text-xl px-4 py-2 rounded-xl hover:bg-slate-500 hover:text-white"
                                        v-model="dock.order"
                                        @change="onUpdate(dock)"
                                        placeholder="Leave blank for default Rank of 5 (1 being most important)">
                                        <option 
                                            v-for="pick in ['0', '1', '2', '3', '4', '5', '6', '7', '8', '9']"
                                            :key="pick">
                                            {{ pick }}
                                        </option>
                                    </select>
                                </td>
                                <td class="border-b border-slate-100 dark:border-slate-700 p-4 pl-8 text-slate-500 dark:text-slate-400">
                                    <button 
                                        @click="modalAdd=dock"> 
                                        Add Content
                                    </button>
                                </td>
                                <td class="border-b border-slate-100 dark:border-slate-700 p-4 pl-8 text-slate-500 dark:text-slate-400">
                                    <button 
                                        @click="modalAdd=dock"> 
                                        Add Content
                                    </button>
                                </td>
                                <td class="border-b border-slate-100 dark:border-slate-700 p-4 pl-8 text-slate-500 dark:text-slate-400">
                                    <button 
                                        @click.prevent="modalDelete=dock" 
                                        class="rounded-full p-2 hover:bg-black hover:border-black">
                                        <svg class="w-8 h-8 hover:fill-white">
                                            <use :xlink:href="`/storage/website-files/icons.svg#ri-close-line`" />
                                        </svg>
                                    </button>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
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
    import formValidationMixin from '../../../../mixins/form-validation-mixin'
    import VueDeleteModal from '../../../../components/modals/Vue-Modal-Delete'
    import AddModal from './dock-modal'

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
                cols: [
                    { id:0, field: 'Placement', class:''},
                    { id:1, field: 'Type', class:''},
                    { id:2, field: 'Name', class:''},
                    { id:3, field: 'Content', class:'' },
                    { id:4, field: 'Rank', class:''},
                    { id:5, field: 'Update',class:'' },
                    { id:6, field: '',class:'' },
                ],
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