<template>
    <div>
        <!-- content -->
        <div class="relative">
            <div class="mx-8 mb-8 p-8 rounded-3xl bg-white shadow-custom-1">
                <div v-if="inputVal.currentTab==='price'">
                    <div 
                        @click="hidePrice"
                        class="font-semibold text-4xl flex justify-between items-center">
                        <span>Choose Price Range</span>
                        <svg class="w-8 h-8">
                            <use :xlink:href="`/storage/website-files/icons.svg#ri-arrow-up-s-line`" />
                        </svg>
                    </div>
                    <div class="z-50">
                        <div class="m-auto text-center bg-white rounded-2xl top-6">
                            <div class="w-[84%] m-auto mt-24">
                                <vue-slider
                                    v-model="inputVal.price"
                                    tooltip="always"
                                    v-bind="priceOptions"
                                    :tooltip-formatter="sliderFormat"
                                    :enable-cross="false" />
                            </div>
                            <div class="py-4 px-12 w-full justify-between flex mt-4">
                                <button 
                                    v-if="inputVal.price.length" 
                                    @click="clear" 
                                    class="border-none flex items-center">
                                    Clear
                                </button>
                                <button 
                                    v-if="!inputVal.price.length" 
                                    @click="hidePrice" 
                                    class="border-none flex items-center">
                                    Cancel
                                </button>
                                <button 
                                    class="px-8 h-14 rounded-full w-auto bg-white whitespace-nowrap text-white bg-default-red border-default-red" 
                                    @click="hidePrice">
                                    Save
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
                <div 
                    v-else
                    @click="showPrice"
                    class="flex justify-between cursor-pointer">
                    <div class="text-2xl">
                        Cost
                    </div>
                    <div class="font-semibold text-2xl">
                        <p v-if="!hasPrice">Add Price</p>
                        <p v-else>${{ inputVal.price[0] }} - ${{ inputVal.price[1] }}</p>
                    </div>
                </div>
                <!-- search -->
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
        },

        data() {
            return {
                priceOptions: { min: 0, max: 100 },
                sliderFormat: v => `$${('' + v).replace(/\B(?=(\d{3})+(?!\d))/g, ',')}`,
            }
        },

        methods: {
            clear() {
                this.inputVal.price = [0,100];
            },
            showPrice() {
                this.inputVal.currentTab='price'
            },
            hidePrice() {
                this.inputVal.currentTab=''
            }
        },
}
    
</script>