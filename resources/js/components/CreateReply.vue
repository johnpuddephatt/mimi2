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
        <div v-else-if="uploadProgress">
          <progress :value="uploadProgress" max="100"></progress>
          {{uploadProgress}}% uploaded
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
      uploadProgress: null,
      reply: {
        id: null,
        video: null,
        reply_id: this.reply_id ?? null,
        user_id: this.$user.id
      }
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
      console.log(`${Math.floor(this.reply.video.size/1024)}kB video`);
      axios.post('/log', { 'error': `BEGINNING UPLOAD, ${ platform.description }, size: ${Math.floor(this.reply.video.size/1024)}kB, ` });

      const data = new FormData();
      for (let [key, value] of Object.entries(this.reply)) {
        if (value) data.append(key, value);
      }

      axios({
          method: 'post',
          url: route('reply.create', { lesson: this.$parameters.lesson }),
          data: data,
          headers: {
            'Content-Type': `multipart/form-data; boundary=${data._boundary}`
          },
          onUploadProgress: progressEvent => {
            this.uploadProgress = (Math.round((progressEvent.loaded * 100) / progressEvent.total));
          },
          timeout: 600000 // 10 minutes, matches Nginx and PHP config
        })
        .then(response => {
          axios.post('/log', {'error': `UPLOAD COMPLETE, ${ platform.description }, size: ${Math.floor(this.reply.video.size/1024)}kB, `});
          this.reply = response.data;
          noSleep.disable();
          this.isSaved = true;
        }).catch(error => {
          noSleep.disable();
          this.isSaving = false;
          this.errorToast('Problem uploading please try again');
          axios.post('/log', {'error': `UPLOAD ERROR, ${ platform.description }, ${ JSON.stringify(error) }`});
        });
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
    z-index: 9999;
    .modal-card-foot {
        padding-top: 10px;
        padding-bottom: 10px;
    }
    .field {
        width: 100%;
    }
}
</style>
