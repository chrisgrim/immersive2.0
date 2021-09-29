<template>
    <div>
        <blockquote v-if="blockquote">
            <span :style="`white-space: ${whiteSpace};`">"{{ adjustedText }}"</span>
            <span 
                v-show="showMore" 
                @click="showMore = false">... <span class="show-text"><br>Show More</span>
            </span>
            <span 
                v-show="lessButton && !showMore && this.text.split(' ').length >= this.limit"
                @click="showMore = true"><span class="show-text"><br>Show Less</span>
            </span>
        </blockquote>
        <p class="text" v-else>
            <span :style="`white-space: ${whiteSpace};`">{{ adjustedText }}</span>
            <span 
                v-show="showMore" 
                @click="showMore = false">... <span class="show-text"><br>Show More</span>
            </span>
            <span 
                v-show="lessButton && !showMore && this.text.split(' ').length >= this.limit"
                @click="showMore = true"><span class="show-text"><br>Show Less</span>
            </span>
        </p>
    </div>
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
        }, 
        blockquote: {
            type: Boolean,
            default: false
        }, 
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