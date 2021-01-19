<template>
    <div class="account-index">
        <div class="account-notifications_header">
            <h2>Account</h2>
        </div>
        <section class="account-index-content grid">
            <a href="/account-settings/notifications">
                <div class="account-index__element">
                    <h3>Notifications</h3>
                    <p>Choose your newsletter and contact preferences</p>
                </div>
            </a>
            <a :href="`/users/${user.id}`">
                <div class="account-index__element">
                    <h3>Profile</h3>
                    <p>Update your user information</p>
                </div>
            </a>
            <a href="/account-settings/favorited">
                <div class="account-index__element">
                    <h3>Favorites</h3>
                    <p>Look through your favorited events and their organizers</p>
                </div>
            </a>
        </section>
        
    </div>
</template>
<script>

    export default {

        props: ['user'],

        components: {
            
        },

        computed: {
            
        },

        data() {
            return {
                
            }
        },

        methods: {

            
        },

        mounted() {
            
        },

    };
</script>