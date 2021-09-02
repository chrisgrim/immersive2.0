<template>
    <div class="admin-interactive-levels">
        <div class="">
            <div class="title">
                <h1>Interactive Levels</h1>
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
                <p>Name</p>
                <p>Description</p>
                <p>Rank</p>
            </div>  
            <div 
                class="data-grid__row" 
                :key="level.id"
                v-for="(level) in interactiveLevels">
                <input 
                    type="text" 
                    v-model="level.name" 
                    placeholder="Interactive Level"
                    @blur="onUpdate(level)">
                <textarea
                    type="text" 
                    v-model="level.description" 
                    placeholder="Interactive level description"
                    @blur="onUpdate(level)" />
                <input 
                    type="text" 
                    v-model="level.rank" 
                    placeholder="Rank"
                    @blur="onUpdate(level)">
                <button 
                    @click.prevent="selectedModal=level" 
                    class="delete">
                    <svg>
                        <use :xlink:href="`/storage/website-files/icons.svg#ri-close-line`" />
                    </svg>
                </button>
            </div>
        </div>
        <AddInteractiveLevel 
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
    import AddInteractiveLevel from './components/add-interactive-level'

    export default {
        components: { AddInteractiveLevel, VueDeleteModal },


        data() {
            return {
                interactiveLevels: '',
                selectedModal: '',
                onAdd: false,
            }
        },

        methods: {
            async onDelete() {
                await axios.delete(`/interactivelevels/${this.selectedModal.id}`)
                .then( res => { 
                    this.interactiveLevels = res.data; 
                    this.selectedModal = null 
                })
            },

            async onLoad() {
                await axios.get('/interactivelevels')
                .then( res => { this.interactiveLevels = res.data })
            },

            async onUpdate(interactiveLevel) {
                await axios.patch(`/interactivelevels/${interactiveLevel.id}`, interactiveLevel)
                .then(res => { this.interactiveLevels = res.data })
            },
            update(value) {
                this.interactiveLevels = value
                this.onAdd = false
            }

        },

        mounted() {
            this.onLoad()
        },

    }

</script>