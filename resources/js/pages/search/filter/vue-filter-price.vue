<template>
    <div 
        class="button" 
        ref="price">
        <button 
            @click="show" 
            :class="{ active: hasPrice }" 
            class="filter round">
            <template v-if="hasPrice">
                ${{ price[0] }} - ${{ price[1] }}
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
                            v-model="price"
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

        props: ['mobile'],

        components: { VueSlider },

        data() {
            return {
                active: false,
                price:  this.$store.state.filterCategory,
                hasPrice: false,
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
                this.price = [0,100];
                this.$store.commit('filterPrice', [0,100])
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

            onLoad() {
                if (this.$store.state.filterPrice) {
                    this.price = this.$store.state.filterPrice;
                    this.hasPrice = true;
                }
            }
        },

        mounted() {
            this.onLoad();
        },

        watch: {
            price() {
                this.price[0] === 0 && this.price[1] === 100 ? this.hasPrice = false : this.hasPrice = true;
            },
        },
    }
</script>