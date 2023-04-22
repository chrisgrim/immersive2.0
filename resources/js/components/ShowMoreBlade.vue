<template>
    <span> <span :style="italic ? 'font-style:italic;' : '' " v-show="isShowing"> {{ adjustedText }}</span><span v-if="showMore && !isShowing" @click="toggleText" class="show-text pl-0">... Show More</span> <span v-if="showMore && isShowing" @click="toggleText" class="show-text pl-0">... Show Less</span>
    </span>
</template>

<script>

export default {

    props : {
        text: {
            type: String,
            default: ''
        },
        limit: {
            type: Number,
            default: 100,
        }, 
        italic: {
            type: Boolean,
            default: false,
        }
    },

    computed: {
        adjustedText() {
            // return this.text.length >= this.limit && this.showMore ? this.text.substring(0, this.limit) : this.text
            return this.text.split(' ').length > this.limit && this.showMore ?  this.text.split(' ').slice(this.limit).join(' ') : this.text
        },
    },

    data() {
        return {
            showMore: this.text.split(' ').length >= this.limit ? true : false,
            isShowing: false,
        }
    },

    methods: {
        toggleText() {
            this.isShowing =! this.isShowing 
        },
    }
}
    
</script>