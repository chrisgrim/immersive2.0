<template>
    <div class="homepage">
        <div>
            <!-- <header 
                class="padded event-index__header" 
                style="background: url('/storage/website-files/header-image.jpg') center center / cover no-repeat;">
                <div class="event-index__header--content">
                    <div class="event-index__header--title">
                        <h3>Discover immersive experiences...</h3>
                    </div>
                    <div class="index-nav-search">
                        <vue-nav-search page="homepage" />
                    </div>
                </div>
            </header> -->

            <section class="section-a">
                <div class="section-a__wrapper">
                    <div 
                        class="shelves" 
                        v-for="(section) in sectionsWithListings"
                        :key="section.id">
                        <TopShelf 
                            :community="community"
                            :section="section" />
                    </div>
                </div>
            </section>

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
                            </div>
                        </div>
                        <div class="header-a__image">
                            <picture>
                                <source 
                                    type="image/webp" 
                                    :srcset="`/storage/${headerImage}`"> 
                                <img 
                                    :src="`/storage/${headerImage.slice(0, -4)}jpg`" 
                                    :alt="`${community.name} Community`">
                            </picture>
                        </div>
                    </div>
                </div>
            </section>

            <section class="section-a">
                <div class="section-a__wrapper">
                    <div 
                        class="shelves" 
                        v-for="(section) in sectionsWithListings"
                        :key="section.id">
                        <BottomShelf 
                            :community="community"
                            :section="section" />
                    </div>
                </div>
            </section>


            <section 
                id="latestevents" 
                class="section-a" 
                v-cloak>
                <div class="section-a__wrapper">
                    <div 
                        class="event-index__eventlist" 
                        ref="list">
                        <div class="event-index__eventlist--middle vertical">
                            <div 
                                class="eventlist__element" 
                                :style="`width:${width}`">
                                <div class="card index">
                                    <div 
                                        itemprop="itemListElement" 
                                        itemscope="" 
                                        itemtype="http://schema.org/ListItem">
                                        <a 
                                            href="/index/search-online" 
                                            class="card-url" />
                                        <div class="card-image__top index">
                                            <div 
                                                class="card-image__middle" 
                                                style="padding-top: 65%;">
                                                <div class="card-image">
                                                    <picture>
                                                        <source 
                                                            type="image/webp" 
                                                            srcset="/storage/website-files/online-events.jpg"> 
                                                        <img 
                                                            style="object-fit:cover" 
                                                            loading="lazy" 
                                                            class="card-image__img index" 
                                                            src="/storage/website-files/online-events.jpg" 
                                                            alt="Online Events showing on Everything Immersive">
                                                    </picture>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="card-content index">
                                            <div class="card-title">
                                                <h3>Online Only</h3>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div 
                                class="eventlist__element" 
                                :style="`width:${width}`">
                                <div class="card index">
                                    <div 
                                        itemprop="itemListElement" 
                                        itemscope="" 
                                        itemtype="http://schema.org/ListItem">
                                        <a
                                            href="/index/search-online?tag=horror&id=8" 
                                            class="card-url" />
                                        <div class="card-image__top index">
                                            <div 
                                                lass="card-image__middle" 
                                                style="padding-top: 65%;">
                                                <div class="card-image">
                                                    <picture>
                                                        <source 
                                                            type="image/webp" 
                                                            srcset="/storage/website-files/horror.jpg"> 
                                                        <img 
                                                            style="object-fit:cover" 
                                                            loading="lazy" 
                                                            class="card-image__img index" 
                                                            src="/storage/website-files/horror.jpg" 
                                                            alt="Online Events showing on Everything Immersive">
                                                    </picture>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="card-content index">
                                            <div class="card-title">
                                                <h3>Horror</h3>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div 
                                class="eventlist__element" 
                                :style="`width:${width}`">
                                <div class="card index">
                                    <div 
                                        itemprop="itemListElement" 
                                        itemscope="" 
                                        itemtype="http://schema.org/ListItem">
                                        <a 
                                            href="/index/search-all" 
                                            class="card-url" />
                                        <div class="card-image__top index">
                                            <div 
                                                class="card-image__middle" 
                                                style="padding-top: 65%;">
                                                <div class="card-image">
                                                    <picture>
                                                        <source 
                                                            type="image/webp" 
                                                            srcset="/storage/website-files/latest-events.jpg"> 
                                                        <img 
                                                            style="object-fit:cover" 
                                                            loading="lazy" 
                                                            class="card-image__img index" 
                                                            src="/storage/website-files/latest-events.jpg" 
                                                            alt="Online Events showing on Everything Immersive">
                                                    </picture>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="card-content index">
                                            <div class="card-title">
                                                <h3>All Events</h3>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </section>

            <section 
                id="staffpicks" 
                class="section-a" 
                v-if="staffpicks.length">
                <div class="padded-width">
                    <div class="banner-title__staffpicks">
                        <div style="display:inline-block;">
                            <h3>Staff Picks</h3>
                            <p>Our experienced staff is dedicated to finding you the best shows and events available.</p>
                        </div>
                        <div class="staffpicks__selection__button">
                            <a href="/staffpicks/current">
                                <button class="staffpicks__selection--loadmore">
                                    See all
                                </button>
                            </a>
                        </div>
                    </div>
                    <div 
                        class="event-index__eventlist" 
                        ref="list">
                        <div class="event-index__eventlist--middle vertical staff">
                            <div 
                                v-for="(pick, index) in staffpicks"
                                :key="pick.id" 
                                class="eventlist__element staff"
                                :class="{
                                    back: hoveredIndicesBack.includes(index),
                                    front: hoveredIndicesFront.includes(index),
                                    current: index === hoveredIndex
                                }"
                                @mouseenter="hoveredIndex = index"
                                @mouseleave="hoveredIndex = null"
                                :style="`width:${staffwidth}%`">
                                <div class="card black staff">
                                    <a :href="`/events/${pick.event.slug}`">
                                        <div 
                                            class="card-image__top staff" 
                                            :style="`height:${staffHeight}rem`">
                                            <div class="card-image__middle staff">
                                                <div class="card-image">
                                                    <picture>
                                                        <source 
                                                            type="image/webp" 
                                                            :srcset="`/storage/${pick.event.thumbImagePath}`"> 
                                                        <img 
                                                            style="object-fit:cover" 
                                                            loading="lazy" 
                                                            class="card-image__img staff" 
                                                            :src="`/storage/${pick.event.thumbImagePath.slice(0, -4)}jpg`" 
                                                            :alt="`${pick.event.name} Immersive Event`">
                                                    </picture>
                                                </div>
                                            </div>
                                        </div>
                                    </a>
                                    <div class="card-content staff">
                                        <a :href="`/events/${pick.event.slug}`">
                                            <div class="event-index__staffpicks--title">
                                                <h3 class="black">
                                                    {{ pick.event.name }}
                                                </h3>
                                            </div>
                                        </a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </section>

            <section 
                id="partners" 
                class="section-a">
                <div class="section-a__wrapper">
                    <h3>Check out our partners</h3>
                    <div class="album four container">
                        <div class="row">
                            <div class="col">
                                <div class="card">
                                    <a 
                                        target="_blank" 
                                        rel="noopener noreferrer" 
                                        href="https://www.argn.com/">
                                        <button 
                                            style="background: url('/storage/website-files/argn-logo.jpg') center center / cover no-repeat;" 
                                            class="contributer--button" />
                                    </a>
                                </div>
                            </div>
                            <div class="col">
                                <div class="card">
                                    <a 
                                        target="_blank" 
                                        rel="noopener noreferrer" 
                                        href="https://roomescapeartist.com/">
                                        <button 
                                            style="background: url('/storage/website-files/rea-logo.png') center center / cover no-repeat;" 
                                            class="contributer--button" />
                                    </a>
                                </div>
                            </div>
                            <div class="col">
                                <div class="card">
                                    <a 
                                        target="_blank" 
                                        rel="noopener noreferrer" 
                                        href="https://noproscenium.com/">
                                        <button 
                                            style="background: url('/storage/website-files/nopro-logo.jpg') center center / cover no-repeat;" 
                                            class="contributer--button" />
                                    </a>
                                </div>
                            </div>
                            <div class="col">
                                <div class="card">
                                    <a 
                                        target="_blank" 
                                        rel="noopener noreferrer" 
                                        href="https://www.herefest.com/">
                                        <button 
                                            style="background: url('/storage/website-files/here-logo.png') center center / cover no-repeat;" 
                                            class="contributer--button" />
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </section>

            <section class="section-a">
                <div class="header-title__announcement">
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
    import ShowMore  from '../events/components/show-more.vue'
    import vueNavSearch  from '../layouts/nav-search.vue'
    import TopShelf from './sections/top-section.vue'
    import BottomShelf from './sections/bottom-section.vue'

    export default {

        props:['categories', 'staffpicks', 'community'],

        components: { ShowMore, vueNavSearch, TopShelf, BottomShelf },

        computed: {
            sectionsWithListings() {
                return this.community.sections.filter( section => section.public_featured.length)
            },
            user () {
                return this.$store.state.user ? this.$store.state.user : ''
            },
            staffHeight() {
                if (this.mobile) {return;}
                if (this.staffpicks.length > 2) {
                    return '25';
                }
                if (this.staffpicks.length == '2') {
                    return '40';
                }
                return '50';
            },

            isHovered() {
                return this.hoveredIndex !== null;
            },

            hoveredIndicesBack() {
                if (! this.isHovered) { return [];}
                return [this.hoveredIndex - 1];
            },

            hoveredIndicesFront() {
                if (! this.isHovered) { return [];}
                return [this.hoveredIndex + 1];
            },
        },

        data() {
            return {
                location: [],
                mobile: window.innerWidth < 768 ? true : false,
                translateStaff: 0,
                translateCat: 0,
                staffwidth: window.innerWidth > 768 ? 100 : 90,
                width: window.innerWidth > 768 ? '33%' : '90%',
                hover: '',
                hoverArray: [],
                hoveredIndex: null,
                headerImage: window.innerWidth < 768 ? this.community.thumbImagePath : this.community.largeImagePath,
            }
        },

        methods: {
            showMore(name){
                name == 'cat' && this.translateCat != -200 ? this.translateCat -= 100 : '';
                name == 'staff' && this.translateStaff != (this.staffpicks.length -1) * -100? this.translateStaff -= 100 : '';
            },
            showLess(name){
                name == 'cat' && this.translateCat != 0 ? this.translateCat += 100 : '';
                name == 'staff' && this.translateStaff != 0 ? this.translateStaff += 100 : '';
            },

        },

        watch: {
            hover() {
                return this.hoverArray = [this.hover - 1, this.hover + 1]
            },
        },
    };
</script>