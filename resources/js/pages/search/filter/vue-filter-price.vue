<template>
    <div 
        class="button" 
        ref="price">
        <button 
            @click="show" 
            :class="{ active: hasPrice }" 
            class="filter round">
            <template v-if="hasPrice">
                ${{ value[0] }} - ${{ value[1] }}
            </template>
            <template v-else>
                Price
            </template>
        </button>
        <template v-if="active">
            <div class="filter__price"> 
                <div class="filter__dropdown">
                    <div>
                        <vue-slider
                            v-model="inputVal"
                            tooltip="always"
                            v-bind="priceOptions"
                            @drag-end="mobileSubmit"
                            :tooltip-formatter="sliderFormat"
                            :enable-cross="false" />
                    </div>
                    <div class="filter__dropdown--footer">
                        <button 
                            v-if="hasPrice" 
                            @click="clear" 
                            class="borderless">
                            clear
                        </button>
                        <button 
                            v-else
                            @click="active = false;" 
                            class="borderless">
                            Cancel
                        </button>
                        <button 
                            @click="submit" 
                            class="filter round">
                            Submit
                        </button>
                    </div>
                </div>
            </div>
        </template>
    </div>
</template>

<script>
    import VueSlider from 'vue-slider-component'
    import 'vue-slider-component/theme/antd.css'
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
            mobileSubmit() {
                this.$store.commit('filterPrice', this.price)
            },
            clear() {
                this.inputVal = [0,100];
            },
            show() {
                this.active =! this.active;
                setTimeout(() => document.addEventListener('click', this.onClickOutside), 200);
            },
            onClickOutside(event) {
                if (this.active == false) {return}
                let price = this.$refs.price;
                if (!price || price.contains(event.target)) return;
                this.active = false;
                this.submit();
            },
        },

    }
</script>