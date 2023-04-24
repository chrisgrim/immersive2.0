<template>
    <div class="relative w-full pb-[56.25%]">
        <iframe 
            class="w-full absolute top-0 left-0 h-full rounded-lg" 
            :src="url"
            :data-src="url"
            title="YouTube video player" 
            frameborder="0" 
            loading="lazy"
            allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture;"
            allowfullscreen />
    </div>
</template>

<script>
    export default {
        props: ['src', 'alt'],

        data() {
            return {
                valid: false,
                url: `https://www.youtube.com/embed/${this.src}`,
                defer:'',
            }
        },

        methods: {
            loadYoutube() {
                this.defer = this.url
            }
        },
    }
</script>