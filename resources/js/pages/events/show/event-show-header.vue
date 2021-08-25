<template>
    <header class="lay-b">
        <nav 
            v-if="isMobile"
            class="es__mobile-nav">
            <a 
                class="es__mobile-nav--back"
                @click="goBack()">
                <div class="es__mobile-nav--button">
                    <svg 
                        aria-label="Back" 
                        role="img" 
                        viewBox="0 0 32 32" 
                        xmlns="http://www.w3.org/2000/svg" 
                        style="display: block; fill: none; height: 16px; width: 16px; stroke: currentcolor; stroke-width: 4; overflow: visible;">
                        <g fill="none">
                            <path d="m20 28-11.29289322-11.2928932c-.39052429-.3905243-.39052429-1.0236893 0-1.4142136l11.29289322-11.2928932" />
                        </g>
                    </svg>
                </div>
            </a>
            <favorite 
                :inputclass="showEventMobileClass" 
                :event="event" />
        </nav>
        <div class="es__header--content">
            <div class="es__header--image">   
                <picture>
                    <source 
                        type="image/webp" 
                        :srcset="`/storage/${isMobile ? event.thumbImagePath : event.largeImagePath}`"> 
                    <img 
                        :src="`/storage/${isMobile ? event.thumbImagePath.slice(0, -4) : event.largeImagePath.slice(0, -4)}jpg`" 
                        :alt="`${event.name} Immersive Event`">
                </picture>
            </div>
            <div v-if="!isMobile">
                <div 
                    v-if="user && user.type == 'a'" 
                    class="es__admin--edit">
                    <a :href="`/create/${event.slug}/title`">
                        <p>Edit</p>
                    </a>
                </div>
                <favorite 
                    :inputclass="showEventClass" 
                    :event="event" />
            </div>
        </div>
    </header>
</template>

<script>
    export default {
        
        props: [ 'event', 'user' ],

        computed: {

        },

        data() {
            return {
                showEventClass: 'es__favorite',
                showEventMobileClass: 'es__favorite--mobile',
                isMobile: window.innerWidth < 768 ? true : false,
            }
        },

        methods: {
            goBack() {
                window.history.back();
            }
        }

    }
</script>