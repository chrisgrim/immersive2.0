<template>
    <div class="internal-card">
        
    </div>
</template>

<script>
    import Tiptap from '../../../components/Tiptap.vue'
    export default {
        
        props: [ 'value' ],

        components: { Tiptap },

        computed: {

        },

        data() {
            return {
                card: this.initializeCardObject(),
            }
        },

        methods: {
            
            initializeCardObject() {
                this.card = {
                    name: null,
                    blurb: null,
                    url: null,
                    thumbImagePath: null,
                }
            }
        }

    }
</script>