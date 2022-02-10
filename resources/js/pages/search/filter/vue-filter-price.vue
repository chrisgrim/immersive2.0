<template>
    <div class="col"> 
        <div 
            v-if="mobile"
            class="relative h-24 w-2/4 mx-auto mt-28"> 
            <vue-slider
                v-model="inputVal"
                tooltip="always"
                v-bind="priceOptions"
                :tooltip-formatter="sliderFormat"
                :enable-cross="false" />
        </div>
        <div 
            v-else 
            v-click-outside="hide">
            <button 
                @click="show" 
                :class="{ 'text-white bg-default-red border-default-red' : hasPrice }" 
                class="px-8 h-14 rounded-full w-auto bg-white whitespace-nowrap hover:text-white hover:bg-default-red hover:border-default-red">
                <template v-if="hasPrice">
                    ${{ value[0] }} - ${{ value[1] }}
                </template>
                <template v-else>
                    Price
                </template>
            </button>
            <div 
                v-if="active"
                class="absolute mt-8 h-24 w-full left-0 md:left-32 md:max-w-xl z-50"> 
                <div class="m-auto text-center bg-white shadow-custom-1 rounded-5xl top-6 overflow-hidden">
                    <div class="w-full h-32 py-12 px-24 mt-12 items-end justify-center">
                        <vue-slider
                            v-model="inputVal"
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
                            @click="active = false;" 
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
    </div>
</template>

<script>
    import VueSlider from 'vue-slider-component'
    import 'vue-slider-component/theme/antd.css'
    import clickOutside from '../../../Directives/clickOutside'
    export default {

        props: ['value', 'mobile'],

        components: { VueSlider },

        computed: {
            inputVal: {
                get() { return this.value },
                set(val) { this.$emit('input', val) }
            },
            hasPrice() {
                return this.value[0] === 0 && this.value[1] === 100 ? false : true;
            },
        },

        data() {
            return {
                active: false,
                priceOptions: { min: 0, max: 100 },
                sliderFormat: v => `$${('' + v).replace(/\B(?=(\d{3})+(?!\d))/g, ',')}`,
            }
        },

        methods: {
            submit() {
                this.$emit('submit', true);
                this.active = false;
            },
            clear() {
                this.inputVal = [0,100];
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