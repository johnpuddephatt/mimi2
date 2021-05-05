<template>
<transition name="fade-out">
  <div class="column is-full is-half-widescreen is-relative">

    <div class="admin-reply" v-if="$user.is_admin">
      <b-tooltip v-if="reply.feedback" label="You’ve replied to this" type="is-dark" animated position="is-left" :delay="1000" class="admin-check-button--tooltip">
        <b-button class="admin-check-button" rounded type="is-light" icon-left="check">
        </b-button>
      </b-tooltip>
      <create-reply v-else :$user="$user" :$parameters="$parameters" :mode="reply.type" :reply_id="reply.id" :should_open="open_reply_modal"></create-reply>
    </div>

    <div class="card reply-card">
      <div class="card-image" :class="{'loaded' : reply.audio || reply.video}" @click="is_open = true">
        <figure class="image is-square m-0" :class="{'audio-preview' : reply.audio}">
          <div v-if="reply.audio"></div>
          <img v-else-if="reply.video" :src="reply.video.thumbnail_path" alt="">
          <div class="text-preview" v-else-if="reply.text">
            <div class="text-preview--inner-preview" v-html="reply.text.replace(/(<([^>]+)>)/gi, '').substr(0,75) + '...'"></div>
          </div>
          <b-loading v-else :is-full-page="false" :active="true"></b-loading>
        </figure>
      </div>
      <div class="card-content is-justify-between">
        <div class="reply-author" @click="isUserModalOpen = true">
          <figure class="image is-48x48 m-0 mr-2">
            <img class="is-rounded" :src="reply.user.photo" />
          </figure>
          <p>
            <span class="is-size-6">{{ reply.user.first_name}}</span>
            <span class="is-size-7">
              <timeago :datetime="reply.created_at" :auto-update="60"></timeago>
            </span>
          </p>
        </div>
        <span v-if="reply.comments_count > 0" class="tag is-rounded">{{ reply.comments_count }} comments</span>
      </div>
    </div>


    <b-modal custom-class="has-background-white-bis reply-card-modal" :active.sync="is_open" has-modal-card trap-focus :destroy-on-hide="true" animation="zoom-in" aria-role="dialog" width="840px" aria-modal>

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
            <video-player v-if="reply.video" @playing="media_stopped = null" @stopped="media_stopped = 'reply'" :should_autoplay="currentSlide == 0" :source="reply.video.playlist_path" :poster="reply.video.thumbnail_path" type="application/x-mpegURL"></video-player>
            <audio-player v-else-if="reply.audio" :source="reply.audio" @playing="media_stopped = null" @stopped="media_stopped = 'reply'" :should_autoplay="currentSlide == 0" v-else />
            <div class="image is-square m-0" v-else>
              <div class="text-preview">
                <div v-html="reply.text" class="content text-preview--inner"></div>
              </div>
            </div>
          </b-carousel-item>
          <b-carousel-item v-if="reply.feedback" key="feedback">
            <video-player v-if="reply.feedback.video && reply.feedback.video.playlist_path" @playing="media_stopped = null" @stopped="media_stopped = 'feedback'" :should_autoplay="currentSlide == 1" :source="reply.feedback.video.playlist_path" :poster="reply.feedback.video.thumbnail_path" type="application/x-mpegURL"></video-player>
            <audio-player v-else-if="reply.feedback.audio" :source="reply.feedback.audio" @playing="media_stopped = null" @stopped="media_stopped = 'feedback'" :should_autoplay="currentSlide == 1" />
            <div class="image is-square m-0" v-else>
              <div class="text-preview">
                <div v-html="reply.feedback.text" class="content text-preview--inner"></div>
              </div>
            </div>
          </b-carousel-item>
        </b-carousel>
      </div>

      <div class="modal-card">
        <header class="modal-card-head is-radiusless">
          <div class="reply-author" @click="isUserModalOpen = true">
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

          <b-dropdown class="ml-a" v-if="$user.id == reply.user.id || $user.is_admin" position="is-bottom-left" aria-role="list">
            <button class="button is-light" slot="trigger" slot-scope="{ active }">
              <b-icon icon="cog"></b-icon>
            </button>
            <b-dropdown-item @click="confirmDelete(reply.id)" aria-role="listitem">Delete</b-dropdown-item>
            <b-dropdown-item v-if="$user.is_admin && reply.feedback && reply.feedback.id" @click="confirmDelete(reply.feedback.id)" aria-role="listitem">Delete feedback</b-dropdown-item>
            <b-dropdown-item v-if="$user.is_admin && !reply.feedback" @click="openReplyModal" aria-role="listitem">Add feedback</b-dropdown-item>
          </b-dropdown>
          <button class="button reply-card-modal__close is-light" @click="is_open = false">
            <b-icon icon="close"></b-icon>
          </button>
        </header>
        <comments :$parameters="$parameters" :in_chatroom_manager="in_chatroom_manager" :preloadedComments="comments" :$user="$user" :reply="reply"></comments>
      </div>
    </b-modal>

    <b-modal v-model="isUserModalOpen" :width="480" scroll="keep">
      <div class="card">
        <div class="card-content">
          <div class="media ">
            <div class="media-left">
              <figure class="image is-64x64">
                <img class="is-rounded" :src="reply.user.photo" alt="Image">
              </figure>
            </div>
            <div class="media-content">
              <p class="title is-4">{{ reply.user.first_name}} {{ reply.user.last_name}}</p>
              <p class="subtitle is-6" v-if="$user.is_admin"><a :href="`mailto:${ reply.user.email }`">{{ reply.user.email }}</a></p>
              <p>{{ reply.user.description }}</p>
              <br>
              <p><small>Joined <timeago :datetime="reply.user.created_at"></timeago></small></p>
            </div>
          </div>
        </div>
      </div>
    </b-modal>
  </div>
</transition>
</template>

<script>
import Comments from '@/components/Comments'
import { Inertia } from '@inertiajs/inertia'

export default {
  props: ['reply', 'in_chatroom_manager', '$parameters', '$user', 'comments'],
  components: {
    Comments
  },
  data() {
    return {
      is_open: false,
      currentSlide: null,
      isDeleted: false,
      media_stopped: null,
      open_reply_modal: false,
      isUserModalOpen: false
    }
  },

  watch: {
    is_open: function(value) {
      if (value) {
        if (!window.location.href.includes(`/reply/${this.reply.id}`)) {
          history.pushState(null, null, `${window.location.href}/reply/${this.reply.id}`);
        }
        this.currentSlide = this.$parameters.show_feedback ? 1 : 0;
      } else {
        history.pushState(null, null, window.location.href.replace('/feedback', '').replace(`/reply/${this.reply.id}`, ''));
      }
    }
  },

  mounted() {
    if (this.$parameters.reply == this.reply.id) {
      this.is_open = true;
    }
  },

  methods: {
    onSubmit() {},

    openReplyModal() {
      this.is_open = false;
      this.open_reply_modal = true;
    },

    updateSlide(value) {
      this.media_stopped = null;
      this.currentSlide = value;
    },

    confirmDelete(id) {
      this.$buefy.dialog.confirm({
        title: 'Confirm deletion',
        message: `Are you sure you want to <b>delete</b> this ${ (id == this.reply.id) ? 'student response' : 'teacher feedback'}? This action cannot be undone.`,
        confirmText: 'Delete',
        type: 'is-danger',
        hasIcon: true,
        onConfirm: () => this.delete(id)
      })
    },

    delete(id) {
      axios({
          method: 'delete',
          url: `/lesson/${ this.$parameters.lesson }/reply/${id}/delete`
        })
        .then(feedback => {
          if (id != this.reply.id) {
            this.updateSlide(0);
          }
          else {
            this.is_open = false;
          }

          this.$nextTick(() => {
            this.successToast('Reply deleted');
            Inertia.reload({ only: ['replies'] });
          });
        })
        .catch(error => {
          this.errorToast('Reply could not be deleted');
          axios.post('/log', {
            'error': `REPLY DELETE ERROR, ${ platform.description }, ${ JSON.stringify(error) }`
          });
        })
    }
  }
};
</script>

<style lang="scss">
@import "../../sass/variables";

.text-preview {
  background-color: lighten($primary, 35%);
  overflow-y: auto;
  line-height: 1.75;
  position: absolute;
  top: 0;
  left: 0;
  right: 0;
  bottom: 0;
  margin: auto 0;
  display: flex;
  flex-direction: column;
  font-size: $size-5;
  font-weight: 500;

  &--inner-preview {
    margin-top: auto;
    margin-bottom: auto;
    padding: 3rem;
    cursor: default;

    &::before,
    &::after {
      content: close-quote;
      font-size: $size-3;
      color: lighten($primary, 15%);
      line-height: 0;
    }
    &::before {
      content: open-quote;
      margin-left: -0.35em;
    }
  }

  &--inner {
    user-select: text;
    padding: 7.5rem 5rem;
    &::before,
    &::after {
      display: inline-block;
      content: close-quote;
      font-size: 5rem;
      font-weight: 900;
      color: lighten($primary, 15%);
      line-height: 0;
      float: right;
    }

    &::before {
      content: open-quote;
      margin-left: -0.5em;
      float: left;
    }
  }
}

.audio-preview {
  background-color: lighten($success, 35%);
  background-image: url(/images/sine.svg);
  background-repeat: no-repeat;
  background-size: contain;
  background-position: center;
}

.reply-card {

    .loading-overlay {
      z-index: 9;
    }

    .card-image {
        position: relative;

        &.loaded {
          &::before {
            content: "\F101";
            font-size: 2.5em;
            background-color: transparentize($turquoise,0.3);
            color: white;
            height: 1.5em;
            width: 1.5em;
            line-height: 1.5em;
            text-align: center;
            border-radius: 9999px;
            z-index: 9;
            font-family: 'VideoJS';
            position: absolute;
            top: 50%;
            left: 50%;
            transform: translate(-50%,-50%);
            transition: background-color 1s;
          }
        }
        &:hover {
            &::before {
                background-color: transparentize($turquoise,0);
            }
        }
        img {
            object-fit: cover;
        }
    }
    &__loading {
        .card-image::before {
            content: none;
        }
    }
    .card-content {
        display: flex;
        flex-direction: row;
        align-items: center;
        padding: 1rem 1.5rem;
    }
    &__add {
        min-height: 22.5em;
        background-color: transparent;
        box-shadow: none;
        border: 2px dashed $grey-lighter;
        height: 100%;
        display: flex;
        flex-direction: column;
        align-items: center;
        padding: 2em;
        justify-content: center;
    }
}

.reply-card-modal {
    .animation-content {
        position: relative;
        border-radius: $radius-large;
        overflow: hidden;
        width: 100%;
        max-width: 175vh;
        display: flex;

        @media screen and (orientation: portrait) {
            flex-direction: column;
            margin: 0;
            border-radius: 0;
            height: 100%;
            // min-height: 100vh;
            margin-top: auto;
            background-color: white;

            .carousel {
              max-width: 50vh;
              max-height: 50vh;
              margin: 0 auto;
            }

            .carousel-wrapper {
              background-color: $grey-darker;
              width: 100%;
            }

            .modal-card {
                width: 100%;
                flex-grow: 1;
                min-height: calc(100vh - 100vw);
            }
        }
    }
    &__close {
        margin-left: 0.25rem;
    }
}

.admin-check-button--tooltip,
.admin-reply-button--tooltip {
    position: absolute;
    z-index: 9;
    top: 1.25em;
    right: 1.25em;
}

.admin-check-button,
.admin-reply-button {
    border-radius: 9999px;
}

.feedback-button,
.feedback-toggle {
    position: absolute;
    z-index: 9;
    left: 50%;
    bottom: 0.75em;
    transform: translateX(-50%);
    @media screen and (orientation: landscape) {
        bottom: 1.5em;
    }
}

.feedback-toggle {
    bottom: auto;
    top: 0.75em;
    width: auto !important;
    @media screen and (orientation: landscape) {
        top: 1.5em;
    }
}

.reply-author {
    display: inline-flex;
    align-items: center;
    flex-direction: row;
    border-bottom: none;

    p {
        margin-top: auto;
        margin-bottom: auto;
        padding-right: 1em;
        line-height: 1.2;
        margin-right: auto;

        span {
            display: block;
        }
    }

    .tag {
        margin-left: auto;
    }
}

.carousel-wrapper {
    position: relative;
}
</style>
