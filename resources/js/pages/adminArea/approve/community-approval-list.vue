<template>
    <div class="events">
        <div class="create-title">
            <h2>Community Approval</h2>
            <p>These are the communities that need approval</p>
        </div>
        
        <section>
            <Album :items="value.data" />
        </section>
    </div>
</template>

<script>
    import Album from './Components/albums.vue'
    export default {

        components: { Album },

        props:[ 'value' ],



    };
</script>