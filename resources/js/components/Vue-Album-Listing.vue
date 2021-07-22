<template>
    <div class="album listing container">
        <div>
            <draggable
                class="row"
                v-model="elements"
                draggable=".drag"
                @start="isDragging=true" 
                @end="debounce">
                <div 
                    v-for="(element) in elements"
                    :key="element.id"
                    :class="{ drag: draggable}"
                    class="col">
                    <div class="card">
                        <div class="card-img">
                            <a 
                                :href="`/communities/${parent.slug}/${element.slug}`" 
                                class="card-url" />
                            <div class="single">
                                <picture v-if="firstImage(element)">
                                    <source 
                                        type="image/webp" 
                                        :srcset="`/storage/${firstImage(element)}`"> 
                                    <img 
                                        style="object-fit:cover" 
                                        loading="lazy" 
                                        :src="`/storage/${firstImage(element).slice(0, -4)}jpg`" 
                                        :alt="`${element.name}`">
                                </picture>
                            </div>
                            <div class="double">
                                <div class="img top">
                                    <picture v-if="secondImage(element)">
                                        <source 
                                            type="image/webp" 
                                            :srcset="`/storage/${secondImage(element)}`"> 
                                        <img 
                                            style="object-fit:cover" 
                                            loading="lazy" 
                                            :src="`/storage/${secondImage(element).slice(0, -4)}jpg`" 
                                            :alt="`${element.name}`">
                                    </picture>
                                </div>
                                <div class="img">
                                    <picture v-if="thirdImage(element)">
                                        <source 
                                            type="image/webp" 
                                            :srcset="`/storage/${thirdImage(element)}`"> 
                                        <img 
                                            style="object-fit:cover" 
                                            loading="lazy" 
                                            :src="`/storage/${thirdImage(element).slice(0, -4)}jpg`" 
                                            :alt="`${element.name}`">
                                    </picture>
                                </div>
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
                            <a 
                                v-if="edit"
                                :href="`/communities/${parent.slug}/${element.slug}/edit`">
                                <button>edit</button>
                            </a>
                        </div>
                    </div>
                </div>
            </draggable>
        </div>
    </div>
</template>

<script>
    import Draggable from "vuedraggable";
    export default {

        props: ['value', 'title', 'text', 'link', 'parent', 'edit', 'draggable'],

        components: { Draggable },

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
            getImages(element) {
                return  [element.thumbImagePath].concat(element.cards.map( e => e.thumbImagePath));
            },
            firstImage(element) {
                return [element.thumbImagePath].concat(element.cards.map( e => e.thumbImagePath))[0];
            },
            secondImage(element) {
                return [element.thumbImagePath].concat(element.cards.map( e => e.thumbImagePath))[1];
            },
            thirdImage(element) {
                return [element.thumbImagePath].concat(element.cards.map( e => e.thumbImagePath))[2];
            },
            debounce() {
                if (this.timeout) 
                    clearTimeout(this.timeout); 
                this.timeout = setTimeout(() => {
                    console.log('send update');
                }, 500); // delay
            },
        },

    }
</script>