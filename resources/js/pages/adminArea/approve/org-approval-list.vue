<template>
    <div class="events">
        <div class="create-title">
            <h2>Organizer Approval</h2>
            <p>These are the organizers that need approval</p>
        </div>
        
        <section>
            <Album :items="organizers" />
        </section>
    </div>
</template>

<script>
    import Album from './Components/albums.vue'
    export default {
        
        components: { Album },

        props: ['organizers', 'user'],

    };
</script>