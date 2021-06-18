<template>
    <div class="com-card__grid">
        <div 
            v-for="(card) in cards"
            :key="card.id"
            class="com-card">
            <a :href="`/listings/${card.slug}`">
                <div class="com-card-wrapper">
                    <div style="padding-top:43.08%;border:1.5px solid rgba(0, 0, 0, 0.08);border-bottom:none;border-radius:16px 16px 0px 0px;" />
                    <div class="com-card-text">
                        <h4>{{card.name}}</h4>
                    </div>
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
                </div>
            </a>
        </div>
    </div>
</template>

<script>
    export default {
        props:['cards'],

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