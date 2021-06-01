<template>
    <div v-if="showRenew">
        <div class="renew-dates">
            <template v-if="showRenew === 'a'">
                <p>Your show has less than month left to show as still active. If your show is still going, please click the Save and Renew below to add an additional 6 months.</p>
            </template>
            <template v-if="showRenew === 'o'">
                <p>Your show has less than month left to show as still active. If your show is still going, please click the Save and Renew below to add an additional 6 months.</p>
            </template>
        </div>
    </div>
</template>

<script>
    export default {
        props: [ 'event' ],

    data() {
        return {
            showRenew: this.checkRenew()
        }
    },

    methods: {
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