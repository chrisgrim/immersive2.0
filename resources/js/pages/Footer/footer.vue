<template>
    <div v-if="visible">
        <div class="px-28 h-32 py-12 bg-white border-t-[1px] flex justify-between">
            <div>
                <div class="inline-block mx-2">
                    <p class="text-lg">A project of The Immersive Experience Institute Inc.</p>
                </div>
                <span>&#183;</span>
                <div class="inline-block mx-2">
                    <a href="/privacy-policy">
                        <p class="text-lg">Privacy Policy</p>
                    </a>
                </div>
                <span>&#183;</span>
                <div class="inline-block mx-2">
                    <a href="/terms">
                        <p class="text-lg">Terms and Conditions</p>
                    </a>
                </div>
                <span>&#183;</span>
                <div class="inline-block mx-2">
                    <a href="/disclaimer">
                        <p class="text-lg">Disclaimer</p>
                    </a>
                </div>
                <span>&#183;</span>
                <div class="inline-block mx-2">
                    <a 
                        rel="noreferrer noopener" 
                        target="_blank" 
                        href="https://www.facebook.com/groups/everythingimmersive">
                        <p class="text-lg">Join us on Facebook.</p>
                    </a>
                </div>
            </div>
            <div>
                <div class="inline-block mx-2">
                    <a 
                        rel="noreferrer noopener" 
                        target="_blank" 
                        href="https://likelyrain.com/">
                        <p class="text-lg">Site by Likely Rain.</p>
                    </a>
                </div>
            </div>
        </div> 
    </div>
</template>

<script>
    export default {
        props: {
            visible: {
                type: Boolean,
                default: true
            },
        },
    };
</script>