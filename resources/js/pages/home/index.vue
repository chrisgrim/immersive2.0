<template>
    <div class="homepage">
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

            <template v-if="community && community.name && sectionsWithListings[0]">
                <section class="section-a">
                    <div class="section-a__wrapper">
                        <TopShelf 
                            :community="community"
                            :section="sectionsWithListings[0]" />
                    </div>
                </section>
            </template>

            <template v-if="community && community.name">
                <section class="section-a">
                    <div class="section-a__wrapper">
                        <h2>Discover More</h2>
                        <div class="header-a">
                            <div class="header-a__content">
                                <div class="header-a__wrapper">
                                    <div class="header-a__name">
                                        <h2>{{ community.name }}</h2>
                                    </div>
                                    <div class="header-a__blurb">
                                        <p>{{ community.blurb }}</p>
                                    </div>
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

            <template v-if="community && community.name">
                <section class="section-a">
                    <div class="section-a__wrapper">
                        <BottomShelf 
                            :community="community"
                            :section="sectionsWithListings[1]" />
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
    import SearchBar  from '../../components/search-bars/search-all.vue'
    import TopShelf from './sections/top-section.vue'
    import BottomShelf from './sections/bottom-section.vue'
    import Partners from './sections/partners.vue'
    import StaffPicks from './sections/staffpicks.vue'

    export default {

        props:['categories', 'staffpicks', 'community'],

        components: { SearchBar, TopShelf, BottomShelf, Partners, StaffPicks },

        computed: {
            sectionsWithListings() {
                return this.community && this.community.name ? this.community.sections.filter( section => section.public_featured.length) : ''
            },
        },

        data() {
            return {
            }
        },

        methods: {

        },

    };
</script>