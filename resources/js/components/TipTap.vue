<template>
  <div class="editor is-bordered">
    <editor-menu-bar :editor="editor" v-slot="{ commands, isActive }">
      <div class="p-1 is-flex has-background-white-ter ">
        <button
          class="button mr-1"
          :class="{ 'bg-gray-200': isActive.bold() }"
          @click.prevent="commands.bold">
          <svg class="tiptap-icon" viewBox="0 0 24 24" id="icon--bold"><path d="M17.194 10.962A6.271 6.271 0 0012.844.248H4.3a1.25 1.25 0 000 2.5h1.013a.25.25 0 01.25.25V21a.25.25 0 01-.25.25H4.3a1.25 1.25 0 100 2.5h9.963a6.742 6.742 0 002.93-12.786zm-4.35-8.214a3.762 3.762 0 010 7.523H8.313a.25.25 0 01-.25-.25V3a.25.25 0 01.25-.25zm1.42 18.5H8.313a.25.25 0 01-.25-.25v-7.977a.25.25 0 01.25-.25h5.951a4.239 4.239 0 010 8.477z"></path></svg>
        </button>

        <button
          class="button mr-1"
          :class="{ 'bg-gray-200': isActive.italic() }"
          @click.prevent="commands.italic">
            <svg class="tiptap-icon" viewBox="0 0 24 24" id="icon--italic"><path d="M22.5.248h-7.637a1.25 1.25 0 000 2.5h1.086a.25.25 0 01.211.384L4.78 21.017a.5.5 0 01-.422.231H1.5a1.25 1.25 0 000 2.5h7.637a1.25 1.25 0 000-2.5H8.051a.25.25 0 01-.211-.384L19.22 2.98a.5.5 0 01.422-.232H22.5a1.25 1.25 0 000-2.5z"></path></svg>
        </button>

        <button
          class="button mr-1"
          :class="{ 'bg-gray-200': isActive.bullet_list() }"
          @click.prevent="commands.bullet_list">
          <svg class="tiptap-icon" viewBox="0 0 24 24" id="icon--ul"><circle cx="2.5" cy="3.998" r="2.5"></circle><path d="M8.5 5H23a1 1 0 000-2H8.5a1 1 0 000 2z"></path><circle cx="2.5" cy="11.998" r="2.5"></circle><path d="M23 11H8.5a1 1 0 000 2H23a1 1 0 000-2z"></path><circle cx="2.5" cy="19.998" r="2.5"></circle><path d="M23 19H8.5a1 1 0 000 2H23a1 1 0 000-2z"></path></svg>
        </button>

        <button
          class="button mr-1"
          :class="{ 'bg-gray-200': isActive.ordered_list() }"
          @click.prevent="commands.ordered_list">
          <svg class="tiptap-icon" viewBox="0 0 24 24" id="icon--ol"><path d="M7.75 4.5h15a1 1 0 000-2h-15a1 1 0 000 2zm15 6.5h-15a1 1 0 100 2h15a1 1 0 000-2zm0 8.5h-15a1 1 0 000 2h15a1 1 0 000-2zM2.212 17.248a2 2 0 00-1.933 1.484.75.75 0 101.45.386.5.5 0 11.483.63.75.75 0 100 1.5.5.5 0 11-.482.635.75.75 0 10-1.445.4 2 2 0 103.589-1.648.251.251 0 010-.278 2 2 0 00-1.662-3.111zm2.038-6.5a2 2 0 00-4 0 .75.75 0 001.5 0 .5.5 0 011 0 1.031 1.031 0 01-.227.645L.414 14.029A.75.75 0 001 15.248h2.5a.75.75 0 000-1.5h-.419a.249.249 0 01-.195-.406L3.7 12.33a2.544 2.544 0 00.55-1.582zM4 5.248h-.25A.25.25 0 013.5 5V1.623A1.377 1.377 0 002.125.248H1.5a.75.75 0 000 1.5h.25A.25.25 0 012 2v3a.25.25 0 01-.25.25H1.5a.75.75 0 000 1.5H4a.75.75 0 000-1.5z"></path></svg>
        </button>
      </div>
    </editor-menu-bar>

    <editor-content  :editor="editor" />
  </div>
</template>

<script>
import { Editor, EditorContent, EditorMenuBar } from 'tiptap'
import {
  HardBreak,
  OrderedList,
  BulletList,
  ListItem,
  Bold,
  Italic,
  Link,
} from 'tiptap-extensions'

export default {
  components: {
    EditorContent,
    EditorMenuBar
  },
  props: ['value'],
  data() {
    return {
      editor: new Editor({
        extensions: [
          new BulletList(),
          new HardBreak(),
          new ListItem(),
          new OrderedList(),
          new Link(),
          new Bold(),
          new Italic(),
        ],
        onUpdate: ({getHTML}) => {
          const state = getHTML()
          this.$emit('input', state)
        },
        content: this.value,
      }),
    }
  },
  mounted() {
      let editorClasses = ['p-4', 'content'];
      this.editor.view.dom.classList.add(...editorClasses);
  },
  beforeDestroy() {
    this.editor.destroy()
  },
}
</script>


<style lang="scss">
@import "../../sass/variables";

.editor {
  border-color: $grey-lighter !important;
  border-radius: $radius;
}
.tiptap-icon {
  width: 16px;
  height: 16px;
}

.ProseMirror {
  min-height: 300px;
}
</style>
