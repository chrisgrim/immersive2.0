<template>
    <div>
        <div class="flex justify-between mb-8 items-center">
            <h2>Categories</h2>
            <button 
                @click="onAdd=!onAdd"
                class="border-none w-20 h-20 flex p-0 rounded-full justify-center items-center hover:bg-slate-300">
                <svg class="w-16 h-16">
                    <use :xlink:href="`/storage/website-files/icons.svg#ri-add-fill`" />
                </svg>
            </button>
        </div>

        <AddCategory 
            @update="update"
            v-if="onAdd" />

        <div class="w-96 mb-4">
            <v-select 
                v-model="inputType"
                :options="options"
                label="name"
                placeholder="Filter by type"
                @input="onLoad" />
        </div>

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
                                :key="category.id"
                                v-for="category in categories">
                                <td class="border-b border-slate-100 dark:border-slate-700 p-4 text-slate-500 dark:text-slate-400">
                                    <CardImage
                                        :image="`${envImageUrl}${category.thumbImagePath}`"
                                        :height="800"
                                        :width="800"
                                        @addImage="addImage($event, category)" />
                                </td>
                                <td class="border-b border-slate-100 dark:border-slate-700 p-4 text-slate-500 dark:text-slate-400">
                                    <input 
                                        type="text" 
                                        v-model="category.name" 
                                        class="w-full p-2" 
                                        placeholder="Category"
                                        @blur="onEdit(category)">
                                </td>
                                <td class="border-b border-slate-100 dark:border-slate-700 p-4 text-slate-500 dark:text-slate-400">
                                    <textarea 
                                        v-model="category.description"
                                        rows="2"
                                        placeholder=" "
                                        @blur="onEdit(category)"
                                        required 
                                        autofocus />
                                </td>
                                <td class="border-b border-slate-100 dark:border-slate-700 p-4 text-slate-500 dark:text-slate-400">
                                    <input 
                                        type="text" 
                                        v-model="category.credit" 
                                        class="p-2" 
                                        placeholder="Image Credit"
                                        @blur="onEdit(category)">
                                </td>
                                <td class="border-b border-slate-100 dark:border-slate-700 p-4 text-slate-500 dark:text-slate-400">
                                    <input 
                                        type="text" 
                                        v-model="category.rank" 
                                        class="max-w-[5rem] p-2" 
                                        placeholder="Rank"
                                        @blur="onEdit(category)">
                                </td>
                                <td class="border-b border-slate-100 dark:border-slate-700 p-4 text-slate-500 dark:text-slate-400">
                                    <button 
                                        @click.prevent="modalDelete=category" 
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

        <VueDeleteModal 
            v-if="modalDelete"
            :item="modalDelete"
            :strict="true"
            body="You are deleting the category. Please be sure you know what you are doing."
            @close="modalDelete=null"
            @ondelete="onDelete" />
    </div>
</template>

<script>
    import AddCategory  from './Components/category-component.vue'
    import CardImage from '../../../components/Upload-Image.vue'
    import VueDeleteModal from '../../../components/modals/Vue-Modal-Delete'

    export default {
        components: { AddCategory, CardImage, VueDeleteModal },

        computed: {
            categories: {
                get() {
                    return this.inputType === 'location based' ? this.location : this.remote
                },
                set() {

                }
            }
        },

        data() {
            return {
                onAdd: false,
                modalDelete: null,
                formData: new FormData(),
                location: '',
                remote: '',
                inputType: 'location based',
                options: [ 'location based', 'remote based'],
                cols: [
                    { id:0, field: '', class:''},
                    { id:1, field: 'Category', class:''},
                    { id:2, field: 'Description', class:''},
                    { id:3, field: 'Image Credit', class:'' },
                    { id:4, field: 'Rank', class:''},
                    { id:5, field: '',class:'' },
                ],
            }
        },

        methods: {
            async onDelete() {
                await axios.delete(`/categories/${this.modalDelete.slug}`)
                .then( res => { 
                    this.location = res.data.location 
                    this.remote = res.data.remote 
                    this.modalDelete = null
                })
            },
            async onEdit(category) {
                await axios.patch(`/categories/${category.slug}`, category)
                .then( res=> { 
                    this.location = res.data.location
                    this.remote = res.data.remote 
                })
                .catch( err => {
                    err.response.data.errors.name ? alert(err.response.data.errors.name) : ''
                    this.onLoad();
                }) 
            },
            async addImage(image, category) {
                this.formData.append('image', image);
                this.formData.append('_method', 'PUT');
                await axios.post(`/categories/${category.slug}`, this.formData)
                this.formData = new FormData()
            },
            async onLoad() {
                await axios.get(`/categories?timestamp=${new Date().getTime()}`)
                .then(res => { 
                    this.location = res.data.location 
                    this.remote = res.data.remote 
                })
            },
            update(value) {
                this.location = value.location 
                this.remote = value.remote 
                this.onAdd = false
            },
        },

        created() {
            this.onLoad()
        },
    }

</script>