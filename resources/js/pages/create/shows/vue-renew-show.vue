<template>
    <div v-if="checkRenew">
        <div class="renew-dates">
            <template v-if="checkRenew === 'a'">
                <p>Your event will expire in less than one month. If your event is still going, please click the Save and Renew button below to add an additional 6 months.</p>
            </template>
            <template v-if="checkRenew === 'o'">
                <p>Your event will expire in less than one month. If your event is still going, please click the Save and Renew button below to add an additional 6 months.</p>
            </template>
        </div>
    </div>
</template>

<script>
    export default {

    props: [ 'event' ],

    computed: {
        checkRenew() {
            if (!this.event.shows.length) {return}
            if (this.event.status !== 'p' && this.event.status !== 'e') {return}

            const lastShowDate = this.event.shows[0].date;
            const oneMonthOut = this.$dayjs().add(30, 'day').format('YYYY-MM-DD 23:59:00')
            
            if (lastShowDate < oneMonthOut) {
                if (this.event.showtype === 'o') { return 'o'}
                if (this.event.showtype === 'a') { return 'a'}
            }

            return null
        },
    },

}
</script>