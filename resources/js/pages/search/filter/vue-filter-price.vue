<template>
    <div>
        <button 
            ref="priceButton"
            @click="togglePrice" 
            :class="{ 'text-white bg-default-red border-default-red' : hasPrice }" 
            class="relative px-8 h-14 rounded-full w-auto bg-white whitespace-nowrap hover:text-white hover:bg-default-red hover:border-default-red text-xl">
            <template v-if="hasPrice">
                ${{ value.price[0] }} - ${{ value.price[1] }}
            </template>
            <template v-else>
                Price
            </template>
        </button>
        <div 
            v-if="isShowing"
            ref="pricePopUp"
            class="absolute mt-8 h-24 w-full max-w-xl z-50"> 
            <div class="m-auto text-center bg-white shadow-custom-1 rounded-2xl top-6 overflow-hidden">
                <div class="w-full h-32 py-12 px-24 mt-12 items-end justify-center">
                    <vue-slider
                        v-model="inputVal.price"
                        tooltip="always"
                        v-bind="priceOptions"
                        :tooltip-formatter="sliderFormat"
                        :enable-cross="false" />
                </div>
                <div class="py-4 px-12 w-full border-t justify-between flex">
                    <button 
                        v-if="hasPrice" 
                        @click="clear" 
                        class="border-none flex items-center">
                        clear
                    </button>
                    <button 
                        v-else
                        @click="hidePrice" 
                        class="border-none flex items-center">
                        Cancel
                    </button>
                    <button 
                        @click="submit" 
                        class="px-8 h-14 rounded-full w-auto bg-white whitespace-nowrap text-white bg-default-red border-default-red">
                        Submit
                    </button>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
    import VueSlider from 'vue-slider-component'
    import 'vue-slider-component/theme/antd.css'
    export default {

        props: ['value'],

        components: { VueSlider },

        computed: {
            inputVal: {
                get() { return this.value },
                set(val) { this.$emit('input', val) }
            },
            hasPrice() {
                return this.value.price[0] === 0 && this.value.price[1] === 100 ? false : true;
            },
            isShowing() {
                return this.inputVal.currentTab==='price'
            },
        },

        data() {
            return {
                priceOptions: { min: 0, max: 100 },
                sliderFormat: v => `$${('' + v).replace(/\B(?=(\d{3})+(?!\d))/g, ',')}`,
            }
        },

        methods: {
            submit() {
                this.$emit('submit', true);
                this.hidePrice()
            },
            clear() {
                this.inputVal.price = [0,100];
            },
            togglePrice() {
                this.inputVal.currentTab==='price' ? this.inputVal.currentTab='' : this.inputVal.currentTab='price'
            },
            hidePrice() {
                this.inputVal.currentTab=''
            },
            onClickOutside(event) {
                let arr = this.$refs.priceButton;
                let arr1 = this.$refs.pricePopUp;
                if (!arr || arr.contains(event.target) || !arr1 || arr1.contains(event.target)) return;
                this.hidePrice()
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