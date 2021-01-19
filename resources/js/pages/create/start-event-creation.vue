<template>
    <div class="event-create-start grid" :style="pageHeight">
        <div class="start-content">
            <div class="start-content__text">
                <h2>Hi, {{user.name}}</h2>
                <p>We're excited you are listing your event on Everything Immersive! Our process has you create your organization first and then events for that organizer. We <b>don't sell tickets</b> on EI, but provide an easy way for thousands of users to discover immersive events like yours.</p>
            </div>
            <a href="/organizer/create"><button class="event-start-button">Create Organization</button></a>
        </div>
        <div class="login-image">
            <picture>
                <img :style="pageHeight" src="/storage/website-files/login-image.jpg">
            </picture>
        </div>
    </div>
</template>

<script>
    export default {

        props: ['user'],

        data() {
            return {
                pageHeight: 0,
            }
        },

        methods: {
            handleResize() {
                this.pageHeight = `height:${window.innerHeight}px;`
            },
        },

        created() {
            window.addEventListener('resize', this.handleResize)
            this.handleResize();
        },

        destroyed() {
            window.removeEventListener('resize', this.handleResize)
        },

    }
</script>