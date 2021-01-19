<template>
    <nav 
        class="create organizer" 
        :style="pageHeight">
        <div class="grid create-nav-header">
            <div class="create-nav-header__home">
                <div class="nav-create-logo">
                    <a :href="backUrl">
                        <h3>EI</h3>
                    </a>
                </div>
            </div>
            <div class="nav-back">
                <button 
                    :disabled="disabled" 
                    @click.prevent="onBackInitial()" 
                    class="nav-back-button"> 
                    Dashboard 
                </button>
            </div>
        </div>
        <div class="create-nav-guide">
            <h3>Add your Organization</h3>
        </div>
    </nav>
</template>

<script>
    import formValidationMixin from '../../mixins/form-validation-mixin'

    export default {
        props: ['user', 'organizer'],

        mixins: [formValidationMixin],

        computed: {
            backUrl() {
                return this.user.hasCreatedOrganizers  ? '/create/events/edit' : '/'
            },
        },

        data() {
            return {
                pageHeight:'',
                disabled: false,
            }
        },

        methods: {



        },




    }

</script>