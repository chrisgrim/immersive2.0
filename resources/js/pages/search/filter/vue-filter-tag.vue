<template>
    <div>
        <button 
            ref="tagButton"
            @click="toggleTag" 
            :class="{ 'text-white bg-default-red border-default-red' : hasTags }" 
            class="relative px-8 h-14 rounded-full w-auto bg-white whitespace-nowrap hover:text-white hover:bg-default-red hover:border-default-red text-xl">
            <template v-if="value.tag.length">
                <span> {{ value.tag[0].name }} </span>
                <span v-if="value.tag.length > 1">
                    +{{ value.tag.length - 1 }}
                </span>
            </template>
            <template v-else>
                Tags
            </template>
        </button>
        <div 
            v-if="isShowing"
            ref="tagPopUp"
            class="mt-8 w-full max-w-5xl absolute">
            <div class="m-auto text-center bg-white top-6 overflow-hidden shadow-custom-1 rounded-2xl">
                <div class="w-full overflow-y-auto overflow-x-hidden max-h-[calc(80vh-24rem)]">
                    <div class="grid bg-white gap-8 p-12 grid-cols-2">
                        <div 
                            v-for="(item) in tags" 
                            :key="item.id" 
                            @click="push(item)">
                            <button class="border-none flex items-center">
                                <div 
                                    :class="{ 'border-8' : tagList.includes(item.id) }"
                                    class="border-2 border-black h-10 w-10 bg-white overflow-hidden rounded-full mr-4" />
                                <p class="text-xl">{{ item.name }}</p>
                            </button>
                        </div>
                    </div>
                </div>
                <div class="py-4 px-12 w-full border-t justify-between flex">
                    <button 
                        v-if="hasTags" 
                        @click="clear" 
                        class="border-none flex items-center">
                        Clear
                    </button>
                    <button 
                        v-else
                        @click="hideTag" 
                        class="border-none flex items-center">
                        Cancel
                    </button>
                    <button 
                        @click="submit()"
                        class="px-8 h-14 rounded-full w-auto bg-white whitespace-nowrap text-white bg-default-red border-default-red">
                        Submit
                    </button>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
    export default {

        props: ['value', 'tags'],

        computed: {
            inputVal: {
                get() { return this.value },
                set(val) { this.$emit('input', val) }
            },
            isShowing() {
                return this.value.currentTab==='tag'
            },
            hasTags() {
                return this.inputVal.tag.length;
            },
            tagList() {
                return this.inputVal.tag.map( tag => tag.id)
            }
        },


        methods: {
            submit() {
                this.$emit('submit', true);
                this.hideTag()
            },
            push(tag) {
                if (this.tagList.includes(tag.id)) {
                    this.inputVal.tag = this.inputVal.tag.filter( tag => tag.id !== tag.id)
                } else {
                    this.inputVal.tag.push(tag)
                }
            },
            clear() {
                this.inputVal.tag = [];
                this.submit();
            },
            toggleTag() {
                this.inputVal.currentTab==='tag' ? this.inputVal.currentTab='' : this.inputVal.currentTab='tag'
            },
            hideTag() {
                this.inputVal.currentTab=''
            },
            onClickOutside(event) {
                let arr = this.$refs.tagButton;
                let arr1 = this.$refs.tagPopUp;
                if (!arr || arr.contains(event.target) || !arr1 || arr1.contains(event.target)) return;
                this.hideTag()
            },
        },

        mounted() {
            document.addEventListener("click", this.onClickOutside);
        },

        beforeUnmount() {
            document.removeEventListener("click", this.onClickOutside);
        },
    }
</script>