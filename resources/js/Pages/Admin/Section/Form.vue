<template>
<app-layout>
  <div class="column is-9-tablet is-9-desktop is-7-widescreen">
    <a class="back-link has-text-dark" @click="confirmClose">&larr; Back to lesson</a>

    <div class="box p-5">
      <div class="mt-6 mb-5 is-flex is-align-items-center" style="max-width: 650px; margin-left: auto; margin-right: auto;">
        <b-input type="textarea" rows="2" @input="isDirty = true" v-model="form.title" custom-class="p-0 f-0 is-size-2 has-text-weight-semibold is-borderless modal-card-title" style="width: 100%;" placeholder="Enter section title..."/>
      </div>
      <div class="mt-6 mb-0" id="editorjs" spellcheck="false"></div>
    </div>

    <div class="section-controls is-fixed-bottom py-3 has-background-white">
      <div class="container fields is-flex is-justify-content-flex-end is-align-content-center">
        <b-checkbox @input="isDirty = true" v-model="form.is_chatroom">Enable chatroom?</b-checkbox>
        <b-button label="Save" type="is-primary is-medium" :loading="form.processing" :disabled="!form.title || !isDirty" class="ml-2" @click.prevent="onSubmit" />
      </div>
    </div>

  </div>
</app-layout>
</template>

<script>
import EditorJS from '@editorjs/editorjs';
import List from '@editorjs/list';
// import Table from '@editorjs/table';
import Table from 'editorjs-table';
import Warning from '@editorjs/warning';
import ImageTool from '@editorjs/image';
import VideoTool from 'editorjs-video-jdp';
import AudioTool from 'editorjs-attaches-audio';
import PairedHeading from 'editorjs-pairedheading';
import MarkerTool from '@/components/editorjs/plugins/Translate';

export default {
  props: ['errors', 'data', '$parameters'],

  data() {
    return {
      isDirty: false,
      form: this.$inertia.form({
        id: this.data?.id ?? null,
        title: this.data?.title ?? null,
        is_chatroom: this.data?.is_chatroom ?? false,
      }),
      editor: null
    }
  },

  destroyed() {
    this.editor.destroy();
  },

  mounted() {

   this.editor = new EditorJS({
     holder: 'editorjs',
     data: this.parsedContent,
     placeholder: 'Start writing!',
     tools: this.toolConfig(),

     onChange: () => {
       this.isDirty = true
     },

     onReady: () => {},
   })

  },
  updated() {

  },

  computed: {
    parsedContent() {
      if (this.data) {
        try {
          return JSON.parse(this.data.content || '{}');
        } catch (e) {
          this.errorToast('Could not read existing content.')
          return {};
        }
      } else {
        // Return empty object if no existing content.
        return {};
      }
    }
  },

  beforeMount() {
    window.addEventListener("beforeunload", this.preventNav)
  },

  beforeDestroy() {
    window.removeEventListener("beforeunload", this.preventNav);
  },

  methods: {

    preventNav(event) {
      event.preventDefault()
      event.returnValue = ""
    },

    confirmClose() {
      if (this.isDirty) {
        this.$buefy.dialog.confirm({
          message: '<strong>Are you sure?</strong> Unsaved changes will be lost.',
          onConfirm: () => this.$inertia.visit(route('lesson.edit', {
            course: this.$parameters.course,
            week: this.$parameters.week,
            lesson: this.$parameters.lesson
          }))
        })
      } else {
        this.$inertia.visit(route('lesson.edit', {
          course: this.$parameters.course,
          week: this.$parameters.week,
          lesson: this.$parameters.lesson
        }))
      }
    },

    onSubmit() {
      this.editor.save().then((outputData) => {

        let postRoute = this.data ? route('section.update', {
          course: this.$parameters.course,
          week: this.$parameters.week,
          lesson: this.$parameters.lesson,
          section: this.data.id
        }) : route('section.store', {
          course: this.$parameters.course,
          week: this.$parameters.week,
          lesson: this.$parameters.lesson,
        })

        this.form.transform((data) => ({
            ...data,
            _method: (this.data ? 'PUT' : 'POST'),
            content: JSON.stringify(outputData)
          }))
          .post(postRoute, {
            preserveScroll: true,
            onSuccess: () => {
              this.isDirty = false;
              this.successToast('Section saved');
            },
            onError: errors => {
              this.errorToast('Check the form for errors');
            },
          })

      }).catch((error) => {
        console.log(error);
        this.errorToast(`Error saving editor content`)
      });
    },

    toolConfig() {
      return {
        markerTool: MarkerTool,

        pairedHeading: PairedHeading,
        list: List,
        audio: {
          class: AudioTool,
          config: {
            field: 'audio',
            types: 'audio/*',
            endpoint: route('upload.store'),
            additionalRequestHeaders: {
              'X-CSRF-TOKEN': window.axios.defaults.headers.common['X-CSRF-TOKEN']
            }
          }
        },
        image: {
          class: ImageTool,
          config: {
            types: 'image/*',
            endpoints: {
              byFile: route('upload.store'), // Your backend file uploader endpoint
            },
            additionalRequestHeaders: {
              'X-CSRF-TOKEN': window.axios.defaults.headers.common['X-CSRF-TOKEN']
            }
          }
        },
        video: {
          class: VideoTool,
          config: {
            types: 'video/*',
            endpoints: {
              byFile: route('upload.store'), // Your backend file uploader endpoint
            },
            additionalRequestHeaders: {
              'X-CSRF-TOKEN': window.axios.defaults.headers.common['X-CSRF-TOKEN']
            }
          }
        },
        table: {
          class: Table,
          inlineToolbar: true,
        },
        warning: Warning,
      }
    }
  }
}
</script>

<style lang="scss">
@import "../../../../sass/variables";

.section-controls {
  position: fixed;
  border-top: 1px solid $grey-lightest;
  box-shadow: 0 0.5em 1em -0.125em rgb(10 10 10 / 10%), 0 0px 0 1px rgb(10 10 10 / 2%);

  z-index: 999;
  bottom: 0;
  left: 0;
  right: 0;
}

.codex-editor {
    .icon {
        width: 0.75rem;
        height: 0.75rem;
    }

    .ce-paragraph {
      line-height: inherit;
    }

    .image-tool {
      margin: 2em 0;
    }

    ol, ul {
      margin-bottom: 1em;
    }
}

.f-0 {
    outline: none;
    box-shadow: none;

    &:focus {
        outline: none;
        box-shadow: none;
    }
}

textarea {
  resize: none;
}
</style>
