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
                    <div class="max-w-screen-2xl relative">
                        <Hero 
                            :mobile="mobile"
                            :dock="dock" />
                    </div>
                </template>
                <div class="max-w-screen-2xl relative h-full m-auto px-8 md:px-12 lg:px-32">
                    <Icons 
                        v-if="dock.type === 'i'"
                        :mobile="mobile"
                        :dock="dock" />
                    <Album 
                        v-if="dock.type === 'f'"
                        :mobile="mobile"
                        number="four"
                        :dock="dock" />
                    <Album 
                        v-if="dock.type === 't'"
                        :mobile="mobile"
                        number="three"
                        :dock="dock" />
                    <Spotlight 
                        v-if="dock.type === 's'"
                        :mobile="mobile"
                        :dock="dock" />
                </div>
            </div>
        </template>
        <div>
            <section 
                v-if="staffpicks.length"
                id="staffpicks" 
                class="max-w-screen-2xl relative h-full m-auto px-8 md:px-12 lg:px-32">
                <StaffPicks :staffpicks="staffpicks" />
            </section>

            <section 
                id="partners" 
                class="max-w-screen-2xl relative h-full m-auto px-8 md:px-12 lg:px-32">
                <div class="my-8 md:mt-16 md:mb-24">
                    <Partners />
                </div>
            </section>

            <section class="max-w-screen-2xl relative h-full m-auto px-8 md:px-12 lg:px-32">
                <div class="my-8 md:mt-16 md:mb-24">
                    <div class="flex flex-col items-center min-h-[26rem] justify-center">
                        <h3>Read The 2020 Immersive Entertainment Industry Annual Report</h3>
                        <p>Discover The Strength of Immersive Entertainment!</p>
                        <br>
                        <a href="/storage/website-files/documents/2020 Immersive Entertainment Industry Annual Report.pdf"><button class="p-4 rounded-full bg-black border-black text-white hover:text-black hover:bg-white">Check out the report here</button></a>
                    </div>
                </div>
            </section>
        </div>
    </div>
</template>

<script>
    import Hero from './Sections/hero-section.vue'
    import Icons from './Sections/icons-section.vue'
    import Album from './Sections/album-section.vue'
    import Spotlight from './Sections/spotlight-section.vue'
    import Partners from './Sections/partners.vue'
    import StaffPicks from './Sections/staffpicks.vue'
    import MobileSearchNav  from './Sections/home-mobile-search-nav.vue'

    export default {

        props:['categories', 'staffpicks', 'docks', 'tags', 'mobile'],

        components: { Album, Partners, StaffPicks, MobileSearchNav, Icons, Hero, Spotlight },

    };
</script>