<template>
    <div role="tabpanel" v-if="isActive" id="grid">
        <slot></slot>
    </div>
</template>

<script>
export default {
    props: {
        title: {},
        active: { type: Boolean, default: false },
        id: {},
        notification: {},
    },
    data() {
        return {
            isActive: this.active
        };
    }
};
</script>