<template>
    <div class="admin-mobility">
        <div class="">
            <div class="title">
                <h1>Mobility Advisories</h1>
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
                <p>Mobility</p>
                <p>Rank</p>
            </div>  
            <div 
                class="data-grid__row" 
                :key="mobility.id"
                v-for="(mobility) in mobilities">
                <input 
                    type="text" 
                    v-model="mobility.mobilities" 
                    placeholder="Mobilities"
                    @blur="onUpdate(mobility)">
                <input 
                    type="text" 
                    v-model="mobility.rank" 
                    placeholder="Mobilities"
                    @blur="onUpdate(mobility)">
                <button 
                    @click.prevent="selectedModal=mobility" 
                    class="delete">
                    <svg>
                        <use :xlink:href="`/storage/website-files/icons.svg#ri-close-line`" />
                    </svg>
                </button>
            </div>
        </div>
        <AddMobilityAdvisory 
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
    import AddMobilityAdvisory from './components/add-mobility-advisory'

    export default {
        components: { AddMobilityAdvisory, VueDeleteModal },

        data() {
            return {
                mobilities: '',
                onAdd: false,
                selectedModal: '',
            }
        },

        methods: {

            async onDelete() {
                await axios.delete(`/mobilities/${this.selectedModal.id}`)
                .then( res => { 
                    this.mobilities = res.data; 
                    this.selectedModal = null 
                })
            },
            async onUpdate(mobility) {
                await axios.patch(`/mobilities/${mobility.id}`, mobility)
                .then( res => { this.mobilities = res.data })
            },
            async onLoad() {
                await axios.get('/mobilities')
                .then( res => { this.mobilities = res.data })
            },
            update(value) {
                this.mobilities = value
                this.onAdd = false
            }
        },

        mounted() {
            this.onLoad()
        },
    }

</script>