<template>
    <div>
        <h2>Staff Picks</h2>
        <p>Our experienced staff is dedicated to finding you the best shows and events available.</p>
        <div>
            <a href="/staffpicks/current">
                <button>
                    See all
                </button>
            </a>
        </div>
        <div class="album four image container">
            <div class="row">
                <div 
                    v-for="(card) in staffpicks"
                    :key="card.id" 
                    class="col">
                    <div class="card">
                        <a 
                            :href="`/events/${card.event.slug}`" 
                            class="card-url" />
                        <div class="card-img single">
                            <picture v-if="card && card.event.thumbImagePath">
                                <source 
                                    type="image/webp" 
                                    :srcset="`/storage/${card.event.thumbImagePath}`"> 
                                <img 
                                    style="object-fit:cover" 
                                    loading="lazy" 
                                    :src="`/storage/${card.event.thumbImagePath.slice(0, -4)}jpg`" 
                                    :alt="`${card.event.name}`">
                            </picture>
                        </div>
                        <div class="card-body">
                            <div class="name">
                                <p>{{ card.event.name }}</p>
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

        },

        data() {
            return {
            }
        },

        methods: {
        },

    };
</script>