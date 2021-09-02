<template>
    <div class="admin-content-advisories">
        <div class="">
            <div class="title">
                <h1>Content Advisories</h1>
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

        <div class="data-grid">
            <div class="data-grid__row header">
                <p>Advisory</p>
                <p>Rank</p>
            </div>
            <div 
                class="data-grid__row"
                :key="advisory.id" 
                v-for="(advisory) in contentAdvisories">
                <input 
                    type="text" 
                    v-model="advisory.advisories" 
                    placeholder="Content Advisories"
                    @blur="onUpdate(advisory)">
                <input 
                    type="text" 
                    v-model="advisory.rank" 
                    placeholder="Content Advisories"
                    @blur="onUpdate(advisory)">
                <button 
                    @click.prevent="selectedModal=advisory" 
                    class="delete">
                    <svg>
                        <use :xlink:href="`/storage/website-files/icons.svg#ri-close-line`" />
                    </svg>
                </button>
            </div>
        </div>
        <AddContentAdvisory 
            @update="update"
            @close="onAdd=false"
            v-if="onAdd" />
        <VueDeleteModal 
            v-if="selectedModal"
            :item="selectedModal"
            :strict="true"
            body="You are deleting the category. Please be sure you know what you are doing."
            @close="selectedModal=null"
            @ondelete="onDelete" />
    </div>
</template>

<script>
    import VueDeleteModal from '../../components/modals/Vue-Modal-Delete'
    import AddContentAdvisory from './components/add-content-advisory'

    export default {
        components: { AddContentAdvisory, VueDeleteModal },

        data() {
            return {
                contentAdvisories: '',
                selectedModal: null,
                onAdd: false,
            }
        },

        methods: {

            async onDelete() {
                await axios.delete(`/contentadvisories/${this.selectedModal.id}`)
                .then(res => { 
                    this.contentAdvisories = res.data; 
                    this.selectedModal = null 
                })
            },
            async onLoad() {
                await axios.get('/contentadvisories')
                .then( res => { this.contentAdvisories = res.data })
            },
            async onUpdate(advisory) {
                await axios.patch(`/contentadvisories/${advisory.id}`, advisory)
                .then( res => { this.contentAdvisories = res.data })
            },
            update(value) {
                this.contentAdvisories = value
                this.onAdd = false
            }
        },

        created() {
            this.onLoad()
        },

    }

</script>