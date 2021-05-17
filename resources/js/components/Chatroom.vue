<template>
  <div class="chatroom">
    <!-- <nav class="clear-both">
      <button class="button" @click="sortBy = 'comments_count'">Most commented</button>
      <button class="button" @click="sortBy = 'id'">Newest</button>
    </nav> -->

    <div class="chatroom-navigation field is-grouped">
      <b-field class="control is-hidden-mobile">
           <b-radio-button type="is-success" v-model="filter" :native-value="null">
               Show all
           </b-radio-button>

           <b-radio-button type="is-success" v-model="filter" native-value="video">
               Video
           </b-radio-button>

           <b-radio-button type="is-success" v-model="filter" native-value="audio">
               Audio
           </b-radio-button>

           <b-radio-button type="is-success" v-model="filter" native-value="text">
               Text
           </b-radio-button>
       </b-field>
       <b-switch :value="include_already_replied_to" :true-value="1" :false-value="0" @input="toggle_already_replied_to" class="ml-a" v-if="show_admin_interface" :left-label="true">Show replies already replied to</b-switch>
       <div v-else class="is-flex ml-a">
          <p class="control">
            <b-dropdown v-model="sortBy" aria-role="list">
                <template #trigger="{ active }">
                    <b-button
                        :label="{
                          'id': 'Newest',
                          'comments_count': 'Most commented'
                        }[sortBy]"
                        class="mr-1"
                        type="is-text"
                        icon-right="chevron-down"
                        icon-left="bar-chart" />
                </template>
                <b-dropdown-item value="id" aria-role="listitem">Newest</b-dropdown-item>
                <b-dropdown-item value="comments_count" aria-role="listitem">Most commented</b-dropdown-item>
            </b-dropdown>
          </p>
          <p class="control">
              <create-reply @uploaded="startRefreshing" />
          </p>
        </div>
    </div>

    <div class="chatroom-body container is-flex is-flex-wrap-wrap">

      <reply-card v-for="reply in sortedReplies" :reply="reply" :key="reply.id" :include_already_replied_to="include_already_replied_to" :in_chatroom_manager="in_chatroom_manager" :comments="$page.props.parameters.reply == reply.id ? comments : null" @uploaded="startRefreshing"/>

      <div v-if="!replies.length && show_admin_interface && !include_already_replied_to" class="message is-fullwidth mt-4 is-success">
        <div class="message-body section is-medium has-text-centered">
          😁 Great! No replies in here need feedback.
        </div>
      </div>

      <div v-else-if="!replies.length && !show_admin_interface" class="message is-fullwidth mt-4 is-success">
        <div class="message-body section is-medium has-text-centered">
          👀 No one’s posted a reply yet.
        </div>
      </div>

      <div v-else-if="!sortedReplies.length" class="message is-fullwidth mt-4 is-success">
        <div class="message-body section is-medium has-text-centered">
          🔍 No replies to show you.
        </div>
      </div>

    </div>
  </div>
</template>

<script>
import { Inertia } from '@inertiajs/inertia'

export default {
  props: ['replies', 'comments', 'show_admin_interface', 'in_chatroom_manager', 'include_already_replied_to'],
  components: {

  },
  data() {
    return {
      reply_count: this.replies.length,
      sortBy: 'id',
      filter: null,
    }
  },

  mounted() {

  },

  watch: {
  },


  computed: {
    sortedReplies() {

      let replies = this.replies.filter((a) => {
        return a;
      });

      replies = replies.sort((a,b) => {
        return b[this.sortBy] - a[this.sortBy];
      });

      if(this.filter) {
        replies = replies.filter((a) => {
          return a.type == this.filter;
        });
      }

      return replies;
    },
  },

  methods: {
    toggle_already_replied_to(value) {
      this.$inertia.visit(route('chatroom.section', {'course': this.$page.props.parameters.course, 'week': this.$page.props.parameters.week, 'lesson': this.$page.props.parameters.lesson, 'section': this.$page.props.parameters.section, 'include_already_replied_to': value }), {
        preserveScroll: true
      })
    },
    startRefreshing() {
      Inertia.reload({ only: ['replies'] });
      setInterval(()=> {
        console.log('reloading');
        Inertia.reload({ only: ['replies'] });
      }, 10000);
    }
  },
};
</script>

<style lang="scss">
@import "../../sass/variables";

.negative-margin {
  margin: 0 (-3rem) 0;

  @media (orientation: portrait) {
    margin: 0 (-0.75rem);
  }

  .chatroom-body {
    padding: 2rem 2.25rem;

    @media (orientation: portrait) {
      padding: 2rem 0;
    }
  }

  .chatroom-navigation {
    padding: 1rem 3rem;

    @media (orientation: portrait) {
      padding: 1rem 0;
    }
  }
}

.chatroom {
  background-color: $white-bis;
}

.chatroom-body {
  padding: 2rem 1rem;
}

.chatroom-navigation {
  padding: 1rem 1rem;
  border-top: 1px solid $grey-lighter;
  border-bottom: 1px solid $grey-lighter;
  background-color: $grey-lightest;

}

.mr-a  {
  margin-right: auto !important;
}
</style>
