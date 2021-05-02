<template>
<div v-if="!isSaved">

  <b-tooltip v-if="reply_id" label="Record admin reply" type="is-dark" animated position="is-left" :delay="1000" class="admin-reply-button--tooltip">
    <b-button class="admin-reply-button" @click="openModal(mode)" size="is-light" icon-right="reply" />
  </b-tooltip>

  <button v-else @click="openModal()" class="button is-primary">Add your reply</button>

  <b-modal custom-class="create-reply-modal" :active.sync="isReplyModalActive" has-modal-card trap-focus :can-cancel="!reply.video && !isRecording" :destroy-on-hide="true" aria-role="dialog" width="420px" aria-modal>

    <div v-if="!replyMode">
      <b-button class="reply-button" @click="replyMode = 'video'" expanded size="is-medium is-primary" icon-right="plus-circle">
        Add video reply
      </b-button>
      <b-button class="reply-button" @click="replyMode = 'audio'" expanded size="is-medium is-primary" icon-right="plus-circle">
        Add audio reply
      </b-button>
      <b-button class="reply-button" @click="replyMode = 'text'" expanded size="is-medium is-primary" icon-right="plus-circle">
        Add text reply
      </b-button>
    </div>

    <div v-else-if="isSaving" class="modal-card">
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
        <camera-field v-if="replyMode == 'video'" @is-recording="isRecording = $event" v-model="reply.video" mode="video"></camera-field>
        <audio-field v-else-if="replyMode == 'audio'" v-model="reply.audio" ></audio-field>
        <tip-tap :max_height="360" v-else v-model="reply.text"></tip-tap>
      </section>
      <footer class="modal-card-foot has-background-light">
        <b-button :disabled="!(reply.video || reply.audio || reply.text)" expanded type="is-primary" size="is-medium" @click.prevent="onSubmit" :loading="isSaving">Post your reply</b-button>
        <p v-if="replyMode != 'text'" class="is-size-7">Start and stop recording with the red record button.</p>
      </footer>
    </div>
  </b-modal>
</div>
</template>

<script>
import NoSleep from 'nosleep.js';
var platform = require('platform');
import CameraField from '@/components/CameraField';
import AudioField from '@/components/AudioField';
import TipTap from '@/components/TipTap';


export default {
  props: ['$parameters', '$user', 'reply_id', 'mode', 'should_open'],
  components: {
    TipTap,
    AudioField,
    CameraField
  },
  data() {
    return {
      isReplyModalActive: false,
      replyMode: null,
      isSaving: false,
      isRecording: false,
      isSaved: false,
      newVideo: {},
      errors: '',
      noSleep: null,
      uploadProgress: null,
      reply: {
        id: null,
        audio: null,
        video: null,
        text: null,
        reply_id: this.reply_id ?? null,
        user_id: this.$user.id
      }
    }
  },

  watch: {
    should_open: function(should_open) {
      if (should_open) {
        this.isReplyModalActive = true;
      }
    }
  },

  mounted() {
    if(this.mode) {
      this.replyMode = this.mode;
    }
  },

  methods: {

    openModal(type) {
      this.replyMode = type;
      this.isReplyModalActive = true;
    },

    onSubmit() {
      this.isSaving = true;
      var noSleep = new NoSleep();
      noSleep.enable();
      if(this.mode != 'text') {
        axios.post('/log', { 'error': `BEGINNING ${this.replyMode } UPLOAD, ${ platform.description }, size: ${Math.floor(this.replyMode == 'video' ? this.reply.video.size/1024 : (this.replyMode == 'audio' ? this.reply.audio.size/1024 : 'N/A'))}kB, ` });
      }

      const data = new FormData();
      for (let [key, value] of Object.entries(this.reply)) {
        if (value) data.append(key, value);
      }

      axios({
          method: 'post',
          url: route('reply.create', { lesson: this.$parameters.lesson, section: this.$parameters.section }),
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
          axios.post('/log', {'error': `${this.replyMode } UPLOAD COMPLETE, ${ platform.description }, size: ${Math.floor(this.replyMode == 'video' ? this.reply.video.size/1024 : (this.replyMode == 'audio' ? this.reply.audio.size/1024 : 'N/A'))}kB, `});
          this.reply = response.data;
          noSleep.disable();
          this.isSaved = true;
          this.$emit('uploaded');
          this.successToast(`Upload successful. Your ${this.replyMode} will appear on the page shortly.`);


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
    // z-index: 9999;
    .modal-card-foot {
        padding-top: 10px;
        padding-bottom: 10px;
    }
    .field {
        width: 100%;
    }
}
</style>
