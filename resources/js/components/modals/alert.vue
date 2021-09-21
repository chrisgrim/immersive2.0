<template>
    <div class="modal alert">
        <div 
            ref="alert"
            class="wrapper">
            <div class="header">
                <button @click="onClose">
                    <svg>
                        <use :xlink:href="`/storage/website-files/icons.svg#ri-close-line`" />
                    </svg>
                </button>
            </div>
            <div class="body">
                <slot></slot>
                <h3>{{ message }}</h3>
                <p>Reviews generally take 1-3 business days.</p>
            </div>
            <div class="footer">
                <button 
                    class="btn-borderless" 
                    @click="onClose">
                    Close
                </button>
            </div>
        </div>
    </div>
</template>

<script>
    export default {

        props: ['message'],

        data() {
            return {
                isDisabled: false,
            }
        },

        methods: {
            onClose() {
                this.$emit('close', true)
            },
            onClickOutside(event) {
                let arr =  this.$refs.alert;
                if (!arr || arr.contains(event.target)) return
                this.onClose()
            },
        },

        mounted() {
            setTimeout(() => this.onClose(), 20000);
            setTimeout(() => document.addEventListener("click", this.onClickOutside), 200)
        },

        destroyed() {
            document.removeEventListener("click", this.onClickOutside)
        },

    }
</script>