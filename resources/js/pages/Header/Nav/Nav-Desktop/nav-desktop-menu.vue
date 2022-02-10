<template>
    <div class="flex items-center justify-end">
        <!--  -->
        <!-- If user is logged in -->
        <template v-if="user">
            <div 
                class="relative ml-8" 
                v-if="!user.hasCreatedOrganizers">
                <a href="/events/create">
                    <div class="text-1xl font-medium hover:text-black hover:font-semibold">Submit Your Experience (Free)</div>
                </a>
            </div>
            <div 
                class="relative ml-8" 
                v-if="user.hasCreatedOrganizers">
                <a href="/create/events/edit">
                    <div class="text-1xl font-medium hover:text-black hover:font-semibold">Your Events</div>
                </a>
            </div>
            <div class="relative ml-8">
                <ProfileButton :user="user" />
            </div>
        </template>

        <!-- If user is guest -->
        <template v-else>
            <div class="relative ml-8">
                <a href="/register?create=true">
                    <div class="text-1xl font-medium hover:text-black hover:font-semibold">Submit Your Experience (Free)</div>
                </a>
            </div>
            <div class="relative ml-8">
                <ProfileButton />
            </div>
        </template>
    </div>
</template>

<script>
    import ProfileButton from './nav-desktop-profile.vue'
    export default {

        props:[ 'user' ],

        components: { ProfileButton },

    }
</script>