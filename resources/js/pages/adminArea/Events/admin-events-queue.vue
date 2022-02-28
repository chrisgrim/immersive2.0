<template>
    <div>
        <h2>Event Approval</h2>
        <p>These are the events that need approval</p>
        <section>
            <Album :items="eventsLoaded" />
        </section>
    </div>
</template>

<script>
    import Album from '../components/albums.vue'
    export default {

        components: { Album },

        props:[ 'user' ],

        data() {
            return {
                eventsLoaded: null,
            }
        },

        methods: {
            onLoad() {
                axios.get(`/admin/events/finalize/fetch?timestamp=${new Date().getTime()}`)
                .then( res => { this.eventsLoaded = res.data })
            },
        },

        created() {
            this.onLoad()
        },

    };
</script>