<template>
    <div>
        <h2>Organizer Approval</h2>
        <p>These are the organizers that need approval</p>
        <section>
            <Album :items="organizers" />
        </section>
    </div>
</template>

<script>
    import Album from '../components/albums.vue'
    export default {
        
        components: { Album },

        props: ['organizers', 'user'],

    };
</script>