<template>
    <div 
        class="button" 
        ref="cat">
        <button
            @click="show" 
            :class="{ active: value.length }" 
            class="filter round">
            <template v-if="value.length && !mobile">
                <span> {{ categoryList[0].name }} </span>
                <span v-if="value.length > 1">
                    +{{ value.length - 1 }}
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
                                            :class="{ active: value.includes(cat.id) }"
                                            class="checkbox" />
                                    </span>
                                    <span>{{ cat.name }}</span>
                                </button>
                            </div>
                        </div>
                    </div>
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
                </div>
            </div>
        </template>
    </div>
</template>

<script>
    export default {

        props: ['value','categories', 'mobile'],

        computed: {
            inputVal: {
                get() { return this.value },
                set(val) { this.$emit('input', val) }
            },
            categoryList()  {
                return this.categories.filter( cat => this.value.includes(cat.id))
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

            push(category) {
                if (!category) return;
                if (this.value.includes(category.id)) {
                   let removeIndex = this.value.indexOf(category.id);
                   this.inputVal.splice(removeIndex, 1);
                } else {
                    this.inputVal.push(category.id);
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
                if (this.active == false) {return}
                let cat = this.$refs.cat;
                if (!cat || cat.contains(event.target)) return;
                this.active = false;
                if (this.value.length) this.submit();
            },
        },

    }
</script>