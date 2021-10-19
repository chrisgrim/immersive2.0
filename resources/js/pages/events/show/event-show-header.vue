<template>
    <header class="lay-b">
        <nav 
            v-if="isMobile"
            class="es__mobile-nav">
            <button 
                aria-label="back button"
                type="button"
                @click="onBack"
                class="arrow svg">
                <svg>
                    <use :xlink:href="`/storage/website-files/icons.svg#ri-arrow-left-s-line`" />
                </svg>
            </button>
            <favorite 
                :inputclass="showEventMobileClass" 
                :event="event" />
        </nav>
        <div class="es__header--content">
            <template v-if="!isMobile">
                <div class="breadcrumbs">
                    <p>
                        <a :href="`/`">Everything Immersive</a> > <a :href="`/index/search-all?&category=${event.category.id}`">{{event.category.name}}</a> > {{event.name}}
                    </p>
                </div>
            </template>
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
            onBack() {
                document.referrer == "" ? window.location.href = '/' : window.history.back()
            }
        }

    }
</script>