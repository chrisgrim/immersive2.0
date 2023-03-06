<template>
    <div>
        <button
            ref="catButton"
            @click="toggleCategory" 
            :class="{ 'text-white bg-default-red border-default-red' : hasCategories }" 
            class="relative px-8 h-14 rounded-full w-auto bg-white whitespace-nowrap hover:text-white hover:bg-default-red hover:border-default-red text-xl">
            <template v-if="value.category.length">
                <span> {{ value.category[0].name }} </span>
                <span v-if="value.category.length > 1">
                    +{{ value.category.length - 1 }}
                </span>
            </template>
            <template v-else>
                Categories
            </template>
        </button>
        <div 
            v-if="isShowing"
            ref="catPopUp"
            class="mt-8 w-full max-w-5xl absolute">
            <div class="m-auto text-center bg-white top-6 overflow-hidden shadow-custom-1 rounded-2xl">
                <div class="w-full overflow-y-auto overflow-x-hidden max-h-[calc(80vh-24rem)]">
                    <div class="grid bg-white gap-8 p-12 grid-cols-2">
                        <div 
                            v-for="(cat) in categories" 
                            :key="cat.id"
                            @click="push(cat)">
                            <button class="border-none flex items-center">
                                <div 
                                    :class="{ 'border-8': categoryList.includes(cat.id) }"
                                    class="border-2 border-black h-10 w-10 bg-white overflow-hidden rounded-full mr-4" />
                                <p class="text-xl">{{ cat.name }}</p>
                            </button>
                        </div>
                    </div>
                </div>
                <div class="py-4 px-12 w-full border-t justify-between flex">
                    <button 
                        v-if="hasCategories" 
                        @click="clear" 
                        class="border-none flex items-center">
                        Clear
                    </button>
                    <button 
                        v-else
                        @click="hideCategory" 
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

        props: ['value','categories', 'mobile'],

        computed: {
            inputVal: {
                get() { return this.value },
                set(val) { this.$emit('input', val) }
            },
            isShowing() {
                return this.value.currentTab==='category'
            },
            hasCategories() {
                return this.inputVal.category.length;
            },
            categoryList()  {
                return this.inputVal.category.map( cat => cat.id)
            }
        },

        methods: {
            submit() {
                this.$emit('submit', true);
                this.hideCategory()
            },
            push(category) {
                if (this.categoryList.includes(category.id)) {
                    this.inputVal.category = this.inputVal.category.filter( cat => cat.id !== category.id)
                } else {
                    this.inputVal.category.push(category)
                }
            },
            clear() {
                this.inputVal.category = [];
                this.submit();
            },
            toggleCategory() {
                this.inputVal.currentTab==='category' ? this.inputVal.currentTab='' : this.inputVal.currentTab='category'
            },
            hideCategory() {
                this.inputVal.currentTab=''
            },
            onClickOutside(event) {
                let arr = this.$refs.catButton;
                let arr1 = this.$refs.catPopUp;
                if (!arr || arr.contains(event.target) || !arr1 || arr1.contains(event.target)) return;
                this.hideCategory()
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