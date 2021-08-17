<template>
    <div class="album simple container">
        <div class="row">
            <div 
                v-for="(element) in elements"
                :key="element.id"
                class="col">
                <div class="card">
                    <div class="card-img">
                        <div>
                            <picture>
                                <source 
                                    type="image/webp" 
                                    :srcset="`/storage/${element.thumbImagePath}`"> 
                                <img 
                                    style="object-fit:cover" 
                                    loading="lazy" 
                                    :src="`/storage/${element.thumbImagePath.slice(0, -4)}jpg`" 
                                    :alt="`${element.name}`">
                            </picture>
                        </div>
                    </div>
                    <div class="card-body">
                        <p 
                            v-if="title" 
                            class="card-title">
                            {{ element.name }}
                        </p>
                        <p 
                            v-if="text"
                            class="card-text">
                            {{ element.description }}
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
    export default {

        props: ['elements', 'title', 'text', 'link'],

        data() {
            return {
                isDisabled: false,
            }
        },

        methods: {

        },

    }
</script>