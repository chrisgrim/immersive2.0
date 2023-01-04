<template>
    <section class="border-b border-slate-200 py-12 px-8 md:py-8 md:px-0">
        <div class="flex w-full p-4">
            <picture>
                <source 
                    type="image/webp" 
                    :srcset="`${envImageUrl}${event.staffpick.user.thumbImagePath}`"> 
                <img 
                    class="w-20 h-20 rounded-full mr-4" 
                    :src="`${envImageUrl}${event.staffpick.user.thumbImagePath.slice(0, -4)}jpg`" 
                    :alt="`${event.staffpick.user.name} Immersive Event`">
            </picture>
            <div>
                <h3>Staff Pick</h3>
                <i><p class="text-2xl text-gray-500">-{{ event.staffpick.user.name }}</p></i>
            </div>
        </div>
        <div class="px-8 md:px-0">
            <ShowMore 
                :blockquote="true"
                :text="event.staffpick.comments"
                :limit="100" />
        </div>
    </section>
</template>

<script>
    import ShowMore  from '../../../components/ShowMore.vue'
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