<template>
    <div class="genres">
        <div class="">
            <div class="title">
                <h1>Remote Locations</h1>
                <div class="add">
                    <button @click.prevent="isModalVisible = true">
                        <IconSvg type="add" />
                    </button>
                </div>
            </div>
        </div>

        <div class="list">
            <div>
                name
            </div>
            <div>
                description
            </div>
            <div>
                admin
            </div>
            <div>
                rank
            </div>
        </div>  
        <div 
            class="list" 
            :key="remoteLocation.id"
            v-for="(remoteLocation) in remoteLocations">
            <input 
                type="text" 
                v-model="remoteLocation.name" 
                placeholder="Remote Location"
                @blur="onUpdate(remoteLocation)">
            <textarea
                type="text" 
                v-model="remoteLocation.description" 
                placeholder="Remote location description"
                @blur="onUpdate(remoteLocation)" />
            <select 
                placeholder="Admin"
                v-model="remoteLocation.admin"
                @change="onUpdate(remoteLocation)">
                <option value="1">True</option>
                <option value="0">False</option>
            </select>
            <input 
                type="text" 
                v-model="remoteLocation.rank" 
                placeholder="Rank"
                @blur="onUpdate(remoteLocation)">
            <button 
                @click.prevent="showModal(remoteLocation)" 
                class="delete-circle">
                <IconSvg type="delete" />
            </button>
        </div>
        <modal 
            v-if="isEditModalVisible" 
            @close="isEditModalVisible = false">
            <div slot="header">
                <div class="circle del">
                    <p>X</p>
                </div>
            </div>
            <div slot="body"> 
                <h3>Are you sure?</h3>
                <p>You are deleting {{ modalDelete.location }}.</p>
            </div>
            <div slot="footer">
                <button 
                    class="btn del" 
                    @click.prevent="onDelete(modalDelete)">
                    Delete
                </button>
            </div>
        </modal>

        <div class="pin noimg">
            <modal 
                v-if="isModalVisible" 
                @close="isModalVisible = false">
                <div slot="header">
                    <div />
                </div>
                <div slot="body" class="body">
                    <div class="text">
                        <div class="name">
                            <input 
                                type="text" 
                                v-model="remoteLocation.name" 
                                placeholder="Remote Location"
                                :class="{ active: activeItem = 'name'}"
                                @click="activeItem = 'name'"
                                @blur="activeItem = null"
                                @input="$v.remoteLocation.name.$touch">
                            <div v-if="$v.remoteLocation.name.$error" class="validation-error">
                                <p class="error" v-if="!$v.remoteLocation.name.required">Please Add Remote Location </p>
                            </div>
                            <textarea
                                type="text" 
                                v-model="remoteLocation.description" 
                                placeholder="Remote location description"
                                @blur="onUpdate(remoteLocation)" />
                        </div>
                    </div>
                </div>
                <div slot="footer">
                    <button 
                        @click.prevent="onSubmit" 
                        class="btn sub">
                        Submit
                    </button>
                </div>
            </modal>
        </div>
    </div>
</template>

<script>
    
    import { required } from 'vuelidate/lib/validators';    
    import IconSvg from '../../components/Svg-icon'

    export default {

        components: { IconSvg },

        computed: {
            submitObject() {
                return {
                    name: this.remoteLocation.name,
                    description: this.remoteLocation.description
                }
            }
        },

        data() {
            return {
                remoteLocations: '',
                activeItem: '',
                remoteLocation: {
                    name: '',
                    description: '',
                },
                isModalVisible: false,
                isEditModalVisible: false,
                modalDelete: '',
            }
        },

        methods: {
            onSubmit() {
                this.$v.$touch(); 
                if (this.$v.$invalid) { return }

                axios.post('/remotelocations', this.submitObject)
                .then( res => { this.remoteLocations = res.data; })
                .catch( this.isModalVisible = false );
            },

            onDelete(remotelocations) {
                axios.delete(`/remotelocations/${remotelocations.id}`)
                .then( res => { this.remoteLocations = res.data; this.isEditModalVisible = false })
                .catch( error => { this.serverErrors = error.response.data.errors; });
            },

            onLoad() {
                axios.get('/remotelocations')
                .then( res => { this.remoteLocations = res.data })
                .catch( error => { this.serverErrors = error.response.data.errors; });
            },

            onUpdate(remoteLocation) {
                axios.patch(`/remotelocations/${remoteLocation.id}`, remoteLocation)
                .then( res => { this.remoteLocations = res.data })
                .catch( error => { this.serverErrors = error.response.data.errors });
            },

            showModal(remotelocations) {
                this.modalDelete = remotelocations;
                this.isEditModalVisible = true;
            },
        },

        created() {
            this.onLoad()
        },

        validations: {
            remoteLocation: {
                name: {
                    required,
                },
                description: {
                    required,
                }
            },
        },
    }
</script>