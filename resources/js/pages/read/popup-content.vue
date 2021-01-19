<template>
    <div class="pop">
        <a :href="`/events/${data.slug}?name=${name}&lat=${lat}&lng=${lng}`">
            <div 
                class="back" 
                :style="image" />
            <div class="info">
                <div class="name">
                    {{ data.name }}
                </div>
                <div class="">
                    {{ data.category ? data.category.name : '' }}
                </div>
            </div>
        </a>
    </div>
</template>

<script>
export default {
    name: "PopupContent",
    props: {
        data: {
            type: Object,
        }
    },
    data() {
        return {
            image: '',
            name: new URL(window.location.href).searchParams.get("name"),
            lat: new URL(window.location.href).searchParams.get("lat"),
            lng: new URL(window.location.href).searchParams.get("lng"),
        }
    },
    methods: {
        canUseWebP() {
            let webp = (document.createElement('canvas').toDataURL('image/webp').indexOf('data:image/webp') == 0);
            if (webp) {
                return this.image = `background-image: url('/storage/${this.data.thumbImagePath}')`
            };
            return this.image = `background-image: url('/storage/${this.data.thumbImagePath.slice(0, -4)}jpg')`
        },
    },

    mounted() {
        this.canUseWebP();
    },
};
</script>