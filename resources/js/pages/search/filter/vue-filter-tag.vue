<template>
    <div v-click-outside="hide">
        <button 
            @click="show" 
            :class="{ 'text-white bg-default-red border-default-red' : value.length }" 
            class="absolute right-0 top-0 px-8 h-14 rounded-full w-auto bg-white whitespace-nowrap hover:text-white hover:bg-default-red hover:border-default-red md:relative">
            <template v-if="value.length && !mobile">
                <span> {{ tagList[0].name }} </span>
                <span v-if="value.length > 1">
                    +{{ value.length - 1 }}
                </span>
            </template>
            <template v-else-if="mobile">
                Show all tags
            </template>
            <template v-else>
                Tags
            </template>
        </button>
        <div 
            v-if="active"
            class="relative mt-8 left-0 w-full md:left-32 md:max-w-5xl md:absolute">
            <div class="m-auto text-center bg-white top-6 overflow-hidden md:shadow-custom-1 md:rounded-5xl">
                <div class="w-full overflow-y-auto overflow-x-hidden md:max-h-[calc(100vh-24rem)]">
                    <div class="grid bg-white gap-8 md:p-12 md:grid-cols-2">
                        <div 
                            v-for="(item) in tags" 
                            :key="item.id" 
                            @click="push(item)">
                            <button class="border-none flex items-center">
                                <div 
                                    :class="{ 'border-8' : value.includes(item.name) }"
                                    class="border-2 border-black h-10 w-10 bg-white overflow-hidden rounded-full mr-4" />
                                <p class="text-xl">{{ item.name }}</p>
                            </button>
                        </div>
                    </div>
                </div>
                <template v-if="!mobile">
                    <div class="py-4 px-12 w-full border-t justify-between flex">
                        <button 
                            v-if="value.length" 
                            @click="clear" 
                            class="border-none flex items-center">
                            Clear
                        </button>
                        <button 
                            v-else
                            @click="active = false;" 
                            class="border-none flex items-center">
                            Cancel
                        </button>
                        <button 
                            @click="submit()"
                            class="px-8 h-14 rounded-full w-auto bg-white whitespace-nowrap text-white bg-default-red border-default-red">
                            Submit
                        </button>
                    </div>
                </template>
            </div>
        </div>
    </div>
</template>

<script>
    import clickOutside from '../../../Directives/clickOutside' 
    export default {

        props: ['value', 'tags', 'mobile'],

        computed: {
            inputVal: {
                get() { return this.value },
                set(val) { this.$emit('input', val) }
            },
            tagList() {
                return this.tags.filter( tag => this.value.includes(tag.name))
            }
        },

        data() {
            return {
                active: false,
            }
        },

        methods: {
            submit() {
                this.$emit('submit', true);
                this.active = false;
            },
            push(tag) {
                if (!tag) return;
                if (this.value.includes(tag.name)) {
                    let removeIndex = this.value.indexOf(tag.name);
                    this.inputVal.splice(removeIndex, 1);
                } else {
                    this.inputVal.push(tag.name);
                }
            },
            clear() {
                this.inputVal = [];
                this.submit();
            },
            show() {
                this.active =! this.active;
            },
            hide() {
                this.active = false;
            },
        },
    }
</script>