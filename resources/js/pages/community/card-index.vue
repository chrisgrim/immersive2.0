<template>
    <div class="com-card__grid">
        <div 
            v-for="(card) in cards"
            :key="card.id"
            class="com-card">
            <div class="com-card-wrapper">
                <a :href="`/communities/${community.slug}/${card.slug}`">
                    <div class="com-card-image">
                        <template v-if="card && card.thumbImagePath">
                            <picture>
                                <source 
                                    type="image/webp" 
                                    :srcset="`/storage/${card.thumbImagePath}`"> 
                                <img 
                                    :src="`/storage/${card.thumbImagePath.slice(0, -4)}`" 
                                    :alt="`${card.name}`">
                            </picture>
                        </template>
                    </div>
                    <div class="com-card-text">
                        <h4>{{card.name}}</h4>
                    </div>
                </a>
            </div>
        </div>
    </div>
</template>

<script>
    export default {
        props:['cards', 'community'],

        computed: {

        },

        data() {
            return {
            }
        },

        methods: {
          
        },


    };
</script>