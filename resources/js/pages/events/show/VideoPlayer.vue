<template>
    <div class="youtube">
        <iframe 
            class="youtube-iframe" 
            :src="src"
            title="YouTube video player" 
            frameborder="0" 
            loading="lazy" 
            allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture"
            allowfullscreen />
    </div>
</template>

<script>
    export default {
        props: ['src', 'alt'],

        data() {
            return {
                valid: false,
                url: '',
            }
        },

        methods: {
        
        },
        mounted() {
            
        }
    }
</script>