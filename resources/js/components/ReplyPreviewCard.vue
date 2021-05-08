<template>
  <div class="card reply-card">
    <div class="card-image" :class="{'loaded' : reply.audio || reply.video}" @click="$emit('open-reply-modal')">
      <figure class="image is-square m-0" :class="{'audio-preview' : reply.audio}">
        <div v-if="reply.type == 'audio' && reply.audio"></div>
        <img v-else-if="reply.type == 'video' && reply.video" :src="reply.video.thumbnail_path" alt="">
        <div class="text-preview" v-else-if="reply.type== 'text'">
          <div class="text-preview--inner-preview" v-html="reply.text.replace(/(<([^>]+)>)/gi, '').substr(0,75) + '...'"></div>
        </div>
        <b-loading class="reply-loading" v-else :is-full-page="false" :active="true"></b-loading>
      </figure>
    </div>
    <div class="card-content is-justify-between">
      <div class="reply-author" @click="$emit('openUserModal')">
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
</template>

<script>
export default {
  props: ['reply'],
  data() {
    return {
    };
  },
  watch: {
  }
};
</script>


<style lang="scss"></style>
