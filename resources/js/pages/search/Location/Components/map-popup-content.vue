<template>
    <div class="w-full min-h-[16rem]">
        <a :href="`/events/${data.slug}?name=${name}&lat=${lat}&lng=${lng}`">
            <div 
                class="h-48 bg-cover bg-no-repeat border-t-2xl" 
                :style="image" />
            <div class="p-4 flex flex-col">
                <span class="text-xl m-0 text-black">{{ data.name }}</span>
                <span class="text-lg m-0">{{ data.category ? data.category.name : '' }}</span>
            </div>
        </a>
    </div>
</template>

<script>
export default {
    name: "PopupContent",
    props: ['data'],
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