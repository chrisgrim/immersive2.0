<template>
    <div v-if="visible">
        <div class="modal-box">
            <div class="modal-box__container" ref="panel">
                <div class="modal-box__close">
                    <button @click="hide">
                        <svg>
                            <use :xlink:href="`/storage/website-files/icons.svg#ri-close-circle-line`" />
                        </svg>
                    </button>
                </div>
                <div class="modal-box__body">
                    <h3>{{message}}</h3>
                    <p>Reviews generally take 1-3 business days.</p>
                </div>
                <div class="modal-box__footer">
                    <button @click="hide">Accept</button>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
    export default {

        props: ['message', 'user'],

        computed: {

        },

        data() {
            return {
                visible: true,
                submitted: this.message && this.message == 'submitted' ? true : false,
            }
        },

        methods: {

            onClickOutside(event) {
                let panel =  this.$refs.panel;
                if (!panel || panel.contains(event.target)) return console.log('one');
                this.visible = false;
            },

            hide() {
                this.visible = false;
            }
        },

        mounted() {
            setTimeout(() => this.hide(), 20000);
            setTimeout(() => document.addEventListener("click", this.onClickOutside), 200);
        },

        beforeDestroy() {
            document.removeEventListener("click", this.onClickOutside);
        },

    }
</script>