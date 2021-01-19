<template>
    <div>
        <input 
        type="text" 
        :value="value"
        :placeholder="placeholder"
        @input="updateValue($event.target.value)"
        @focus="onFocus($event.target.value)"
        @keyup="onKeyUp($event.target.value)"
        list="inputlist"
        />
        <div>
            <transition :name="transition">
                <ul 
                    ref="dropdownMenu" 
                    v-if="dropdownOpen" 
                    @mousedown.prevent="onMousedown" 
                    @mouseup="onMouseUp">
                    <li
                    role="option"
                    v-for="(option, index) in options"
                    :key="option.model.id"
                    @mouseover="selectable(option.model) ? typeAheadPointer = index : null"
                    @mousedown.prevent.stop="selectable(option.model) ? select(option.model) : null"
                    >
                        <span name="option" v-bind="option">
                            {{ option.model.name }}
                        </span>
                    </li>
                </ul>
            </transition>
        </div>
    </div>
</template>

<script>

    export default {
        props: {
            /** * Contains the currently selected value.  */
            value: {},
            /**  * An array of strings or objects to be used as dropdown choices. */
            options: {
                type: Array,
                default() {
                    return []
                },
            },
            /** * Equivalent to the `placeholder` attribute on an `<input>` */
            placeholder: {
                type: String,
                default: ''
            },
            /**  * Sets a Vue transition property on the `.vs__dropdown-menu`. */
            transition: {
                type: String,
                default: 'vs__fade'
            },
            /** Decides whether an option is selectable or not. Not selectable options  */
            selectable: {
                type: Function,
                default: option => true,
            },
            /**
            * Close a dropdown when an option is chosen. Set to false to keep the dropdown
            * open (useful when combined with multi-select, for example)
            * @type {Boolean}
            */
            closeOnSelect: {
                type: Boolean,
                default: true
            },
            reduce: {
                type: Function,
                default: option => option,
            },
        },

        computed: {
            dropdownOpen() {
                return this.noDrop ? false : this.open && !this.mutableLoading
            },
            /**
            * The options that are currently selected.
            * @return {Array}
            */
            selectedValue () {
                let value = this.value;
                if (value) {
                    return [].concat(value);
                }
                return [];
            },
        },

        data() {
            return {
                selectedItem: null,
                typeAheadPointer: -1,
                noDrop: false,
                open: true,
                search: '',
            }
        },

        methods: {
            onMousedown() {
                this.mousedown = true
            },
            onMouseUp() {
                this.mousedown = false
            },

            onFocus(value) {
                this.$emit('open', value)
            },

            onKeyUp(value) {
                this.$emit('search-change', value)
            },

            updateValue (value) {
                if (value !== null) {
                    if (Array.isArray(value)) {
                        value = value.map(val => this.reduce(val));
                    } else {
                        value = this.reduce(value);
                    }
                }
                this.$emit('input', value);
            },

            selectItem (value) {
                console.log(value.model.name);
                this.selectedItem = value.model.name
                this.inputValue = ''
                this.$emit('on-item-selected', value)
            },
            /**
            * Select a given option.
            * @param  {Object|String} option
            * @return {void}
            */
            select(option) {
                if (!this.isOptionSelected(option)) {
                    if (this.multiple) {
                        option = this.selectedValue.concat(option)
                    }
                    this.updateValue(option);
                }
                this.onAfterSelect(option)
            },
            /**
            * Check if the given option is currently selected.
            * @param  {Object|String}  option
            * @return {Boolean}        True when selected | False otherwise
            */
            isOptionSelected(option) {
                return this.selectedValue.some(value => this.optionComparator(value, option))
            },
            /**
            * Determine if two option objects are matching.
            *
            * @param a {Object}
            * @param b {Object}
            * @returns {boolean}
            */
            optionComparator(a, b) {
                return this.getOptionKey(a) === this.getOptionKey(b);
            },

            
            /**
            * Called from this.select after each selection.
            * @param  {Object|String} option
            * @return {void}
            */
            onAfterSelect(option) {
                if (this.closeOnSelect) {
                    this.open = !this.open;
                    this.searchEl.blur()
                }

                if (this.clearSearchOnSelect) {
                    this.search = ''
                }
            },
        },
    }
</script>