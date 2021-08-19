<template>
    <div class="account">
        <section class="section-a">
            <div class="section-a__wrapper">
                <div>
                    <h2>Account</h2>
                </div>
                <div class="feature-a three">
                    <a href="/account-settings/notifications">
                        <div class="element">
                            <h3>Notifications</h3>
                            <p>Choose your newsletter and contact preferences</p>
                        </div>
                    </a>
                    <a :href="`/users/${user.id}`">
                        <div class="element">
                            <h3>Profile</h3>
                            <p>Update your user information</p>
                        </div>
                    </a>
                    <a href="/account-settings/favorited">
                        <div class="element">
                            <h3>Favorites</h3>
                            <p>Look through your favorited events and their organizers</p>
                        </div>
                    </a>
                </div>
            </div>
        </section>
    </div>
</template>

<script>
    export default {

        props: ['user'],

    };
</script>