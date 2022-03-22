<template>
    <div>
        <img 
            style="width:20rem"
            :src="`/storage/${loadevent.largeImagePath.slice(0, -4)}jpg`" alt="">
        <p>{{loadevent.name}}</p>
        <p><i>{{loadevent.tag_line}}</i></p>
        <p>{{showDates}}</p>
        <p>{{loadevent.price_range}}</p>
        <p>{{loadevent.age_limits.name}}</p>
        <template v-if="loadevent.hasLocation">
            <h4>Location: </h4>
            <template v-if="loadevent.location.venue">
                <h4>Venue: </h4> <p>{{ loadevent.location.venue }}</p>
            </template>
            <h4>Address</h4>
            <p><span v-if="loadevent.location.city"> {{ loadevent.location.city }},</span> <span v-if="loadevent.location.region"> {{ loadevent.location.region }} </span></p>
        </template>
        <template v-else>
            <h4>Platforms: </h4>
            <div 
                v-for="location in loadevent.remotelocations"
                :key="location.id">
                <p>
                    {{ location.name }}
                </p>
            </div>
        </template>
        <h4>Description: </h4><p>{{loadevent.description}}</p>
        <h4>Event Url (click to copy url)</h4>
        <div>
            <button @click="copyURL">
                https://everythingimmersive.com/events/{{loadevent.slug}}
            </button>
            <div v-if="visible"> Copied!</div>
        </div>
    </div>
</template>

<script>

    export default {
        props: ['loadevent', 'tickets'],

        data() {
            return {
                visible: false
            }
        },

        computed: {
            showDates() {
                if (!this.loadevent.shows.length) { return }

                const lastDate = this.cleanDate(this.loadevent.shows[0].date)
                const firstDate = this.cleanDate(this.loadevent.shows[this.loadevent.shows.length-1].date)

                if (this.loadevent.showtype === 'o') { 
                    return firstDate + '-' + lastDate
                }
                if (this.loadevent.showtype === 'l' || this.loadevent.showtype === 's') { 
                    if (this.loadevent.shows.length > 2) {
                        return firstDate + '-' + lastDate
                    } else {
                        return lastDate
                    }
                }
                return 'Available Anytime'
            }
        },

        methods: {
            cleanDate(data) {
                return this.$dayjs(data).format("MMM DD");
            },

            copyURL () {
                var text = "https://everythingimmersive.com/events/" + this.loadevent.slug;
                navigator.clipboard.writeText(text);
                this.visible = true;
                setTimeout(() => this.visible = false, 1500);
            },
        }
    }
    
</script>