<template>
    <div class="create-bottom-nav" :class="{review: isReview}">
        <div></div>
        <div class="create-navbar" :class="{review: isReview}">
        </div>
    </div>
</template>

<script>
    export default {

        props: ['event'],

        computed: {

        },
        
        data() {
            return {
                isReview: this.event ? window.location.pathname == `/create/${this.event.slug}/review` ? true : false : '',
            }
        },

         methods: {
            
        },


    }
</script>