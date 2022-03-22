<template>
    <div>
        <div class="w-full bg-red-300 fixed bottom-0 h-40 z-[2003]">
            <div class="w-full flex justify-end items-center h-40">
                <span class="mr-4">{{ loadorganizer.user.email }}   </span>
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