<template>
    <p class="text">
        <span :style="`white-space: ${whiteSpace};`">{{ adjustedText }}</span>
        <span 
            v-if="showMore" 
            @click="showMore = false">... <span class="show-text"><br>Show More</span>
        </span>
        <span 
            v-if="lessButton && !showMore && this.text.split(' ').length >= this.limit"
            @click="showMore = true"><span class="show-text"><br>Show Less</span>
        </span>
    </p>
</template>

<script>

export default {

    props : {
        text: {
            type: String,
            default: ''
        },
        limit : {
            type: Number,
            default: 100,
        }, 
        whiteSpace: {
            type: String,
            default: 'pre-wrap'
        },
        lessButton: {
            type: Boolean,
            default: true
        }
    },

    computed: {
        adjustedText() {
            // return this.text.length >= this.limit && this.showMore ? this.text.substring(0, this.limit) : this.text
            return this.text.split(' ').length > this.limit && this.showMore ?  this.text.split(' ').slice(0, this.limit).join(' ') : this.text
        },
    },

    data() {
        return {
            showMore: this.text.split(' ').length >= this.limit ? true : false,
        }
    },
}
    
</script>