<template>
    <section 
        class="border-b border-slate-200 py-12 px-8 md:py-16 md:px-0"
        v-if="event.eventreviews ? event.eventreviews.length : null">
        <div
            v-for="review in event.eventreviews"
            :key="review.id">
            <a 
                rel="noreferrer" 
                target="_blank" 
                :href="review.url">
                <div class="flex items-center mb-4">
                    <img 
                        class="w-12 h-12 md:w-8 md:h-8 mr-4" 
                        :alt="review.reviewer_name"
                        :src="review.image_path">
                    <h3 class=""> {{ review.reviewer_name }}'s Review</h3>
                </div>
                <blockquote 
                    style="white-space: pre-wrap;"
                    class="px-8 md:px-0"> {{ review.review.substring(0,300) }}...<span class="show-text"> Read More
                    </span>
                </blockquote>
            </a>
        </div>
    </section>
</template>

<script>
    export default {
        props: [ 'event'],
    }
</script>