<template>
    <div class="categories">
        <div class="">
            <div class="title">
                <h1>Categories</h1>
                <div class="add-button">
                    <button 
                        @click="onAdd=!onAdd"
                        class="add__icon" 
                        :class="{active: onAdd}">
                        <svg>
                            <use :xlink:href="`/storage/website-files/icons.svg#ri-add-fill`" />
                        </svg>
                    </button>
                </div>
            </div>
        </div>
        <tabs>
            <!-- location based category tab -->
            <tab 
                title="Location Based Events" 
                :active="true" 
                class="tab-events">
                <div class="data-grid">
                    <div 
                        class="data-grid__row"
                        :key="category.id"
                        v-for="(category) in categories.location">
                        <CardImage
                            :image="`/storage/${category.thumbImagePath}`"
                            :height="800"
                            :width="800"
                            @addImage="addImage($event, category)" />
                        <input 
                            type="text" 
                            v-model="category.name" 
                            placeholder="Category Name"
                            @blur="onEdit(category)">
                        <textarea
                            type="text" 
                            v-model="category.description" 
                            placeholder="Category Name"
                            @blur="onEdit(category)" />
                        <input 
                            type="text" 
                            v-model="category.credit" 
                            placeholder="image credit"
                            @blur="onEdit(category)">
                        <div>
                            <input 
                                type="text" 
                                v-model="category.rank" 
                                placeholder="Category Rank"
                                @blur="onEdit(category)">
                        </div>
                        <button 
                            @click.prevent="modalDelete=category" 
                            class="delete">
                            <svg>
                                <use :xlink:href="`/storage/website-files/icons.svg#ri-close-line`" />
                            </svg>
                        </button>
                    </div>
                </div>
            </tab>

            <!-- remote based category tab -->
            <tab 
                title="Online Events" 
                class="tab-events">
                <div class="data-grid">
                    <div 
                        class="data-grid__row"
                        :key="category.id"
                        v-for="(category) in categories.remote">
                        <CardImage
                            :image="`/storage/${category.thumbImagePath}`"
                            :height="800"
                            :width="800"
                            @addImage="addImage($event, category)" />
                        <input 
                            type="text" 
                            v-model="category.name" 
                            placeholder="Category Name"
                            @blur="onEdit(category)">
                        <textarea
                            type="text" 
                            v-model="category.description" 
                            placeholder="Category Name"
                            @blur="onEdit(category)" />
                        <input 
                            type="text" 
                            v-model="category.credit" 
                            placeholder="image credit"
                            @blur="onEdit(category)">
                        <div>
                            <input 
                                type="text" 
                                v-model="category.rank" 
                                placeholder="Category Rank"
                                @blur="onEdit(category)">
                        </div>
                        <button 
                            @click.prevent="modalDelete=category" 
                            class="delete">
                            <svg>
                                <use :xlink:href="`/storage/website-files/icons.svg#ri-close-line`" />
                            </svg>
                        </button>
                    </div>
                </div>
            </tab>
        </tabs>
        <VueDeleteModal 
            v-if="modalDelete"
            :item="modalDelete"
            :strict="true"
            body="You are deleting the category. Please be sure you know what you are doing."
            @close="modalDelete=null"
            @ondelete="onDelete" />
        <AddCategory 
            @update="update"
            @close="onAdd=false"
            v-if="onAdd" />
    </div>
</template>

<script>
    import AddCategory  from './components/add-category.vue'
    import CardImage from '../../components/Upload-Image.vue'
    import VueDeleteModal from '../../components/modals/Vue-Modal-Delete'

    export default {
        components: { AddCategory, CardImage, VueDeleteModal },

        data() {
            return {
                categories: '',
                onAdd: false,
                modalDelete: null,
                formData: new FormData(),
            }
        },

        methods: {
            async onDelete() {
                await axios.delete(`/categories/${this.modalDelete.slug}`)
                .then( res => { 
                    this.categories = res.data;
                    this.modalDelete = null
                })
            },
            async onEdit(category) {
                await axios.patch(`/categories/${category.slug}`, category)
                .then( res=> { 
                    this.categories = res.data 
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
                .then(res => { this.categories = res.data })
            },
            update(value) {
                this.categories = value
                this.onAdd = false
            },
        },

        created() {
            this.onLoad()
        },
    }

</script>