<template>
    <div class="whitespace-nowrap overflow-y-hidden overflow-x-auto m-auto w-full">
        <div 
            style="scroll-snap-type: x mandatory;" 
            class="flex w-full scroll-p-7 overflow-auto mt-8 scroll-smooth md:flex-wrap mx-[-1rem]">
            <div 
                v-for="(element) in elements"
                :key="element.id"
                class="relative w-full flex flex-[1_0_calc(100%-6rem)] snap-start snap-always px-4 md:flex-[0_1_33.3333333333%] md:w-4/12 lg:flex-[0_1_25%] lg:w-3/12">
                <div class="flex w-full flex-col overflow-hidden relative">
                    <a 
                        :href="`/communities/${community.slug}/${element.slug}`" 
                        class="block h-full absolute w-full rounded-2xl top-0 left-0 z-10" />
                    <CardImage 
                        :community="community"
                        :element="element" />
                    <div class="mb-8 mt-2">
                        <div 
                            v-if="title"
                            class="mt-4 overflow-hidden text-ellipsis max-h-16">
                            <p> {{ element.name }} </p>
                        </div>
                        <div 
                            v-if="text"
                            class="mt-2 overflow-hidden text-ellipsis max-h-16">
                            <p> {{ element.blurb }} </p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
    import CardImage from './vue-image.vue'
    export default {

        props: ['value', 'title', 'text', 'link', 'edit', 'shelf', 'community'],

        components: { CardImage },

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