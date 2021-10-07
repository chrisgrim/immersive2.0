<template>
    <div>
        <template v-if="mobile">
            <MobileSearchNav
                :tags="tags" 
                :categories="categories" />
            <div style="height:8rem;" />
        </template>
        <template v-if="docks && docks.length">
            <div 
                :key="dock.id"
                v-for="dock in docks">
                <template v-if="dock.type === 'h'">
                    <Hero :dock="dock" />
                </template>
                <template v-if="dock.type === 'i'">
                    <Icons :dock="dock" />
                </template>
                <template v-if="dock.type === 'f'">
                    <Album 
                        number="four"
                        :dock="dock" />
                </template>
                <template v-if="dock.type === 't'">
                    <Album 
                        number="three"
                        :dock="dock" />
                </template>
                <template v-if="dock.type === 's'">
                    <Spotlight :dock="dock" />
                </template>
            </div>
        </template>
        <div>
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
                    <div class="annual-report">
                        <h3>Read The 2020 Immersive Entertainment Industry Annual Report</h3>
                        <p>Discover The Strength of Immersive Entertainment!</p>
                        <br>
                        <a href="/storage/website-files/documents/2020 Immersive Entertainment Industry Annual Report.pdf"><button class="black">Check out the report here</button></a>
                    </div>
                </div>
            </section>
        </div>
    </div>
</template>

<script>
    import Hero from './sections/hero-section.vue'
    import Icons from './sections/icons-section.vue'
    import Album from './sections/album-section.vue'
    import Spotlight from './sections/spotlight-section.vue'
    import Partners from './sections/partners.vue'
    import StaffPicks from './sections/staffpicks.vue'
    import MobileSearchNav  from './sections/home-mobile-search-nav.vue'

    export default {

        props:['categories', 'staffpicks', 'docks', 'tags'],

        components: { Album, Partners, StaffPicks, MobileSearchNav, Icons, Hero, Spotlight },

        computed: {

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