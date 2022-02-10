<template>
    <div class="max-w-screen-2xl relative">
        <section class="my-8 md:mt-16 md:mb-24">
            <div class="px-8 my-8 md:px-12 lg:px-32 lg:my-12">
                <h2>Account</h2>
                <div class="flex flex-col gap-8 mt-8">
                    <a href="/account-settings/notifications">
                        <div class="border p-8 shadow-custom-5 h-full rounded-2xl">
                            <h3 class="text-2xl font-semibold mb-4">Notifications</h3>
                            <p class="text-2xl text-gray-500">Choose your newsletter and contact preferences</p>
                        </div>
                    </a>
                    <a :href="`/users/${user.id}`">
                        <div class="border p-8 shadow-custom-5 h-full rounded-2xl">
                            <h3 class="text-2xl font-semibold mb-4">Profile</h3>
                            <p class="text-2xl text-gray-500">Update your user information</p>
                        </div>
                    </a>
                    <!-- <a href="/account-settings/favorited">
                        <div class="border p-8 shadow-custom-5 h-full rounded-2xl">
                            <h3>Favorites</h3>
                            <p>Look through your favorited events and their organizers</p>
                        </div>
                    </a> -->
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