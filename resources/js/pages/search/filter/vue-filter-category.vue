<template>
    <div 
        class="button" 
        ref="cat">
        <button
            @click="show" 
            :class="{ active: category.length }" 
            class="filter round">
            <template v-if="category.length && !mobile">
                <span> {{ category[0].name }} </span>
                <span v-if="category.length > 1">
                    +{{ category.length - 1 }}
                </span>
            </template>
            <template v-else>
                Categories
            </template>
        </button>
        <template v-if="active">
            <div class="filter__category"> 
                <div class="filter__dropdown">
                    <div class="element">
                        <div class="grid">
                            <div 
                                v-for="(cat) in categories" 
                                :key="cat.id" 
                                class="filter__category--element" 
                                @click="push(cat)">
                                <button class="borderless">
                                    <span>
                                        <div 
                                            :class="{ active: category.includes(cat) }"
                                            class="checkbox">
                                            <IconSvg 
                                                v-if="category.includes(cat)"
                                                type="check" />
                                        </div>
                                    </span>
                                    <span>{{ cat.name }}</span>
                                </button>
                            </div>
                        </div>
                    </div>
                    <div class="filter__dropdown--footer">
                        <button 
                            v-if="category.length" 
                            @click="clear" 
                            class="borderless">
                            Clear
                        </button>
                        <button 
                            v-else
                            @click="active = false;" 
                            class="borderless">
                            Cancel
                        </button>
                        <button 
                            @click="submit()"
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
    import IconSvg from '../../../components/Svg-icon'
    export default {

        props: ['categories', 'mobile'],

        components: { IconSvg },

        computed: {

        },

        data() {
            return {
                active: false,
                category: this.$store.state.filterCategory,
            }
        },

        methods: {
            submit() {
                this.$emit('submit', true);
                this.active = false;
            },

            push(value) {
                if (!value) return;
                if (this.category.includes(value)) {
                   let removeIndex = this.category.map(function(item) { return item.id; }).indexOf(value.id);
                   this.category.splice(removeIndex, 1);
                } else {
                    this.category.push(value);
                }
                this.$store.commit('filterCategory', this.category)
            },

            clear() {
                this.category = [];
                this.$store.commit('filterCategory', [])
            },

            show() {
                this.active =! this.active;
                setTimeout(() => document.addEventListener('click', this.onClickOutside), 200);
            },

            onClickOutside(event) {
                if (this.active == false) {return}
                let cat = this.$refs.cat;
                if (!cat || cat.contains(event.target)) return;
                this.active = false;
                this.submit();
            },
        },

    }
</script>