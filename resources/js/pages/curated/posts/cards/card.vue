<template>
    <div 
        :class="{event: card.name}"
        class="body">
        <a 
            v-if="hasUrl"
            target="_blank" 
            rel="noopener noreferrer nofollow"
            :href="hasUrl"
            class="card-url" />
        <template v-if="hasImage">
            <div class="post__card-image">
                <picture>
                    <source 
                        type="image/webp" 
                        :srcset="`/storage/${hasImage}`"> 
                    <img 
                        :src="`/storage/${hasImage.slice(0, -4)}`" 
                        :alt="`${card.name}`">
                </picture>
            </div>
        </template>
        <template v-if="hasName">
            <div class="card-name">
                <h3>{{ hasName }}</h3>
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
        <template v-if="hasUrl">
            <div class="card-out">
                <a target="_blank" rel="noopener noreferrer nofollow" :href="hasUrl">
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

        computed: {
            hasImage() {
                if (this.card.type === 'i') { return this.card.thumbImagePath }
                if (this.card.type === 'h' || this.card.type === 't') { return }
                return this.card.type === 'e' && !this.card.thumbImagePath ? this.card.event.thumbImagePath : this.card.thumbImagePath
            },
            hasName() {
                return this.card.event && !this.card.name ? this.card.event.name : this.card.name
            },
            hasUrl() {
                return this.card.event && !this.card.url ?`/events/${this.card.event.slug}` : this.card.url
            }
        },

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