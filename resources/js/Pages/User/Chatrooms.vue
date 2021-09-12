<template>
  <app-layout>
    <div class="columns is-centered">
      <div class="column is-7-tablet is-6-desktop is-5-widescreen">
        <div class="box">
          <h3 class="title has-text-centered">Your chatrooms</h3>
          <p class="subtitle has-text-centered">
            Select a class to see a list of the chatrooms you’ve already replied
            to.
          </p>

          <b-dropdown
            class="is-block"
            position="is-bottom-right"
            append-to-body
            aria-role="menu"
          >
            <template #trigger>
              <button
                class="button is-fullwidth navbar-item text-overflow-ellipsis"
                role="button"
              >
                <span class=""
                  >{{ cohort.course.title }} –
                  <em v-if="cohort.title !== 'Default'">{{
                    cohort.title
                  }}</em></span
                >
                <b-icon icon="chevron-down"></b-icon>
              </button>
            </template>

            <b-dropdown-item has-link aria-role="menuitem">
              <inertia-link
                v-for="cohort in cohorts"
                :key="cohort.id"
                :href="route('user.chatroom.cohort', { cohort: cohort.id })"
              >
                {{ cohort.course.title }} – {{ cohort.title }}
              </inertia-link>
            </b-dropdown-item>
          </b-dropdown>

          <hr />

          <inertia-link
            v-for="lesson in lessonsRepliedTo"
            :key="lesson.id"
            class="button is-medium is-justify-between is-fullwidth is-outlined"
            :href="
              route('section.reply', {
                cohort: this.$page.props.parameters.cohort,
                course: this.course.id,
                week: lesson.week.number,
                lesson: lesson.id,
                section: lesson.sections[0].id,
                reply: this.replies.find(reply => reply.lesson_id == lesson.id)
                  .id
              })
            "
          >
            <span class="text-overflow-ellipsis mr-a">{{ lesson.title }}</span>
            <!-- <b-tooltip v-if="replies.find(entry => entry.lesson_id == lesson.id)" label="You’ve replied to this" type="is-success" position="is-bottom">
              <b-icon type="is-success" icon="check-circle" />
            </b-tooltip> -->
            <b-icon icon="arrow-right" />
          </inertia-link>

          <div
            v-if="!lessonsRepliedTo.length"
            class="message is-fullwidth mt-4 is-success"
          >
            <div class="message-body section is-medium has-text-centered">
              You’ve not posted any replies in this class yet.
            </div>
          </div>
        </div>
      </div>
    </div>
  </app-layout>
</template>

<script>
import AppLayout from "@/Layouts/AppLayout";

export default {
  props: ["cohort", "cohorts", "lessons", "replies"],
  components: {
    AppLayout
  },
  data() {
    return {};
  },
  mounted() {},

  computed: {
    lessonsRepliedTo() {
      return this.lessons.filter(lesson => {
        return this.replies.map(o => o["lesson_id"]).includes(lesson.id);
      });
    }
  },

  methods: {}
};
</script>

<style lang="scss"></style>
