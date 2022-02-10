export default {

    methods: {
        checkVuelidate() {
            this.disabled = true
            this.$v.$touch()
            this.$v.$invalid ? this.disabled = false : this.disabled = true
            return this.$v.$invalid
        },

        checkForChanges(value) {
            if ( !this.$v.$anyDirty && value !== 'save' && this.event.status + 1 >= this.creationPage ) {
                return true;
            }
        },

        onErrors(err) {
            err ? this.serverErrors = err.response.data.errors : '';
            this.disabled = false;
        },

        clearErrors() {
            this.serverErrors = null
        },

        onSingleError(err) {
            this.disabled = false;
            this.serverErrors = err.response.data.message;
        },

        onBackInitial() {
            return window.location.href = `/create/events/edit`;
        },

        onBack(value) {
            return value == 'exit' ?  this.onBackInitial() :  window.location.href = `/create/${this.event.slug}/${value}`
        },

        onFetch(value) {
            return `/create/${this.event.slug}/${value}/fetch?timestamp=${new Date().getTime()}`
        },
            
        onForward(value) {
            window.location.href = `/create/${this.event.slug}/${value}`;
            this.disabled = false;
        },

        onRegistered() {
            return window.location.href = '/verify-email';
        },

        onFinishOrganizer(value) {
            return window.location.href = value;
        },

        save() {
            this.checkResubmitStatus();
            this.onLoad();
            this.disabled = false;
            this.updated = true;
            setTimeout(() => this.updated = false, 3000);
        },

        checkResubmitStatus() {
            this.resubmit == true ? location.reload() : '';
        }
    }
}