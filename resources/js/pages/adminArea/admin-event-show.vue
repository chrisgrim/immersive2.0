<template>
    <div>
        <img 
            style="width:20rem"
            :src="`/storage/${loadevent.largeImagePath.slice(0, -4)}jpg`" alt="">
        <h1 style="padding:2rem 0rem">{{loadevent.name}}</h1>
        <p>{{loadevent.tag_line}}</p>
        <p>{{showDates}}</p>
        <p>{{loadevent.price_range}}</p>

        <h3 style="display:inline-block;">{{loadevent.name}}:</h3>
        <span style="display: inline-block;"><p>{{showDates}};</p></span>
        <span style="display: inline-block;"><p>{{loadevent.price_range}};</p></span>
        <span style="display: inline-block;"><p>{{loadevent.tag_line}}</p></span>
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
                if (this.loadevent.showtype === 'o') { 
                    return this.cleanDate(this.loadevent.shows[0].date) + '-' + this.cleanDate(this.loadevent.shows[this.loadevent.shows.length-1].date)
                }
                if (this.loadevent.showtype === 'l' || this.loadevent.showtype === 's') { 
                    if (this.loadevent.shows.length > 2) {
                        return this.cleanDate(this.loadevent.shows[0].date) + '-' + this.cleanDate(this.loadevent.shows[this.loadevent.shows.length-1].date)
                    } else {
                        return this.cleanDate(this.loadevent.shows[0].date)
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