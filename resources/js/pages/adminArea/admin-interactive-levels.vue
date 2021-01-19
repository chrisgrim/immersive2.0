<template>
    <div class="genres">
        <div class="">
            <div class="title">
                <h1>Interactive Levels</h1>
                <div class="add">
                    <button @click.prevent="isModalVisible = true">
                        <IconSvg type="add" />
                    </button>
                </div>
            </div>
        </div>

        <div 
            class="list" 
            :key="interactiveLevel.id"
            v-for="(interactiveLevel) in interactiveLevels">
            <input 
                type="text" 
                v-model="interactiveLevel.name" 
                placeholder="Interactive Level"
                @blur="onUpdate(interactiveLevel)">
            <textarea
                type="text" 
                v-model="interactiveLevel.description" 
                placeholder="Interactive level description"
                @blur="onUpdate(interactiveLevel)" />
            <input 
                type="text" 
                v-model="interactiveLevel.rank" 
                placeholder="Rank"
                @blur="onUpdate(interactiveLevel)">
            <button 
                @click.prevent="showModal(interactiveLevel)" 
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
                <p>You are deleting {{ modalDelete.level }}.</p>
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
                                v-model="interactiveLevel.name" 
                                placeholder="Interactive Level"
                                :class="{ active: active = 'name'}"
                                @click="active = 'name'"
                                @blur="active = null"
                                @input="$v.interactiveLevel.name.$touch">
                            <div v-if="$v.interactiveLevel.name.$error" class="validation-error">
                                <p class="error" v-if="!$v.interactiveLevel.name.required">Please add interactive level name </p>
                            </div>
                            <textarea
                                type="text" 
                                v-model="interactiveLevel.description" 
                                placeholder="Interactive level description"
                                @blur="onUpdate(interactiveLevel)" />
                            <div v-if="$v.interactiveLevel.description.$error" class="validation-error">
                                <p class="error" v-if="!$v.interactiveLevel.description.required">Please add interactive level description </p>
                            </div>
                        </div>
                    </div>
                </div>
                <div slot="footer">
                    <button 
                        @click.prevent="onSubmit()" 
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

        data() {
            return {
                interactiveLevels: '',
                active: '',
                interactiveLevel: {
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
                if (this.$v.$invalid) { return false }

                axios.post('/interactivelevels', this.interactiveLevel)
                .then( res => { this.interactiveLevels = res.data })
                .catch( this.isModalVisible = false );
            },

            onDelete(interactiveLevel) {
                axios.delete(`/interactivelevels/${interactiveLevel.id}`)
                .then( res => { this.interactiveLevels = res.data; this.isEditModalVisible = false })
                .catch( error => { this.serverErrors = error.response.data.errors });
            },

            onLoad() {
                axios.get('/interactivelevels')
                .then( res => { this.interactiveLevels = res.data })
                .catch( error => { this.serverErrors = error.response.data.errors; });
            },

            onUpdate(interactiveLevel) {
                axios.patch(`/interactivelevels/${interactiveLevel.id}`, interactiveLevel)
                .then(res => { this.interactiveLevels = res.data })
                .catch( error => {  this.serverErrors = error.response.data.errors });
            },

            showModal(interactiveLevel) {
                this.modalDelete = interactiveLevel;
                this.isEditModalVisible = true;
            },
        },

        created() {
            this.onLoad()
        },

        validations: {
            interactiveLevel: {
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