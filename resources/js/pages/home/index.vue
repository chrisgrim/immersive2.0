<template>
    <div class="homepage">
        <template v-if="mobile">
            <MobileSearchNav
                :tags="tags" 
                :categories="categories" />
        </template>
        <div>
            <section 
                class="section-b" 
                style="background: url('/storage/website-files/header-image.jpg') center center / cover no-repeat;height:53vh;">
                <div class="intro">
                    <div class="name">
                        <h3> Welcome to immersive theater</h3>
                    </div>
                </div>
            </section>

            <template v-if="community && community.name && shelvesWithListings[0]">
                <section class="section-a">
                    <div class="section-a__wrapper">
                        <TopShelf 
                            :community="community"
                            :shelf="shelvesWithListings[0]" />
                    </div>
                </section>
            </template>

            <template v-if="community && community.name">
                <section class="section-a">
                    <div class="section-a__wrapper">
                        <div class="header-a">
                            <div class="header-a__content">
                                <div class="header-a__wrapper">
                                    <div class="header-a__name">
                                        <h2>{{ community.name }}</h2>
                                    </div>
                                    <div class="header-a__blurb">
                                        <p>{{ community.blurb }}</p>
                                    </div>
                                    <a :href="`/communities/${community.slug}`">
                                        <button>
                                            Check it out
                                        </button>
                                    </a>
                                </div>
                            </div>
                            <div class="header-a__image">
                                <picture>
                                    <source 
                                        type="image/webp" 
                                        :srcset="`/storage/${community.thumbImagePath}`"> 
                                    <img 
                                        :src="`/storage/${community.thumbImagePath.slice(0, -4)}jpg`" 
                                        :alt="`${community.name} Community`">
                                </picture>
                            </div>
                        </div>
                    </div>
                </section>
            </template>

            <template v-if="community && community.name && shelvesWithListings[1]">
                <section class="section-a">
                    <div class="section-a__wrapper">
                        <BottomShelf 
                            :community="community"
                            :shelf="shelvesWithListings[1]" />
                    </div>
                </section>
            </template>

            <section 
                id="staffpicks" 
                class="section-a" 
                v-if="staffpicks.length">
                <StaffPicks :staffpicks="staffpicks" />
            </section>

            <section 
                id="partners" 
                class="section-a">
                <div class="section-a__wrapper">
                    <Partners />
                </div>
            </section>

            <section class="section-a">
                <div>
                    <h3>Read The 2020 Immersive Entertainment Industry Annual Report</h3>
                    <p>Discover The Strength of Immersive Entertainment!</p>
                    <br>
                    <p><a href="/storage/website-files/documents/2020 Immersive Entertainment Industry Annual Report.pdf"><button class="black">Check out the report here</button></a></p>
                </div>
            </section>
        </div>
    </div>
</template>

<script>
    import TopShelf from './sections/top-section.vue'
    import BottomShelf from './sections/bottom-section.vue'
    import Partners from './sections/partners.vue'
    import StaffPicks from './sections/staffpicks.vue'
    import MobileSearchNav  from './sections/mobile-search-nav.vue'

    export default {

        props:['categories', 'staffpicks', 'dock', 'tags'],

        components: { TopShelf, BottomShelf, Partners, StaffPicks, MobileSearchNav },

        computed: {
            shelvesWithListings() {
                return this.community && this.community.name ? this.community.limited_shelves.filter( shelf => shelf.public_listings_with_cards.length) : ''
            },
            community() {
                return this.dock.featured ? this.dock.featured[0].featureable: null
            }
        },

        data() {
            return {
                mobile: window.innerWidth < 768,
            }
        },

        methods: {

        },

    };
</script>