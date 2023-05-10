<template>
    <div class="relative w-full pb-[56.25%]">
        <iframe 
            v-if="defer"
            class="w-full absolute top-0 left-0 h-full rounded-lg" 
            :src="defer"
            :data-src="url"
            title="YouTube video player" 
            frameborder="0" 
            loading="lazy"
            allow="accelerometer; clipboard-write; encrypted-media; gyroscope; picture-in-picture;"
            allowfullscreen />
        <div
            @click="loadYoutube"
            class="w-full absolute top-0 left-0 h-full rounded-lg cursor-pointer" 
            v-else>
            <img 
                class="w-full h-full object-cover" 
                :src="`https://img.youtube.com/vi/${src}/sddefault.jpg`" 
                alt="">
        </div>
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
            handleScroll () {
                this.defer = this.url
                window.removeEventListener('scroll', this.handleScroll);
            }
        },
        created () {
            window.addEventListener('scroll', this.handleScroll);
        },
        unmounted () {
            window.removeEventListener('scroll', this.handleScroll);
        },
    }
</script>