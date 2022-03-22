<template>
    <div>
        <div class="approvebar">
            <div class="comments">
                <div class="field">
                    <label>Mod Comments</label>
                    <textarea 
                        type="text"
                        name="comments" 
                        v-model="comments" 
                        placeholder=" "
                        :class="{ error: $v.comments.$error }"
                        @input="$v.comments.$touch"
                        rows="8" />
                    <div v-if="$v.comments.$error" class="validation-error">
                        <p class="error" v-if="!$v.comments.required">Be sure to include notes</p>
                    </div>
                </div>
            </div>
            <div class="buttons">
                <button 
                    v-for="(button, index) in buttons"
                    :key="index"
                    @click.prevent="button.click"
                    :class="{bspin : disabled}" 
                    :disabled="disabled" 
                    class="create">
                    {{ button.name }}
                </button>
            </div>
        </div>
    </div>
</template>

<script>
    import { required } from 'vuelidate/lib/validators';
    import formValidationMixin from '../../../mixins/form-validation-mixin'

    export default {

        props: ['value'],

        mixins: [formValidationMixin],

        data() {
            return {
                community: this.value,
                comments: '',
                disabled: false,
                buttons: [
                    { click: () => { this.onBack() }, name: 'Go Back' },
                    { click: () => { this.onRejected() }, name: 'Reject' },
                    { click: () => { this.onApproved() }, name: 'Approved' },
                ],
            }
        },

        methods: {
            async onApproved() {
                await axios.post(`/admin/communities/${this.community.slug}/approve`)
                this.redirect('/admin/communities/finalize')
            },
            async onRejected() {
                if (this.checkVuelidate()) { return false }
                await axios.post(`/admin/communities/${this.community.slug}/reject`, { message: this.comments })
                this.redirect('/admin/communities/finalize')
            },
            onEdit() {
                this.redirect(`/index/${this.community.slug}/post`)
            },
            onBack() {
                this.redirect('/admin/communities/finalize')
            },
            redirect(value) {
                window.location.href = value;
            },
        },

        validations: {
            comments: {
                required,
            },
        },
    }
</script>