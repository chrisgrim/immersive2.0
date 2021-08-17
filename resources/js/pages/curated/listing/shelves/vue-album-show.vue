<template>
    <div class="album four grid-image container">
        <div class="row">
            <div 
                v-for="(element) in elements"
                :key="element.featureable.id"
                class="col">
                <div class="card">
                    <a 
                        :href="url(element)" 
                        class="card-url" />
                    <ImageArray 
                        :community="community"
                        :element="element" />
                    <div class="card-body">
                        <p 
                            v-if="title" 
                            class="card-title">
                            {{ element.featureable.name }}
                        </p>
                        <p 
                            v-if="text"
                            class="card-text">
                            {{ element.featureable.blurb }}
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
    import ImageArray from './vue-album-images.vue'
    export default {

        props: ['value', 'title', 'text', 'link', 'edit', 'shelf', 'community'],

        components: { ImageArray },

        computed: {
            elements: {
                get() {
                    return this.value;
                },
                set(val) {
                    this.$emit('input', val);
                }
            },
        },

        data() {
            return {
                isDisabled: false,
            }
        },

        methods: {
            url(element) {
                if (element.type === 'e') { return `/events/${element.featureable.slug}` }
                if (element.type === 'l') { return `/communities/${this.community.slug}/${element.featureable.slug}` }
            }
        },

    }
</script>