<template>
<div v-if="!isSaved" :class="reply_id ? '' : 'column is-full is-half-widescreen'">

  <b-tooltip v-if="reply_id" label="Record admin reply" type="is-dark" animated position="is-left" :delay="1000" class="admin-reply-button--tooltip">
    <b-button class="admin-reply-button" @click="isReplyModalActive = true" size="is-light" icon-right="reply" />
  </b-tooltip>

  <div v-else class="card reply-card reply-card__add">
    <b-button class="reply-button" @click="isReplyModalActive = true" expanded size="is-medium is-primary" icon-right="plus-circle">
      Add your reply
    </b-button>
  </div>

  <b-modal custom-class="create-reply-modal" :active.sync="isReplyModalActive" has-modal-card trap-focus :can-cancel="!reply.video && !isRecording" :destroy-on-hide="true" aria-role="dialog" width="420px" aria-modal>
    <div v-if="isSaving" class="modal-card">
      <section class="modal-card-body has-text-centered">
        <div v-if="isSaved">
          <h3 class="title">Ottimo <span class="emoji">✨</span></h3>
          <p class="subtitle">Your reply has been saved</p>
        </div>
        <div v-else>
          <h3 class="title">Un momento <span class="emoji">⌛</span></h3>
          <p class="subtitle">Your reply is uploading</p>
        </div>
        <div class="circle-loader" :class="isSaved? 'load-complete' : ''">
          <div v-if="isSaved" class="checkmark draw"></div>
        </div>
        <div v-if="isSaved">
          <b-button @click.prevent="onModalEnd">Close</b-button>
        </div>
        <div v-else-if="reply.progress">
          {{ reply.progress.percentage }}% uploaded
        </div>
      </section>
    </div>

    <div v-else class="modal-card">
      <section class="modal-card-body is-paddingless" style="overflow: visible;">
        <camera-field @is-recording="isRecording = $event" v-model="reply.video" mode="video"></camera-field>
      </section>
      <footer class="modal-card-foot has-background-light">
        <b-button v-if="reply.video" expanded type="is-primary" size="is-medium" @click.prevent="onSubmit" :loading="isSaving">Upload your reply</b-button>
        <p class="is-size-7" v-else>Start and stop recording with the red record button.</p>
      </footer>
    </div>
  </b-modal>
</div>
</template>

<script>
import NoSleep from 'nosleep.js';
var platform = require('platform');
import CameraField from '@/components/CameraField';

export default {
  props: ['$parameters', '$user', 'reply_id', 'should_open'],
  components: {
    CameraField
  },
  data() {
    return {
      isReplyModalActive: false,
      isSaving: false,
      isRecording: false,
      isSaved: false,
      newVideo: {},
      errors: '',
      noSleep: null,
      reply: this.$inertia.form({
        id: null,
        video: null,
        reply_id: this.reply_id ?? null,
        user_id: this.$user.id
      })
    }
  },

  watch: {
    isReplyModalActive: function() {
      if (this.$root.$refs.instructionVideo) {
        this.$root.$refs.instructionVideo.pause();
      }
    },
    should_open: function(should_open) {
      if (should_open) {
        this.isReplyModalActive = true;
      }
    }
  },

  methods: {

    onSubmit() {
      this.isSaving = true;
      var noSleep = new NoSleep();
      noSleep.enable();
      axios.post('/log', {
        'error': `BEGINNING UPLOAD, ${ platform.description }, size: ${Math.floor(this.reply.video.size/1024)}kB, `
      });

      this.reply.post(route('reply.create', { lesson: this.$parameters.lesson }), {
        preserveScroll: true,
        onSuccess: () => {

          this.successToast('Your reply has been posted.');
          axios.post('/log', {
            'error': `UPLOAD COMPLETE, ${ platform.description }, size: ${Math.floor(this.reply.video.size/1024)}kB, `
          });
          noSleep.disable();
          this.isSaved = true;
        },
        onError: errors => {
          noSleep.disable();
          this.isSaving = false;
          this.errorToast('An error has occured');
          console.log(errors);
          // var uploadErrorMessage = 'Unknown error';
          // if (error.response) {
          //   uploadErrorMessage = (error.response.data && error.response.data.message) ? error.response.data.message : 'Server reponse error. Let us know and we’ll look into the problem for you.'
          // } else if (error.request) {
          //   uploadErrorMessage = (error.request.data && error.request.data.message) ? error.request.data.message : 'Network request error. Either your internet connection is unstable or the upload timed out.'
          // } else if (error.message) {
          //   uploadErrorMessage = error.message;
          // }
          // this.errorToast(uploadErrorMessage);
          // axios.post('/log', {
          //   'error': `UPLOAD ERROR ${ platform.description }, Error: ${ uploadErrorMessage }, ${ error }, Reply data: ${JSON.stringify(this.reply)},`
          // });
        },
      })
    },

    onModalEnd() {
      this.isReplyModalActive = false;
      this.isSaving = false;
      this.errors = null;
    }
  }
}
</script>

<style lang="scss">
.create-reply-modal {
    .modal-card-foot {
        padding-top: 10px;
        padding-bottom: 10px;
    }
    .field {
        width: 100%;
    }
}
</style>
