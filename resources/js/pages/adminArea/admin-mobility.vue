<template>
    <div class="mobility">
        <div class="">
            <div class="title">
                <h1>Mobility</h1>
                <div class="add">
                    <button @click.prevent="isModalVisible = true">
                        <IconSvg type="add" />
                    </button>
                </div>
            </div>
        </div>

        <div 
            class="list" 
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
                @click.prevent="showModal(mobility)" 
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
                <p>You are deleting {{modalDelete.mobility}}.</p>
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
                    <div></div>
                </div>
                <div slot="body" class="body">
                    <div class="text">
                        <div class="name">
                            <input 
                                type="text" 
                                v-model="mobility" 
                                placeholder="Mobilities"
                                :class="{ active: mobilityActive}"
                                @click="mobilityActive = true"
                                @blur="mobilityActive = false"
                                @input="$v.mobility.$touch">
                            <div v-if="$v.mobility.$error" class="validation-error">
                                <p class="error" v-if="!$v.mobility.required">Please Add Mobility </p>
                            </div>
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

        data() {
            return {
                mobilities: '',
                mobilityActive: false,
                mobility: '',
                isModalVisible: false,
                isEditModalVisible: false,
                modalDelete: '',
            }
        },

        methods: {

            onSubmit(){
                this.$v.$touch(); 
                if (this.$v.$invalid) { return false };

                axios.post('/mobilities', { mobilities: this.mobility })
                .then( res => { this.mobilities = res.data; this.clearMobility(); })
                .catch( this.isModalVisible = false )
            },

            onDelete(mobility) {
                axios.delete(`/mobilities/${mobility.id}`)
                .then( res => { this.mobilities = res.data; this.isEditModalVisible = false })
                .catch( error => { this.serverErrors = error.response.data.errors; });
            },

            onUpdate(mobility) {
                axios.patch(`/mobilities/${mobility.id}`, mobility)
                .then( res => { this.mobilities = res.data })
                .catch( error => { this.serverErrors = error.response.data.errors });
            },

            onLoad() {
                axios.get('/mobilities')
                .then( res => { this.mobilities = res.data })
                .catch( error => { this.serverErrors = error.response.data.errors; });
            },

            showModal(mobility) {
                this.modalDelete = mobility;
                this.isEditModalVisible = true;
            },

            clearMobility() {
                this.isModalVisible = false;
                this.mobility = '';
            },

        },

        created() {
            this.onLoad()
        },

        validations: {
            mobility: {
                required,
            },
        },
    }

</script>