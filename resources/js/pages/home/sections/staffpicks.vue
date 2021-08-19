<template>
    <div>
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
    </div>
</template>

<script>

    export default {

        props:['staffpicks'],

        computed: {
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
                staffwidth: window.innerWidth > 768 ? 100 : 90,
                hover: '',
                hoverArray: [],
                hoveredIndex: null,
            }
        },

        methods: {


        },

        watch: {
            hover() {
                return this.hoverArray = [this.hover - 1, this.hover + 1]
            },
        },
    };
</script>