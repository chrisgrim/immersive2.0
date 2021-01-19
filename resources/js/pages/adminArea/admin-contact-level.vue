<template>
    <div class="contactlevels">
        <div class="">
            <div class="title">
                <h1>Contact Levels</h1>
                <div class="add">
                    <button @click.prevent="isModalVisible = true">
                        <IconSvg type="add" />
                    </button>
                </div>
            </div>
        </div>

        <div 
            class="list" 
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
                @click.prevent="showModal(level)" 
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
                <p>You are deleting {{modalDelete.level}}.</p>
            </div>
            <div slot="footer">
                <button 
                    class="btn del" 
                    @click.prevent="onDelete(modalDelete)">Delete</button>
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
                                v-model="contactLevel" 
                                placeholder="Contact Level Name"
                                :class="{ active: levelActive}"
                                @click="levelActive = true"
                                @blur="levelActive = false"
                                @input="$v.contactLevel.$touch">
                            <div v-if="$v.contactLevel.$error" class="validation-error">
                                <p class="error" v-if="!$v.contactLevel.required">Please Add Contact Level </p>
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
                contactLevels: '',
                levelActive: false,
                contactLevel: '',
                isModalVisible: false,
                isEditModalVisible: false,
                modalDelete: '',
            }
        },

        methods: {

            onSubmit() {
                this.$v.$touch(); 
                if (this.$v.$invalid) { return false }

                axios.post('/contactlevels', {level: this.contactLevel})
                .then( res => { this.contactLevels = res.data })
                .catch( this.isModalVisible = false );
            },

            onDelete(level) {
                axios.delete(`/contactlevels/${level.id}`)
                .then( res => { this.isEditModalVisible = false; this.contactLevels = res.data })
                .catch( error => { this.serverErrors = error.response.data.errors; });
            },

            onLoad() {
                axios.get('/contactlevels')
                .then( res => { this.contactLevels = res.data })
                .catch( error => { this.serverErrors = error.response.data.errors });
            },

            onUpdate(level) {
                axios.patch(`/contactlevels/${level.id}`, level )
                .then( res => { this.contactLevels = res.data })
                .catch( error => { this.serverErrors = error.response.data.errors });
            },

            showModal(level) {
                this.modalDelete = level;
                this.isEditModalVisible = true;
            },

            clearData() {
                this.isModalVisible = false;
                this.contactLevel = '';
            },

        },

        created() {
            this.onLoad()
        },

        validations: {
            contactLevel: {
                required,
            },
        },
    }

</script>