<template>
    <div>
        <div class="approvebar">
            <div class="comments">
                <div class="field">
                    <label>Mod Comments1</label>
                    <textarea 
                        type="text"
                        name="comments" 
                        v-model="comments" 
                        placeholder=" "
                        :class="{ active: commentsActive }"
                        @click="commentsActive = true"
                        @blur="commentsActive = false" 
                        @input="$v.comments.$touch"
                        rows="8" />
                    <div v-if="$v.comments.$error" class="validation-error">
                        <p class="error" v-if="!$v.comments.required">Be sure to include notes</p>
                    </div>
                </div>
            </div>
            <template v-if="exists">
                <div class="admin-approve-similar-name">
                    <a 
                        rel="noreferrer noopener" 
                        target="_blank" 
                        :href="`/events/${exists.slug}`">An event named {{ exists.name }} already exists</a>
                </div>
            </template>
            <div class="admin-approve-url">
                <a 
                    rel="noreferrer noopener" 
                    target="_blank" 
                    :href="loadevent.ticketUrl">
                    {{ loadevent.ticketUrl }}
                </a>
            </div>
            <div class="buttons">
                <button 
                    v-for="(button, index) in buttons"
                    :key="index"
                    @click.prevent="button.click"
                    :class="{bspin : dis}" 
                    :disabled="dis" 
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
        props: ['loadevent', 'exists'],

        mixins: [ formValidationMixin ],

        computed: {
            approvedOrg() {
                return this.loadevent.organizer.status == 'p' ? true : false;
            }
        },

        data() {
            return {
                event: this.loadevent,
                comments: '',
                commentsActive: false,
                dis: false,
                buttons: [
                    { click: () => { this.onEditOrganizer() }, name: 'Organizer' },
                    { click: () => { this.onBack() }, name: 'Go Back' },
                    { click: () => { this.onRejected() }, name: 'Reject' },
                    { click: () => { this.onEdit() }, name: 'Make Edits Yourself' },
                    { click: () => { this.onDenied() }, name: 'Request Changes' },
                    { click: () => { this.onApproved() }, name: 'Approved' },
                ],
            }
        },

        methods: {
            async onApproved() {
                await axios.post(`/admin/event/${this.event.slug}/approve`)
                this.redirect('/admin/events/finalize')
            },

            async onDenied() {
                if (this.checkVuelidate()) { return false }
                await axios.post(`/admin/event/${this.event.slug}/fail`, { message: this.comments })
                this.redirect('/admin/events/finalize')
            },

            async onRejected() {
                if (this.checkVuelidate()) { return false }
                await axios.post(`/admin/event/${this.event.slug}/reject`, { message: this.comments })
                this.redirect('/admin/events/finalize')
            },

            onEdit() {
                this.redirect(`/create/${this.event.slug}/title`)
            },

            onEditOrganizer() {
                this.redirect(`/organizer/${this.event.organizer.slug}/edit`)
            },

            onBack() {
                this.redirect('/admin/events/finalize');
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