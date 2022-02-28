<template>
    <div>
        <div class="w-full bg-red-300 fixed bottom-0 h-40 z-[2003]">
            <div class="absolute left-12 top-[-12rem] bg-white border w-[35rem] rounded-2xl p-4">
                <div class="field">
                    <label>Mod Comments</label>
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
            <VueModalAccept 
                @onSubmit="showExists = false"
                @close="showExists = false"
                v-if="exists && showExists">
                <h3>Warning</h3>
                <p style="display:inline">An event named {{ exists.name }} already exists. Please review the live listing to ensure you are not approving a duplicate event.</p>
                <a 
                    style="color:#3a3aff" 
                    target="_blank" 
                    :href="`/events/${exists.slug}`">View Event</a>
            </VueModalAccept>
            <div class="bg-blue-500 absolute right-0 mt-[-4rem] p-4 rounded-tl-2xl">
                <a 
                    rel="noreferrer noopener" 
                    target="_blank" 
                    class="text-white"
                    :href="loadevent.ticketUrl">
                    {{ loadevent.ticketUrl }}
                </a>
            </div>
            <div class="w-full flex justify-end items-center h-40">
                <button 
                    v-for="(button, index) in buttons"
                    :key="index"
                    @click.prevent="button.click"
                    :class="{bspin : dis}" 
                    :disabled="dis" 
                    class="mr-4 bg-white rounded-2xl hover:bg-black hover:text-white hover:border-black">
                    {{ button.name }}
                </button>
            </div>
        </div>
    </div>
</template>

<script>
    import { required } from 'vuelidate/lib/validators';
    import formValidationMixin from '../../../mixins/form-validation-mixin'
    import VueModalAccept from '../../../components/modals/Vue-Modal-Accept'

    export default {
        props: ['loadevent', 'exists'],

        mixins: [ formValidationMixin ],

        components: { VueModalAccept },

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
                showExists: this.exists ? true : false,
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