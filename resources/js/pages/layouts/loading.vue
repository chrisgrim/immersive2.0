<template>
    <div v-if="loading" class="spinner">
        <div class="area">
            <div class="dot dot-1"></div>
            <div class="dot dot-2"></div>
            <div class="dot dot-3"></div>
        </div>
        <svg xmlns="http://www.w3.org/2000/svg" version="1.1">
            <defs>
                <filter id="goo">
                <feGaussianBlur in="SourceGraphic" stdDeviation="10" result="blur" />
                <feColorMatrix in="blur" mode="matrix" values="1 0 0 0 0  0 1 0 0 0  0 0 1 0 0  0 0 0 21 -7"/>
                </filter>
            </defs>
        </svg>
    </div>
</template>

<script>
  export default {
    props: {
      loading: {
        type: Boolean,
        default: true
      },
    },

    data() {
      return {
        styles: {
          width: this.width + 'px',
          height: this.height + 'px'
        }
      }
    },
    name: "CubeSpinner"
  }
</script>