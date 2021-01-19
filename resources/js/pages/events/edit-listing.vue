<template>
	<div>
		<a :href="`/events/${event.slug}/edit`">
	    	<div>
	    		<img :src="eventImg" alt="">
	    	</div>
	    	<div>
	    		{{name}}
	    	</div>
		</a>
    </div>
</template>

<script>

	export default {

        props: {
	        event: {
	            type:Object,
	        },
	    }, 

		data() {
			return {
				eventImg: this.event.thumbImagePath ? '/storage/' + this.event.thumbImagePath : '/storage/default-avatar/default.jpg',
				name: this.event.name ? this.event.name : 'keep editing',
			}
		},

		methods: {
		

		}
    };
</script>