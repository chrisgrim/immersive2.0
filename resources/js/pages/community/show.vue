<template>
    <div class="community-show">
        <div class="com-header">
            <div class="com-image">
                <picture>
                    <source 
                        type="image/webp" 
                        :srcset="`/storage/${headerImage}`"> 
                    <img 
                        :src="`/storage/${headerImage.slice(0, -4)}`" 
                        :alt="`${community.name} Community`">
                </picture>
            </div>
        </div>
        <div class="com-intro">
            <div class="com-content">
                <div class="com-blurb">
                    <tiptap 
                        v-model="community.blurb"
                        :community="community" />
                </div>
            </div>
            <div class="com-curators">
                <h4>Meet your curators</h4>
                <div 
                    v-for="curator in community.curators"
                    :key="curator.id"
                    class="curator">
                    <template v-if="curator && curator.thumbImagePath">
                        <picture>
                            <source 
                                type="image/webp" 
                                :srcset="`/storage/${curator.thumbImagePath}`"> 
                            <img 
                                :src="`/storage/${curator.thumbImagePath.slice(0, -4)}`" 
                                :alt="`${curator.name} Community`">
                        </picture>
                    </template>
                    {{ curator.name}}
                </div>
            </div>
        </div>
        <div class="listings">
            <template v-if="community && community.listings && community.listings.length">
                <VueCards
                    :cards="community.listings"/>
            </template>
        </div>
    </div>
</template>

<script>
    import VueCards  from './card-index.vue'
    import Tiptap from '../../components/Tiptap.vue'
    export default {
        
        props: [ 'value', 'user' ],

        components: { VueCards, Tiptap },

        computed: {

        },

        data() {
            return {
                community: this.value,
                headerImage: window.innerWidth < 768 ? this.value.thumbImagePath : this.value.largeImagePath,
                content: `<h2>${this.value.name} community! 🎉</h2><p>Go ahead and edit this text to say whatever you want!</p>`,
            }
        },

        methods: {
            
        }

    }
</script>