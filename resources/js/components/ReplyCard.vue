<template>
  <transition name="fade-out">
    <div class="column is-full is-half-widescreen is-relative">
      <div class="admin-reply" v-if="$page.props.user.is_admin">
        <b-tooltip v-if="reply.feedback" label="You’ve replied to this" type="is-dark" animated position="is-left" :delay="1000" class="admin-check-button--tooltip">
          <b-button class="admin-check-button" rounded type="is-light" icon-left="check"></b-button>
        </b-tooltip>
        <create-reply v-else :mode="reply.type" :reply_id="reply.id" @uploaded="$emit('uploaded')" :should_open="isCreateReplyModalOpen" @close="isCreateReplyModalOpen = false"></create-reply>
      </div>
      <reply-preview-card :reply="reply" @open-reply-modal="openReply" @openUserModal="isUserModalOpen = true"></reply-preview-card>
      <reply-modal :reply="reply" :comments="comments" :create_reply_modal_open="isCreateReplyModalOpen" @close-reply-modal="closeReply" @open-create-reply-modal="isCreateReplyModalOpen = true" @delete="confirmDelete" @openUserModal="isUserModalOpen = true"></reply-modal>
      <user-modal v-model="isUserModalOpen" :user="reply.user"></user-modal>
    </div>
  </transition>
</template>

<script>
import { Inertia } from '@inertiajs/inertia'

import ReplyModal from '@/components/ReplyModal'
import UserModal from '@/components/UserModal'
import ReplyPreviewCard from '@/components/ReplyPreviewCard'

export default {
  props: ['reply', 'in_chatroom_manager', 'include_already_replied_to', 'comments'],
  components: {
    ReplyModal,
    UserModal,
    ReplyPreviewCard
  },

  data() {
    return {
      isDeleted: false,
      isCreateReplyModalOpen: false,
      isUserModalOpen: false,
      destroyReplyForm: this.$inertia.form({
        in_chatroom_manager: this.in_chatroom_manager
      }),
    }
  },

  watch: {
  },

  mounted() {
  },

  methods: {

    openReply() {
      this.$inertia.visit(route(this.in_chatroom_manager ? 'chatroom.reply' : 'section.reply', {'course': this.$page.props.parameters.course, 'week': this.$page.props.parameters.week, 'lesson': this.$page.props.parameters.lesson, 'section': this.$page.props.parameters.section, 'reply': this.reply.id, 'include_already_replied_to': this.include_already_replied_to  }), {
        only: ['comments', 'parameters'],
        preserveScroll: true,
        preserveState: true,
      });
    },

    closeReply() {
      this.$inertia.visit(route(this.in_chatroom_manager ? 'chatroom.section' : 'section.show', {'course': this.$page.props.parameters.course, 'week': this.$page.props.parameters.week, 'lesson': this.$page.props.parameters.lesson, 'section': this.$page.props.parameters.section, 'include_already_replied_to': this.include_already_replied_to  }), {
        only: [],
        preserveScroll: true,
        preserveState: true,
      });
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
      this.destroyReplyForm.delete(route('reply.delete', { 'course': this.$page.props.parameters.course, 'week': this.$page.props.parameters.week, 'lesson': this.$page.props.parameters.lesson, 'section': this.$page.props.parameters.section, 'reply': id }),
      {
        preserveScroll: true,
        onSuccess: () => {
          this.successToast('Reply deleted!');
        },
        onError: errors => {
          this.errorToast('Reply could not be deleted');
          axios.post('/log', {'error': `COMMENT DELETE ERROR, ${ platform.description }, ${ JSON.stringify(error) }`});
        },
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
    margin: auto 0;
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
    z-index: 39;
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
              max-width: 56.25vh;
              max-height: 56.25vh;
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

            .modal-card-foot, .modal-card-head {
              padding: 10px 20px;
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

.reply-loading {
  z-index: 8;
}
</style>
