<template>
	<input type="file" accept="image/*" class="fileinput" @change="onChange">
</template>

<script>
	export default {
		methods: {
			onChange(e) {
				if (! e.target.files.length) return;
				let file = e.target.files[0];
				let reader = new FileReader();
				reader.readAsDataURL(file);
				reader.onload = e => {
					let src = e.target.result;

					const img = new Image();

					img.onload = () => {
                        this.$emit('loaded', {src, file, width: img.width, height: img.height});
                    };

                    img.onerror = () => {
                        this.$emit('error');
                    };
                    img.src = src;
				};
			}
		}
	}
</script>