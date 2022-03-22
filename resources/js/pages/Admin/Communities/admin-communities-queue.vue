<template>
    <div>
        <h2>Community Approval</h2>
        <p>These are the communities that need approval</p>
        <section>
            <Album :items="value.data" />
        </section>
    </div>
</template>

<script>
    import Album from '../components/albums.vue'
    export default {
        components: { Album },
        props:[ 'value' ],
    };
</script>