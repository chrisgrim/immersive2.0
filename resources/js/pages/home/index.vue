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

            <template v-if="hasDock1()">
                <section class="section-a">
                    <div class="section-a__wrapper">
                        <Locations :shelf="dock1.featured[0].featureable" />
                    </div>
                </section>
            </template>

            <template v-if="hasDock2()">
                <section class="section-a">
                    <div class="section-a__wrapper">
                        <TopShelf :shelf="dock2.featured[0].featureable" />
                    </div>
                </section>
            </template>

            <template v-if="hasDock3()">
                <section class="section-a">
                    <div class="section-a__wrapper">
                        <div class="header-a">
                            <div class="header-a__content">
                                <div class="header-a__wrapper">
                                    <div class="header-a__name">
                                        <h2>{{ dock3.featured[0].featureable.name }}</h2>
                                    </div>
                                    <div class="header-a__blurb">
                                        <p>{{ dock3.featured[0].featureable.blurb }}</p>
                                    </div>
                                    <a :href="`/communities/${dock3.featured[0].featureable.slug}`">
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
                                        :srcset="`/storage/${dock3.featured[0].featureable.thumbImagePath}`"> 
                                    <img 
                                        :src="`/storage/${dock3.featured[0].featureable.thumbImagePath.slice(0, -4)}jpg`" 
                                        :alt="`${dock3.featured[0].featureable.name} Community`">
                                </picture>
                            </div>
                        </div>
                    </div>
                </section>
            </template>

            <template v-if="hasDock4()">
                <section class="section-a">
                    <div class="section-a__wrapper">
                        <BottomShelf :shelf="dock4.featured[0].featureable" />
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
                <div class="section-a__wrapper">
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
    import Locations from './sections/locations-section.vue'
    import TopShelf from './sections/top-section.vue'
    import BottomShelf from './sections/bottom-section.vue'
    import Partners from './sections/partners.vue'
    import StaffPicks from './sections/staffpicks.vue'
    import MobileSearchNav  from './sections/mobile-search-nav.vue'

    export default {

        props:['categories', 'staffpicks', 'dock1', 'dock2', 'dock3', 'dock4', 'tags'],

        components: { TopShelf, BottomShelf, Partners, StaffPicks, MobileSearchNav, Locations },

        computed: {

        },

        data() {
            return {
                mobile: window.innerWidth < 768,
            }
        },

        methods: {
            hasDock1() {
                return this.dock1 && this.dock1.featured[0] && this.dock1.featured[0].featureable
            },
            hasDock2() {
                return this.dock2 && this.dock2.featured[0] && this.dock2.featured[0].featureable
            },
            hasDock3() {
                return this.dock3 && this.dock3.featured[0] && this.dock3.featured[0].featureable
            },
            hasDock4() {
                return this.dock4 && this.dock4.featured[0] && this.dock4.featured[0].featureable
            }
        },

    };
</script>