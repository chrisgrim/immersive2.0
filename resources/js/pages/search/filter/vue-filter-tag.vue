<template>
    <div 
        class="button" 
        ref="tag">
        <button 
            @click="show" 
            :class="{ active : tag.length }" 
            class="filter round">
            <template v-if="tag.length && !mobile">
                <span> {{ tag[0].name }} </span>
                <span v-if="tag.length > 1">
                    +{{ tag.length - 1 }}
                </span>
            </template>
            <template v-else>
                Tags
            </template>
        </button>
        <template v-if="active">
            <div class="filter__tag"> 
                <div class="filter__dropdown">
                    <div class="element">
                        <div class="grid">
                            <div 
                                v-for="(item) in tags" 
                                :key="item.id" 
                                class="filter__category--element" 
                                @click="push(item)">
                                <button class="borderless">
                                    <span>
                                        <div 
                                            :class="{ active: tag.includes(item) }"
                                            class="checkbox">
                                            <IconSvg 
                                                v-if="tag.includes(item)"
                                                type="check" />
                                        </div>
                                    </span>
                                    <span>{{ item.name }}</span>
                                </button>
                            </div>
                        </div>
                    </div>
                    <div class="filter__dropdown--footer">
                        <button 
                            v-if="tag.length" 
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

        props: ['tags', 'mobile'],

        components: { IconSvg },

        computed: {

        },

        data() {
            return {
                active: false,
                tag: this.$store.state.filterTag,
            }
        },

        methods: {
            submit() {
                this.$emit('submit', true);
                this.active = false;
            },

            push(value) {
                if (!value) return;
                if (this.tag.includes(value)) {
                    let removeIndex = this.tag.map(function(item) { return item.id; }).indexOf(value.id);
                    this.tag.splice(removeIndex, 1);
                } else {
                    this.tag.push(value);
                }
                this.$store.commit('filterTag', this.tag)
            },

            clear() {
                this.tag = [];
                this.$store.commit('filterTag', [])
            },

            show() {
                this.active =! this.active;
                setTimeout(() => document.addEventListener('click', this.onClickOutside), 200);
            },

            onClickOutside(event) {
                if (this.active === false) {return}
                let tag = this.$refs.tag;
                if (!tag || tag.contains(event.target)) return;
                this.active = false;
                if (this.tag.length) this.submit();
            },
        },
    }
</script>