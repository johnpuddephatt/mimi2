<template>
  <div>
    <nav class="clear-both">
      <button class="button" @click="sortBy = 'comments_count'">Most commented</button>
      <button class="button" @click="sortBy = 'id'">Newest</button>
    </nav>
    <div class="container is-flex is-flex-wrap-wrap">
      <create-reply :$parameters="$parameters" :$user="$user"/>
      <reply-card @uploaded="startRefreshing" v-for="reply in sortedReplies" :reply="reply" :key="reply.id" :$parameters="$parameters" :$user="$user" :comments="$parameters.reply == reply.id ? comments : null"/>
    </div>
  </div>
</template>

<script>
import { Inertia } from '@inertiajs/inertia'

export default {
  props: ['replies', '$parameters', '$user'],
  components: {

  },
  data() {
    return {
      reply_count: this.replies.length,
      sortBy: 'id',
    }
  },

  mounted() {
  },


  computed: {
    sortedReplies() {
      return this.replies.sort((a,b) => {
        return b[this.sortBy] - a[this.sortBy];
      });
    },
  },

  methods: {
    startRefreshing() {
      setInterval(()=> {
        Inertia.reload({ only: ['replies'] });
      }, 10000);
    }
  }
};
</script>

<style lang="scss">
@import "../../sass/variables";
</style>
