<template>
    <transition name="modal-fade">
        <div class="modal-backdrop">
            <div 
                ref="modal" 
                :class="modalclass" 
                class="modal" 
                role="dialog" 
                aria-labelledby="modalTitle" 
                aria-describedby="modalDescription">
                <header 
                    class="modal-header" 
                    id="modalTitle">
                    <slot name="header">
                        This is the default tile!
                    </slot>
                </header>
                <section 
                    class="modal-body" 
                    id="modalDescription">
                    <slot name="body">
                        I'm the default body!
                    </slot>
                </section>
                <footer class="modal-footer">
                    <button 
                        class="btn" 
                        @click="close" 
                        aria-label="Close modal">
                        Cancel
                    </button>
                    <slot name="footer">
                        I'm the default footer!
                        <button 
                            type="button" 
                            class="btn" 
                            @click="close" 
                            aria-label="Close modal">
                            Close me!
                        </button>
                    </slot>
                </footer>
            </div>
        </div>
    </transition>
</template>
<script>
export default {
    name: 'Modal',
    props: ['modalclass'],
    methods: {
        close() {
            this.$emit('close');
        },
        onClickOutside(event) {
            let temp =  this.$refs.modal;
            if (!temp || temp.contains(event.target)) return;
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