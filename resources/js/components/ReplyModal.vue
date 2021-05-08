<template>
  <b-modal :active="is_open" @close="$emit('close-reply-modal')" ref="replyModal" custom-class="has-background-white-bis reply-card-modal" has-modal-card trap-focus :destroy-on-hide="true" animation="zoom-in" aria-role="dialog" width="840px" aria-modal>

    <div class="carousel-wrapper column is-two-thirds is-paddingless">
      <div v-if="reply.feedback" class="feedback-toggle field has-addons">
        <p class="control">
          <button class="button is-small" :class="currentSlide == 0 ? 'is-selected is-success' : ''" @click.prevent="currentSlide = 0">Student response</button>
        </p>
        <p class="control">
          <button class="button is-small" :class="currentSlide == 1 ? 'is-selected is-success' : ''" @click.prevent="currentSlide = 1">Teacher feedback</button>
        </p>
      </div>
      <transition name="fade-up">
        <b-button type="is-primary" icon-right="play" v-if="media_stopped && reply.feedback && (currentSlide == 0)" @click.prevent="currentSlide = 1" size="is-medium" class="feedback-button">
          See teacher feedback
        </b-button>
      </transition>

      <b-carousel animated="fade" @change="updateSlide($event)" :arrow="false" :indicator="false" :has-drag="false" v-model="currentSlide" :autoplay="false" icon-size="is-medium">
        <b-carousel-item key="reply">
          <video-player v-if="reply.type == 'video' && reply.video" @playing="media_stopped = null" @stopped="media_stopped = 'reply'" :should_autoplay="currentSlide == 0" :source="reply.video.playlist_path" :poster="reply.video.thumbnail_path" type="application/x-mpegURL"></video-player>
          <audio-player v-else-if="reply.type == 'audio' && reply.audio" :source="reply.audio" @playing="media_stopped = null" @stopped="media_stopped = 'reply'" :should_autoplay="currentSlide == 0" v-else />
          <div class="image is-square m-0" v-else-if="reply.type == 'text'">
            <div class="text-preview">
              <div v-html="reply.text" class="content text-preview--inner"></div>
            </div>
          </div>
          <div class="image is-square m-0" v-else>
            <b-loading class="reply-loading" :is-full-page="false" :active="true"></b-loading>
          </div>
        </b-carousel-item>
        <b-carousel-item v-if="reply.feedback" key="feedback">
          <video-player v-if="reply.feedback.type == 'video' && reply.feedback.video" @playing="media_stopped = null" @stopped="media_stopped = 'feedback'" :should_autoplay="currentSlide == 1" :source="reply.feedback.video.playlist_path" :poster="reply.feedback.video.thumbnail_path" type="application/x-mpegURL"></video-player>
          <audio-player v-else-if="reply.feedback.type == 'audio' && reply.feedback.audio" :source="reply.feedback.audio" @playing="media_stopped = null" @stopped="media_stopped = 'feedback'" :should_autoplay="currentSlide == 1" />
          <div class="image is-square m-0" v-else-if="reply.feedback.type == 'text'">
            <div class="text-preview">
              <div v-html="reply.feedback.text" class="content text-preview--inner"></div>
            </div>
          </div>
          <div class="image is-square m-0" v-else>
            <b-loading class="reply-loading" :is-full-page="false" :active="true"></b-loading>
          </div>
        </b-carousel-item>
      </b-carousel>
    </div>

    <div class="modal-card">
      <header class="modal-card-head is-radiusless">
        <div class="reply-author" @click="$emit('openUserModal')">
          <figure class="image is-48x48 mr-1">
            <img class="is-rounded" :src="reply.user.photo" />
          </figure>
          <p>
            <span class="is-size-6">{{ reply.user.first_name }}</span>
            <span class="is-size-7">
              <timeago :datetime="reply.created_at" :auto-update="60"></timeago>
            </span>
          </p>
        </div>

        <b-dropdown class="ml-a" v-if="$page.props.user.id == reply.user.id || $page.props.user.is_admin" position="is-bottom-left" aria-role="list">
          <button class="button is-light" slot="trigger" slot-scope="{ active }">
            <b-icon icon="cog"></b-icon>
          </button>
          <b-dropdown-item @click="$emit('delete',reply.id)" aria-role="listitem">Delete</b-dropdown-item>
          <b-dropdown-item v-if="$page.props.user.is_admin && reply.feedback && reply.feedback.id" @click="$emit('delete',reply.feedback.id)" aria-role="listitem">Delete feedback</b-dropdown-item>
          <b-dropdown-item v-if="$page.props.user.is_admin && !reply.feedback" @click="openReplyModal" aria-role="listitem">Add feedback</b-dropdown-item>
        </b-dropdown>
        <button class="button reply-card-modal__close is-light" @click="$emit('close-reply-modal')">
          <b-icon icon="close"></b-icon>
        </button>
      </header>
      <comments :comments="comments" :reply="reply"></comments>
    </div>
  </b-modal>
</template>

<script>
import Comments from '@/components/Comments'

export default {
  components: {
    Comments
  },

  props: ['reply', 'comments'],

  data() {
    return {
      currentSlide: null,
      media_stopped: null,
    };
  },

  mounted() {
    this.updateSlide(this.$page.props.parameters.show_feedback ? 1 : 0);
  },

  computed: {
    is_open: function() {
      return this.$page.props.parameters.reply == this.reply.id;
    }
  },

  methods: {
    updateSlide(value) {
      this.media_stopped = null;
      this.currentSlide = value;
    },

    openReplyModal() {
      this.currentSlide = null;
      this.$emit('open-create-reply-modal');
    }
  }
};
</script>


<style lang="scss"></style>
