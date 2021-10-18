<template>
    <section v-if="event.eventreviews ? event.eventreviews.length : null">
        <div class="element">
            <div 
                class="box es__reviews" 
                v-for="review in event.eventreviews"
                :key="review.id">
                <a 
                    rel="noreferrer" 
                    target="_blank" 
                    :href="review.url">
                    <div class="event-show-review__right--image">
                        <img 
                            width="33px" 
                            height="33px" 
                            :alt="review.reviewer_name"
                            :src="review.image_path">
                    </div>
                    <div class="event-show-review__right--name">
                        <h4> {{ review.reviewer_name }}'s Review</h4>
                    </div>
                </a>
                <div class="review">
                    <a 
                        rel="noreferrer" 
                        target="_blank" 
                        :href="review.url">                 
                        <blockquote 
                            style="white-space: pre-wrap;"
                            class="text"> {{ review.review.substring(0,300) }}...<span class="show-text"> Read More
                            </span>
                        </blockquote>
                    </a>
                </div>
            </div>
        </div>
    </section>
</template>

<script>
    export default {

        props: [ 'event'],

        data() {
            return {
                titleFontSize: '',
            }
        },

    }
</script>