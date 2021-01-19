<template>
    <div class="container">
        <div class="category">
            <div class="list">
                <h2>Explore {{category.name}}</h2>
                <div>
                    <div id="grid">
                        <div v-for="event in category.events">
                            <event-listing-item :event="event"></event-listing-item>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script>



    export default {

        props: ['category'],

        data() {
            return {
                value: '',
                list: [],
                price: '',
                eventName: '',
                location: '',
            }
        },

        methods: {
        }

    };
</script>