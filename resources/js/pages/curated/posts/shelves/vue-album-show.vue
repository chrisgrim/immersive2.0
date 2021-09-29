<template>
    <div class="album four grid-image container">
        <div class="row">
            <div 
                v-for="(element) in elements"
                :key="element.id"
                class="col">
                <div class="card">
                    <a 
                        :href="`/communities/${community.slug}/${element.slug}`" 
                        class="card-url" />
                    <ImageArray 
                        :community="community"
                        :element="element" />
                    <div class="card-body">
                        <div 
                            v-if="title"
                            class="name">
                            <p> {{ element.name }} </p>
                        </div>
                        <div 
                            v-if="text"
                            class="blurb">
                            <p> {{ element.blurb }} </p>
                        </div>
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
        },

    }
</script>