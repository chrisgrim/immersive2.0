<template>
    <div class="modal">
        <div 
            ref="modal" 
            class="wrapper">
            <div class="header">
                <button @click="onClose">
                    <svg>
                        <use :xlink:href="`/storage/website-files/icons.svg#ri-close-line`" />
                    </svg>
                    <slot name="header"></slot>
                </button>
            </div>
            <div class="body" >
                <slot name="body">
                    I'm the default body!
                </slot>
            </div>
            <div class="footer">
                <button 
                    class="btn-borderless" 
                    @click="onClose">
                    Close
                </button>
                <slot name="footer"></slot>
            </div>
        </div>
    </div>
</template>
<script>
export default {
    
    methods: {
        close() {
            this.$emit('close');
        },
        onClickOutside(event) {
            let modal =  this.$refs.modal;
            if (!modal || modal.contains(event.target)) return;
            this.$emit('close');
        },
    },

    mounted() {
        setTimeout(() => document.addEventListener("click", this.onClickOutside), 200);
    },

    beforeUnmount() {
        document.removeEventListener("click", this.onClickOutside);
    }
};
</script>