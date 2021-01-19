<template>
    <section
        id="staffpick"
        class="es__staffpick pad4">
        <div class="es__staffpick--title">
            <picture>
                <source 
                    type="image/webp" 
                    :srcset="`/storage/${event.staffpick.user.thumbImagePath}`"> 
                <img 
                    :src="`/storage/${event.staffpick.user.thumbImagePath.slice(0, -4)}jpg`" 
                    :alt="`${event.staffpick.user.name} Immersive Event`">
            </picture>
            <div>
                <h3>Staff Pick</h3>
                <i><p>-{{ event.staffpick.user.name }}</p></i>
            </div>
        </div>
        <div class="es__staffpick--description">
            <ShowMore 
                :text="event.staffpick.comments"
                :limit="100" />
        </div>
    </section>
</template>

<script>
    import ShowMore  from '../components/show-more.vue'
    export default {

        props: [ 'event'],

        components: { ShowMore },

        data() {
            return {
                titleFontSize: '',
            }
        },

    }
</script>