<template>
    <div>
        <div class="flex flex-col mb-8 p-4">
            <a 
                class="flex items-center mb-2" 
                rel="noreferrer noopener" 
                target="_blank"
                :href="`https://www.twitter.com/${organizer.twitterHandle}`" 
                v-if="organizer.twitterHandle">
                <svg class="m-2 w-10 h-10">
                    <use :xlink:href="`/storage/website-files/icons.svg#ri-twitter-line`" />
                </svg>
                <p>{{ organizer.twitterHandle }}</p>
            </a>
            <a 
                class="flex items-center mb-2" 
                rel="noreferrer noopener" 
                target="_blank" 
                :href="`https://www.facebook.com/${organizer.facebookHandle}`" 
                v-if="organizer.facebookHandle">
                <svg class="m-2 w-10 h-10">
                    <use :xlink:href="`/storage/website-files/icons.svg#ri-facebook-line`" />
                </svg>
                <p>{{ organizer.facebookHandle }}</p>
            </a>
            <a 
                class="flex items-center mb-2" 
                rel="noreferrer noopener" 
                target="_blank" 
                :href="`https://www.instagram.com/${organizer.instagramHandle}`" 
                v-if="organizer.instagramHandle">
                <svg class="m-2 w-10 h-10">
                    <use :xlink:href="`/storage/website-files/icons.svg#ri-instagram-line`" />
                </svg>
                <p>{{ organizer.instagramHandle }}</p>
            </a>
            <a 
                class="flex items-center mb-2" 
                rel="noreferrer noopener" 
                target="_blank" 
                :href="`${organizer.website}`" 
                v-if="organizer.website">
                <svg class="m-2 w-10 h-10">
                    <use :xlink:href="`/storage/website-files/icons.svg#ri-global-line`" />
                </svg>
                <p>{{ organizer.name }}</p>
            </a>
        </div>
        <template v-if="organizer.patreon">
            <a 
                class="flex items-center mb-2" 
                target="_blank" 
                rel="noreferrer noopener"
                :href="`https://www.patreon.com/${organizer.patreon}`">
                <button class="uppercase text-lg font-bold bg-default-red border-none max-w-xs text-white">
                    Back {{ organizer.name }} on Patreon
                </button>
            </a>
        </template>
    </div>
</template>

<script>
    export default {
        props: ['organizer']
    };
</script>