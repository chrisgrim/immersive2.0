<template>
    <div>
        <a 
            aria-label="Visit Listing"
            v-if="hasUrl"
            :rel="internalUrl"
            :href="hasUrl"
            :class="[ hasImage ? 'h-80 md:h-[42rem]' : 'h-full' ]"
            class="w-full absolute rounded-xl z-10 top-0 left-0 block" />
        <template v-if="hasImage">
            <div class="h-80 relative rounded-2xl overflow-hidden md:h-[42rem]">
                <picture>
                    <source 
                        type="image/webp" 
                        :srcset="`/storage/${hasImage}`"> 
                    <img 
                        loading="lazy"
                        class="w-full rounded-2xl align-bottom object-cover h-full"
                        :src="`/storage/${hasImage.slice(0, -4)}`" 
                        :alt="`${card.name}`">
                </picture>
            </div>
        </template>
        <template v-if="hasName">
            <h3 class="mt-8 text-4xl">{{ hasName }}</h3>
        </template>
        <template v-if="card.event_id">
            <p class="mt-4 text-1xl"> Booking Through: {{ cleanDate(card.event.closingDate) }} </p>
        </template>
        <template v-if="card.blurb">
            <p 
                class="mt-4" 
                v-html="card.blurb" />
        </template>
        <template v-if="hasUrl">
            <div class="mt-8 mb-16">
                <a :rel="internalUrl" :href="hasUrl">
                    <button class="px-4 py-2 text-white bg-black border-white inline-block rounded-full hover:bg-white hover:text-black hover:border-black z-50">Check it out</button>
                </a>
            </div>
        </template>
    </div>
</template>

<script>
    export default {
        props: [ 'card', 'mobile' ],

        computed: {
            hasImage() {
                if (this.card.type === 'i') { return this.getImage() }
                if (this.card.type === 'h' || this.card.type === 't') { return }
                return this.card.type === 'e' && !this.card.thumbImagePath ? this.getEventImage() : this.getImage()
            },
            hasName() {
                return this.card.event && !this.card.name ? this.card.event.name : this.card.name
            },
            hasUrl() {
                return this.card.event && !this.card.url ?`/events/${this.card.event.slug}` : this.card.url
            },
            internalUrl() {
                return this.card.event && !this.card.url ? '' : 'noopener noreferrer nofollow'
            }
        },

        data() {
            return {
            }
        },

        methods: {
            getImage() {
                return this.mobile ? this.card.thumbImagePath : this.card.largeImagePath ? this.card.largeImagePath : this.card.thumbImagePath
            },
            getEventImage() {
                return this.mobile ? this.card.event.thumbImagePath : this.card.event.largeImagePath
            },
            cleanDate(data) {
                return this.$dayjs(data).format("dddd, MMMM D YYYY");
            }
        },

    }
</script>