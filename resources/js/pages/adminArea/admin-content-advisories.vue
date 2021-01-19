<template>
    <div class="contentlevels">
        <div class="">
            <div class="title">
                <h1>Content Advisories</h1>
                <div class="add">
                    <button @click.prevent="isModalVisible = true">
                        <IconSvg type="add" />
                    </button>
                </div>
            </div>
        </div>

        <div 
            class="list"
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
                @click.prevent="showModal(advisory)" 
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
                <p>You are deleting {{ modalDelete.advisories }}.</p>
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
                                v-model="contentAdvisory" 
                                placeholder="Content Advisory"
                                :class="{ active: contentActive}"
                                @click="contentActive = true"
                                @blur="contentActive = false"
                                @input="$v.contentAdvisory.$touch">
                            <div v-if="$v.contentAdvisory.$error" class="validation-error">
                                <p class="error" v-if="!$v.contentAdvisory.required">Please Add Content Advisories </p>
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
    
    import { required } from 'vuelidate/lib/validators'
    import IconSvg from '../../components/Svg-icon'


    export default {

        components: { IconSvg },

        data() {
            return {
                contentAdvisory: '',
                contentActive: false,
                contentAdvisories: '',
                isModalVisible: false,
                isEditModalVisible: false,
                modalDelete: '',
            }
        },

        methods: {

            onSubmit() {
                this.$v.$touch(); 
                if (this.$v.$invalid) { return false }

                axios.post('/contentadvisories', { advisories: this.contentAdvisory })
                .then(res => { this.contentAdvisories = res.data; this.clearAdvisory(); })
                .catch( this.isModalVisible = false );
            },

            onDelete(advisory) {
                axios.delete(`/contentadvisories/${advisory.id}`)
                .then(res => { this.contentAdvisories = res.data; this.isEditModalVisible = false })
                .catch(error => { this.serverErrors = error.response.data.errors; });
            },

            onLoad() {
                axios.get('/contentadvisories')
                .then( res => { this.contentAdvisories = res.data })
                .catch();
            },

            onUpdate(advisory) {
                axios.patch(`/contentadvisories/${advisory.id}`, advisory)
                .then( res => { this.contentAdvisories = res.data })
                .catch();
            },

            clearAdvisory() {
                this.isModalVisible = false;
                this.contentAdvisory = '';
            },

            showModal(advisory) {
                this.modalDelete = advisory;
                this.isEditModalVisible = true;
            },
        },

        created() {
            this.onLoad()
        },

        validations: {
            contentAdvisory: {
                required,
            },
        },
    }

</script>