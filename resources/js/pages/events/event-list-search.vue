<template>
    <div class="container">
        <div class="index">
            <div class="events">
                <h2>Our Latest Events</h2>
                <div>
                    <div id="grid">
                        <div v-for="event in events">
                            <event-listing-item :user="user" :event="event"></event-listing-item>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script>

    export default {

        props: {
            events: {
                type:Array,
            },
            user: {
                type:String
            }
        }, 

        data() {
            return {
            }
        },

        methods: {
        
        },

    };
</script>