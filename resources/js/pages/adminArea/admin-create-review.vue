<template>
    <div class="rev">
        <div class="editor">
            <editor-floating-menu :editor="editor" v-slot="{ commands, isActive, menu }">
                <div
                    class="editor__floating-menu"
                    :class="{ 'is-active': menu.isActive }"
                    :style="`left: ${menu.left}px; bottom: ${menu.bottom}px;`">
                    <button
                        class="menubar__button"
                        :class="{ 'is-active': isActive.bold() }"
                        @click="commands.bold">
                        B
                    </button>
                    <button
                        class="menubar__button"
                        :class="{ 'is-active': isActive.italic() }"
                        @click="commands.italic">
                        /
                    </button>
                    <button
                        class="menubar__button"
                        :class="{ 'is-active': isActive.code() }"
                        @click="commands.code">
                        ''
                    </button>
                    <button
                        class="menubar__button"
                        :class="{ 'is-active': isActive.paragraph() }"
                        @click="commands.paragraph">
                        p
                    </button>

                    <button
                        class="menubar__button"
                        :class="{ 'is-active': isActive.heading({ level: 1 }) }"
                        @click="commands.heading({ level: 1 })">
                      h1
                    </button>
                     <button
                      class="menubar__button"
                      :class="{ 'is-active': isActive.heading({ level: 1 }) }"
                      @click="commands.heading({ level: 2 })"
                    >
                      h2
                    </button>
                     <button
                      class="menubar__button"
                      :class="{ 'is-active': isActive.heading({ level: 1 }) }"
                      @click="commands.heading({ level: 3 })"
                    >
                      h3
                    </button>
                    <button
                        class="menubar__button"
                        @click="showImageModal(commands.image)"
                        >
                        image
                    </button>
                </div>
            </editor-floating-menu>
            <editor-menu-bubble :editor="editor" :keep-in-bounds="keepInBounds" v-slot="{ commands, isActive, menu }">
                <div
                    class="menububble"
                    :class="{ 'is-active': menu.isActive }"
                    :style="`left: ${menu.left}px; bottom: ${menu.bottom}px;`"
                >

                    <button
                      class="menububble__button"
                      :class="{ 'is-active': isActive.bold() }"
                      @click="commands.bold"
                    >
                      B
                    </button>
                    <button
                      class="menububble__button"
                      :class="{ 'is-active': isActive.italic() }"
                      @click="commands.italic"
                    >
                      /
                    </button>
                    <button
                      class="menububble__button"
                      :class="{ 'is-active': isActive.code() }"
                      @click="commands.code"
                    >
                      ''
                    </button>

                    <button
                      class="menububble__button"
                      :class="{ 'is-active': isActive.paragraph() }"
                      @click="commands.paragraph"
                    >
                      p
                    </button>

                    <button
                      class="menububble__button"
                      :class="{ 'is-active': isActive.heading({ level: 1 }) }"
                      @click="commands.heading({ level: 1 })"
                    >
                      h1
                    </button>
                     <button
                      class="menububble__button"
                      :class="{ 'is-active': isActive.heading({ level: 2 }) }"
                      @click="commands.heading({ level: 2 })"
                    >
                      h2
                    </button>
                     <button
                      class="menububble__button"
                      :class="{ 'is-active': isActive.heading({ level: 3 }) }"
                      @click="commands.heading({ level: 3 })"
                    >
                      h3
                    </button>
                </div>
            </editor-menu-bubble>
            <editor-content class="editor__content" :editor="editor" />
        </div>
    </div>
</template>

<script>
    import Modal from "./imagemodal";
    import { Editor, EditorContent, EditorMenuBubble, EditorFloatingMenu  } from 'tiptap'
    import { Blockquote, BulletList, CodeBlock, HardBreak, Heading, ListItem, OrderedList, TodoItem, TodoList, Image, Bold, Code, Italic, Link, History} from 'tiptap-extensions'
    import { required } from 'vuelidate/lib/validators';

    export default {

        components: { EditorContent, EditorMenuBubble, EditorFloatingMenu, Modal},

        data() {
            return {
            json: '',
            html: '',
            keepInBounds: true,
              editor: new Editor({
                extensions: [
                  new Blockquote(),
                  new BulletList(),
                  new CodeBlock(),
                  new HardBreak(),
                  new Image(),
                  new Heading({ levels: [1, 2, 3] }),
                  new ListItem(),
                  new OrderedList(),
                  new TodoItem(),
                  new TodoList(),
                  new Link(),
                  new Bold(),
                  new Code(),
                  new Italic(),
                  new History(),
                ],
                content: `
                  <h2>
                    Medium Type Editor
                  </h2>
                  <p>
                    This is an example of a medium-like editor. Enter a new line and some buttons will appear.
                  </p>
                `,
                onUpdate: ({ getJSON, getHTML }) => {
                  this.json = getJSON()
                  this.html = getHTML()
                },
              }),
            }
          },

        computed: {
            
        },

        methods: {
            showImagePrompt(command) {
                const src = prompt('Enter the url of your image here')
                if (src !== null) {
                command({ src })
                }
            },
            showImageModal(command) {
                console.log('will add image here');
            },

            
        },

        // mounted() {
        //     this.editor = new Editor({
        //         content: '<p>This is just a boring paragraph</p>',
        //     })
        // },

        beforeDestroy() {
            this.editor.destroy()
        },

        validations: {

        },
    }

</script>