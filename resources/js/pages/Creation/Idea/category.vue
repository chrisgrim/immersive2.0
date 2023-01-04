<template>
    <main class="transition ease-in duration-300">
        <div class="fixed w-[calc(100vw-26rem)] overflow-auto h-full left-[26rem]">
            <div class="p-12 h-28 w-full">
                <p class="font-semibold">Category</p>
            </div>
            <div class="flex justify-center w-full p-8">
                <div class="max-w-3xl">
                    <h2>What Category does your event fall into??</h2>
                </div>
            </div>
            <div class="max-w-3xl m-auto">
                <div 
                    class="w-full relative"
                    ref="categoryDrop">
                    <div class="w-full relative">
                        <svg 
                            :class="{'rotate-90': dropdown}"
                            class="w-10 h-10 fill-black absolute z-10 right-4 top-8">
                            <use :xlink:href="`/storage/website-files/icons.svg#ri-arrow-right-s-line`" />
                        </svg>
                        <input 
                            class="text-2xl relative p-8 w-full border mb-12 rounded-3xl focus:rounded-3xl"
                            v-model="category"
                            placeholder="Select Category"
                            @input="filterCategories"
                            @focus="onDropdown"
                            autocomplete="false"
                            type="text">
                        <div v-if="inputVal.event.category && !dropdown">
                            <img 
                                class="h-[30rem] w-full object-cover rounded-3xl" 
                                :src="`${$envImageUrl}${inputVal.event.category.largeImagePath}`" 
                                alt="">
                            <p class="text-xl mt-8">
                                {{inputVal.event.category.description}}</p>
                        </div>
                    </div>
                    <ul 
                        class="h-[40rem] overflow-auto bg-white relative w-full list-none rounded-b-3xl" 
                        v-if="dropdown">
                        <li 
                            class="py-6 px-6 flex items-center gap-8 hover:bg-gray-300" 
                            v-for="item in categoryList"
                            :key="item.id"
                            @click="selectCategory(item)">
                            <img 
                                class="w-16" 
                                :src="`${$envImageUrl}${item.thumbImagePath}`" 
                                alt="">
                            {{item.name}}
                        </li>
                    </ul>
                </div>
            </div>
        </div>
    </main>
</template>

<script>
    import { required } from 'vuelidate/lib/validators'
    export default {

        props: ['categories', 'value'],

        computed: {
            inputVal: {
                get() { return this.value },
                set(val) { this.$emit('input', val) }
            },
        },

        components: {  },

        data() {
            return {
                dropdown: false,
                category: this.initializeCategory(),
                storeCategory: null,
                categoryList: this.categories,
                genres: '',
            };
        },

        methods: {
            filterCategories() {
                if (this.category === "") { this.categoryList = this.categories }
                this.categoryList = this.categories.filter( item => item.name.toLowerCase().replaceAll('-', ' ').includes(this.category.toLowerCase()))
            },
            selectCategory(category) {
                this.category=category.name
                this.dropdown=false
                this.inputVal.event.category_id=category.id
                this.inputVal.event.category=category
                this.inputVal.submit=true
                this.$emit('submit', true);
            },
            onDropdown() {
                this.dropdown = true
            },
            initializeCategory() {
                return this.value.event.category_id ? this.value.event.category.name : ''
            },
            checkStatus() {
                this.$v.$touch()
                this.inputVal.submit =! this.$v.$invalid
            },
            onClickOutside(event) {
                let arr =  this.$refs.categoryDrop;
                if (!arr || arr.contains(event.target)) return;
                this.dropdown = false;
            },
        },

        mounted() {
            this.checkStatus();
            document.addEventListener("click", this.onClickOutside);
        },

        beforeUnmount() {
            document.removeEventListener("click", this.onClickOutside);
        },

        validations: {
            inputVal: {
                event : {
                    category_id: {
                        required,
                    },
                }
            }
        },

    }
</script>