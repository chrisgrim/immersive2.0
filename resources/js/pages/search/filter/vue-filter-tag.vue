<template>
    <div 
        class="col" 
        ref="tag">
        <button 
            @click="show" 
            :class="{ active : value.length }" 
            class="filter round">
            <template v-if="value.length && !mobile">
                <span> {{ tagList[0].name }} </span>
                <span v-if="value.length > 1">
                    +{{ value.length - 1 }}
                </span>
            </template>
            <template v-else-if="mobile">
                Show all tags
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
                                            :class="{ active: value.includes(item.name) }"
                                            class="checkbox" />
                                    </span>
                                    <span>{{ item.name }}</span>
                                </button>
                            </div>
                        </div>
                    </div>
                    <template v-if="!mobile">
                        <div class="filter__dropdown--footer">
                            <button 
                                v-if="value.length" 
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
                    </template>
                </div>
            </div>
        </template>
    </div>
</template>

<script>
    export default {

        props: ['value', 'tags', 'mobile'],

        computed: {
            inputVal: {
                get() { return this.value },
                set(val) { this.$emit('input', val) }
            },
            tagList() {
                return this.tags.filter( tag => this.value.includes(tag.name))
            }
        },

        data() {
            return {
                active: false,
            }
        },

        methods: {
            submit() {
                this.$emit('submit', true);
                this.active = false;
            },
            push(tag) {
                if (!tag) return;
                if (this.value.includes(tag.name)) {
                    let removeIndex = this.value.indexOf(tag.name);
                    this.inputVal.splice(removeIndex, 1);
                } else {
                    this.inputVal.push(tag.name);
                }
            },
            clear() {
                this.inputVal = [];
                this.submit();
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
                if (this.value.length) this.submit();
            },
        },
    }
</script>