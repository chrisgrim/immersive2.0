<template>
    <div>
        <a 
            v-if="card.url"
            target="_blank" 
            rel="noopener noreferrer nofollow"
            :href="card.url"
            class="card-url" />
        <template v-if="card.thumbImagePath">
            <div class="listing__card-image">
                <picture>
                    <source 
                        type="image/webp" 
                        :srcset="`/storage/${card.thumbImagePath}`"> 
                    <img 
                        :src="`/storage/${card.thumbImagePath.slice(0, -4)}`" 
                        :alt="`${card.name}`">
                </picture>
            </div>
        </template>
        <template v-if="card.name">
            <div class="card-name">
                <h2>{{ card.name }}</h2>
            </div>
        </template>
        <template v-if="card.blurb">
            <div class="card-blurb">
                <div v-html="card.blurb" />
            </div>
        </template>
        <template v-if="card.url">
            <a target="_blank" rel="noopener noreferrer nofollow" :href="card.url">
                <button class="black">Check it out</button>
            </a>
        </template>
    </div>
</template>

<script>
    export default {
        props: [ 'card', 'owner' ],

        data() {
            return {
            }
        },

        methods: {

        },

    }
</script>