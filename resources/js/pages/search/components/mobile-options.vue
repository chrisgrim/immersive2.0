<template>
    <div class="nav-search__options">
        <div class="header">
            <button 
                class="svg close" 
                @click="onClose">
                <svg>
                    <use :xlink:href="`/storage/website-files/icons.svg#ri-close-line`" />
                </svg>
            </button>
            <div class="title">
                Search
            </div>
            <button 
                class="svg options btn underline" 
                @click="onClear">
                Clear
            </button>
        </div>
        <div class="body">
            <div class="price">
                
            </div>
            <div class="category">
                
            </div>
            <div class="tags">
                
            </div>
        </div>
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
            onClose() {
                this.$emit('close');
            },
            onClear() {
                this.inputVal = [0,100];
            },
        },

    }
</script>