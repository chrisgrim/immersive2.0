<template>
    <button
        class="menu-item"
        :class="{ 'is-active': isActive ? isActive(): null }"
        @click="action"
        :title="title">
        <svg class="remix">
            <use :xlink:href="`/storage/website-files/icons.svg#ri-${icon}`" />
        </svg>
    </button>
</template>

<script>
export default {
  props: {
    icon: {
        type: String,
        required: true,
    },

    title: {
        type: String,
        required: true,
    },

    action: {
        type: Function,
        required: true,
    },

    isActive: {
        type: Function,
        default: null,
    },
  },
}
</script>