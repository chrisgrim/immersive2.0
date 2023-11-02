<template>
    <div class="flex items-center justify-end">
        <!--  -->
        <!-- If user is logged in -->
        <template v-if="user">
            <div 
                class="relative ml-8" 
                v-if="!user.hasCreatedOrganizers">
                <a 
                    :class="onClass.links"
                    href="/events/create">
                    <span>Submit Your Experience (Free)</span>
                </a>
            </div>
            <div 
                class="relative ml-8" 
                v-if="user.hasCreatedOrganizers">
                <a 
                    :class="onClass.links"
                    href="/create/events/edit">
                    <span>Your Events</span>
                </a>
            </div>
            <div class="relative ml-8">
                <ProfileButton :user="user" />
            </div>
        </template>

        <!-- If user is guest -->
        <template v-else>
            <div class="relative ml-8">
                <a 
                    :class="onClass.links"
                    href="/register?create=true">
                    <span>Submit Your Experience (Free)</span>
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

        props:[ 'user', 'onClass' ],

        components: { ProfileButton },

    }
</script>