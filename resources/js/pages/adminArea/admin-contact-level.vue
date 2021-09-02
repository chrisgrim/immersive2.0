<template>
    <div class="contactlevels">
        <div class="">
            <div class="title">
                <h1>Contact Levels</h1>
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
                <p>Rank</p>
            </div>
            <div 
                class="data-grid__row"
                :key="level.id"
                v-for="(level) in contactLevels">
                <input 
                    type="text" 
                    v-model="level.level" 
                    placeholder="Contact Level Name"
                    @blur="onUpdate(level)">
                <input 
                    type="text" 
                    v-model="level.rank" 
                    placeholder="Contact Level Rank"
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
        <AddContactLevel 
            @update="update"
            @close="onAdd=false"
            v-if="onAdd" />
        <VueDeleteModal 
            v-if="selectedModal"
            :item="selectedModal"
            :strict="true"
            :body="`You are deleting ${selectedModal.level}`"
            @close="selectedModal=null"
            @ondelete="onDelete" />
    </div>
</template>

<script>
    import VueDeleteModal from '../../components/modals/Vue-Modal-Delete'
    import AddContactLevel from './components/add-contact-level'

    export default {

        components: { VueDeleteModal, AddContactLevel },

        data() {
            return {
                contactLevels: '',
                onAdd: false,
                selectedModal: null,
            }
        },

        methods: {
            async onDelete() {
                await axios.delete(`/contactlevels/${this.selectedModal.id}`)
                .then( res => { 
                    this.contactLevels = res.data 
                    this.selectedModal = null
                })
            },
            async onLoad() {
                await axios.get('/contactlevels')
                .then( res => { this.contactLevels = res.data })
            },
            async onUpdate(level) {
                await axios.patch(`/contactlevels/${level.id}`, level )
                .then( res => { this.contactLevels = res.data })
            },
            update(value) {
                this.contactLevels = value
                this.onAdd = false
            }
        },

        mounted() {
            this.onLoad()
        },
    }

</script>