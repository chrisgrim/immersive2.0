<template>
    <div>
        <div class="social">
            <a 
                rel="noreferrer noopener" 
                target="_blank" 
                :href="`https://www.twitter.com/${organizer.twitterHandle}`" 
                v-if="organizer.twitterHandle">
                <svg>
                    <use :xlink:href="`/storage/website-files/icons.svg#ri-twitter-line`" />
                </svg>
                <span>{{ organizer.twitterHandle }}</span>
            </a>
            <a 
                rel="noreferrer noopener" 
                target="_blank" 
                :href="`https://www.facebook.com/${organizer.facebookHandle}`" 
                v-if="organizer.facebookHandle">
                <svg>
                    <use :xlink:href="`/storage/website-files/icons.svg#ri-facebook-line`" />
                </svg>
                <span>{{ organizer.facebookHandle }}</span>
            </a>
            <a 
                rel="noreferrer noopener" 
                target="_blank" 
                :href="`https://www.instagram.com/${organizer.instagramHandle}`" 
                v-if="organizer.instagramHandle">
                <svg>
                    <use :xlink:href="`/storage/website-files/icons.svg#ri-instagram-line`" />
                </svg>
                <span>{{ organizer.instagramHandle }}</span>
            </a>
            <a 
                rel="noreferrer noopener" 
                target="_blank" 
                :href="`${organizer.website}`" 
                v-if="organizer.website">
                <svg>
                    <use :xlink:href="`/storage/website-files/icons.svg#ri-global-line`" />
                </svg>
                <span>{{ organizer.name }}</span>
            </a>
        </div>
        <template v-if="organizer.patreon">
            <div class="patreon">
                <a 
                    target="_blank" 
                    rel="noreferrer noopener"
                    :href="`https://www.patreon.com/${organizer.patreon}`">
                    <button class="patreon">
                        Back {{ organizer.name }} on Patreon
                    </button>
                </a>
            </div>
        </template>
    </div>
</template>

<script>
    export default {

        props: ['organizer'],
        
    };
</script>