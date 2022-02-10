<template>
    <section>
        <div class="es__tickets">
            <h3>{{ event.price_range }}</h3>
            <p @click="visible =! visible">
                See all ticket types and ticket info
            </p>
        </div>

        <div 
            :class="{visible: visible, top: !scroll}"
            class="es__tickets--detailed">
            <div class="es__tickets--title">
                <button @click="visible =! visible">
                    <IconSvg type="delete" />
                </button>
                <h4>Ticket Details</h4>
            </div>
            <div
                class="es__ticket--element"
                v-for="ticket in tickets" 
                :key="ticket.name">
                <div class="es__ticket--name">
                    <h4> {{ ticket.name }} </h4>
                    <template v-if="ticket.type == 'f'">
                        <p>Free</p>
                    </template>
                    <template v-else-if="ticket.type == 'p'">
                        <p>Pay what you can</p>
                    </template>
                    <template v-else>
                        <p> {{ ticket.ticket_price == 0.00 ? 'Free' : `${ticket.currency} ${ticket.ticket_price}` }} </p>
                    </template>
                </div>
                <div class="es__ticket--description">
                    <p> {{ ticket.description }} </p>
                </div>
            </div>
        </div>
        <div 
            :class="{visible: visible}"
            class="es__tickets--background" />
    </section>
</template>

<script>
    import IconSvg from '../../../components/Svg-icon'
    export default {

        props: [ 'event', 'tickets', 'scroll' ],

        components: { IconSvg },

        computed: {
            eventUrl() {
                if (this.event.ticketUrl) {return this.event.ticketUrl}
                if (this.event.websiteUrl) {return this.event.websiteUrl}
                return this.event.organizer.website;
            },
        },

        data() {
            return {
                hover: null,
                visible: false,
            }
        },
    }
</script>