<template>
    <div 
        :class="{event: card.name}"
        class="body">
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
                <h3>{{ card.name }}</h3>
            </div>
        </template>
        <template v-if="card.event_id">
            <div class="card-blurb">
                <p> Closing Date: {{ cleanDate(card.event.closingDate) }} </p>
            </div>
        </template>
        <template v-if="card.blurb">
            <div class="card-blurb">
                <p v-html="card.blurb" />
            </div>
        </template>
        <template v-if="card.url">
            <div class="card-out">
                <a target="_blank" rel="noopener noreferrer nofollow" :href="card.url">
                    <button class="black">Check it out</button>
                </a>
            </div>
        </template>
        <template v-if="card.name">
            <div class="seperator">
                <span class="a" />
                <span class="a" />
                <span class="a b" />
            </div>
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
            cleanDate(data) {
                return this.$dayjs(data).format("dddd, MMMM D YYYY");
            }
        },

    }
</script>