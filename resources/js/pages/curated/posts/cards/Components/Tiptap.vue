<template>
    <div 
        class="tiptap-editor" 
        v-if="editor">
        <menu-bar 
            class="editor__header" 
            :editor="editor" />
        <editor-content 
            class="editor__content min-h-[10rem]" 
            :editor="editor" />
        <div class="flex justify-between border-t p-2">
            <button 
                class="rounded-xl py-2 px-4 bg-white text-black hover:bg-black hover:text-white hover:border-white" 
                @click="cancel">
                Cancel
            </button>
            <button 
                class="rounded-xl py-2 px-4 bg-black text-white border-black hover:bg-white hover:text-black"
                :disabled="disabled" 
                @click="save">
                Save
            </button>
        </div>
    </div>
</template>
<script>
    import { Editor, EditorContent } from '@tiptap/vue-2'
    import Link from '@tiptap/extension-link'
    import StarterKit from '@tiptap/starter-kit'
    import MenuBar from './MenuBar.vue'

    export default {

        props: ['community', 'value', 'disabled'],

        components: {EditorContent, MenuBar },

        data() {
            return {
                editor: null,
            }
        },

        methods: {
            save() {
                this.$emit('save', true)
            },
            cancel() {
                this.$emit('cancel', true)
            }
        },

        watch: {
            value(value) {
                const isSame = this.editor.getHTML() === value
                if (isSame) { return }
                this.editor.commands.setContent(this.value, false)
            },
        },

        mounted() {
            this.editor = new Editor({
                extensions: [
                    StarterKit, Link
                ],
                content: this.value,
                onUpdate: () => {
                    this.$emit('input', this.editor.getHTML())
                },
            })
        },

        beforeDestroy() {
            this.editor.destroy()
        },
    }
</script>