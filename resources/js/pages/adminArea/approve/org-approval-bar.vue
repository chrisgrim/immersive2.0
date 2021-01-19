<template>
    <div>
        <div class="approvebar">
            <div class="buttons">
                <span style="display:inline-block;margin:0rem 6rem">{{ loadorganizer.user.email }}   </span>
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

    export default {

        props: ['loadorganizer'],

        data() {
            return {
                organizer: this.loadorganizer,
                dis: false,
                buttons: [
                    { click: () => { this.onBack() }, name: 'Go Back' },
                    { click: () => { this.onDelete() }, name: 'Delete' },
                    { click: () => { this.onEdit() }, name: 'Make Edits Yourself' },
                    { click: () => { this.onApproved() }, name: 'Approved' },
                ],
            }
        },

        methods: {
            async onApproved() {
                await axios.post(`/admin/organizer/${this.organizer.slug}/approve`)
                this.redirect('/admin/organizers/finalize')
            },

            async onDelete() {
                await axios.post(`/admin/organizer/${this.organizer.slug}/delete`)
                this.redirect('/admin/organizers/finalize')
            },

            onEdit() {
                this.redirect(`/organizer/${this.organizer.slug}/edit`)
            },

            onBack() {
                this.redirect('/admin/organizers/finalize')
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