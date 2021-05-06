<template>
  <div>
    <figure class="image is-32x32">
      <img class="is-rounded" :src="comment.user.photo" />
    </figure>
    <div class="comment-card--text">
      <p class="is-size-7 has-text-weight-semibold">
        {{ comment.user.first_name }}
        <span v-if="comment.user.is_admin" class="is-rounded has-background-success" title="Teacher">
          <b-icon
            icon="school"
            size="is-small"
            type="is-white">
          </b-icon>
        </span>
        <timeago class="has-text-grey has-text-weight-light" :datetime="comment.created_at" :auto-update="60"></timeago>
      </p>

      <div class="comment-card__value is-size-7" v-html="comment.value"></div>

      <div class="comment-card__controls">
        <span class="is-size-7" @click="$emit('reply', comment.id)">Reply</span>
        <span v-if="$page.props.user.is_admin || (page.props.user.id == comment.user.id)" class="is-size-7" @click="$emit('delete', comment.id)">Delete</span>
      </div>

    </div>
  </div>
</template>

<script>

export default {
  props: ['comment'],

  mounted() {
  },

  methods: {
  }
};
</script>

<style lang="scss">
@import "../../sass/variables";

.comment-card {
  display: flex;
  flex-direction: row;
  padding: 0.5em 0;
  cursor: pointer;

  &--child {
    padding: 0.5em;

    &:last-child {
      margin-bottom: 0.5em;
    }
  }

  &__controls {
    display: none;
    line-height: 1;

    span  {
      display: inline-block;
      margin-right: 1em;
      font-weight: 500;
    }
  }


  &.active {
    .comment-card__controls {
      display: block;
    }
  }

  &__value {
    word-break: break-word;

    li {
      list-style-type: disc;
      margin-left: 1.5em;
    }
    p:last-child {
      margin-bottom: 0;
    }
  }

  &--text {
    flex: 1;
  }

  .image {
    flex: 0 0 2rem;
    margin-right: 0.25rem;
  }
}
</style>
