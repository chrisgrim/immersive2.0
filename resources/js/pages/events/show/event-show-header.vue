<template>
    <header class="min-h-[200px] relative w-full m-auto p-0 md:px-12 lg:px-32 lg:max-w-screen-xl">
        <!--  -->
        <!-- For mobile -->
        <nav v-if="mobile">
            <button 
                aria-label="back button"
                type="button"
                @click="onBack"
                class="absolute flex top-8 left-8 z-10 p-1 rounded-full shadow-lg bg-white">
                <svg class="h-12 w-12">
                    <use :xlink:href="`/storage/website-files/icons.svg#ri-arrow-left-s-line`" />
                </svg>
            </button>
            <favorite 
                :mobile="mobile"
                :event="event" 
                :user="user" />
        </nav>

        <!-- For desktop -->
        <nav v-else>
            <div class="py-6 flex items-center justify-between">
                <p class="text-xl">
                    <a :href="`/`" class="underline">Everything Immersive</a> > <a :href="`/index/search-all?&category=${event.category.id}`" class="underline">{{event.category.name}}</a> > {{event.name}}
                </p>
                <favorite 
                    :mobile="mobile"
                    :user="user" 
                    :event="event" />
            </div> 
        </nav>
        <picture>
            <source 
                type="image/webp" 
                :srcset="`/storage/${mobile ? event.thumbImagePath : event.largeImagePath}`"> 
            <img 
                class="min-h-[200px] h-[43vh] w-full object-cover md:rounded-xl md:h-[40rem]"
                :src="`/storage/${mobile ? event.thumbImagePath.slice(0, -4) : event.largeImagePath.slice(0, -4)}jpg`" 
                :alt="`${event.name} Immersive Event`">
        </picture>
    </header>
</template>

<script>
    import Favorite from './Components/event-favorite'
    export default {
        
        props: [ 'event', 'user', 'mobile' ],

        components: { Favorite },

        data() {
            return {
                userCanEdit:  true
            }
        },

        methods: {
            onBack() {
                document.referrer == "" ? window.location.href = '/' : window.history.back()
            }
        }

    }
</script>