<template>
<app-layout>
  <div class="column is-7-tablet is-6-desktop is-5-widescreen">
    <inertia-link class="back-link has-text-dark" :href="route('courses.manage')">&larr; Back to admin</inertia-link>
    <div class="box">
      <h3 class="title has-text-centered">{{ data ? 'Modifica' : 'Creare' }} corso <span class="emoji">✏️</span></h3>
      <p class="subtitle has-text-centered">{{ data ? 'Edit the' : 'Set up a new'  }} course below</p>
      <b-notification v-if="errors.course" type="is-danger" has-icon role="alert" :closable="false" :message="errors.course[0]">
      </b-notification>

      <b-tabs>
        <b-tab-item label="Overview">

          <b-field label="Title" :message="errors.title" :type="errors.title ? 'is-danger' : null">
            <b-input required name="title" v-model="form.title" placeholder="Enter the title for this course"></b-input>
          </b-field>

          <b-field label="Description" :message="errors.description" :type="errors.description ? 'is-danger' : null">
            <tip-tap v-model="form.description"/>
          </b-field>

          <b-checkbox v-if="data" v-model="form.archived">Archive this course?</b-checkbox>

          <b-checkbox v-model="form.is_open">Make this course open to all?</b-checkbox>


        </b-tab-item>

        <b-tab-item label="Lessons">
          <div class="is-flex is-justify-content-space-between is-align-items-center mb-2">
            <h3 class="label">Lessons</h3>
            <inertia-link v-if="$parameters.course" class="button is-primary is-small" :href="route('week.create', {course: $parameters.course })">
              Add a new week</inertia-link>
          </div>

          <nav v-if="$parameters.course" class="mb-6">

            <b-collapse
                class="collapse"
                animation="slide"
                v-for="(week, index) of data.weeks"
                :key="index"
                :open="isOpen == index"
                @open="isOpen = index">

                <template #trigger="props">

                  <div :class="props.open ? 'has-background-success' : ''" class="is-radius is-size-6 p-2 pl-2 is-align-items-center is-flex" role="button">
                    <b-icon class="mr-2"
                              :icon="props.open ? 'menu-down' : 'menu-right'">
                          </b-icon>
                    <span class="text-overflow-ellipsis mr-2">{{ week.name }}</span>

                    <span class="is-size-7 ml-2 mr-2 has-text-weight-normal">
                      {{ week.lessons.length || 'No' }} lessons
                    </span>

                    <div class="ml-a has-text-weight-normal">
                      <inertia-link @click.stop class="button is-small is-link is-outlined" :href="route('lesson.create', {course: $parameters.course, week: week.number })">Add lesson</inertia-link>
                      <b-dropdown
                          position="is-bottom-left"
                          append-to-body

                          aria-role="menu"
                          @click.native.stop>
                          <template #trigger>
                              <b-button class="is-small">
                                <b-icon icon="cog" size="is-small"></b-icon>
                              </b-button>
                          </template>

                          <b-dropdown-item has-link aria-role="menuitem">
                            <inertia-link @click.stop :href="route('week.edit', {course: $parameters.course, week: week.number})">Edit</inertia-link>
                          </b-dropdown-item>
                          <b-dropdown-item has-link aria-role="menuitem">
                            <a href="#" role="button" @click.stop="confirmWeekDelete(week.number)">Delete</a>
                          </b-dropdown-item>
                      </b-dropdown>
                    </div>
                  </div>
                </template>

                <div class="notification has-background-white mb-0 is-size-7 has-text-centered" v-if="!week.lessons.length">
                  No lessons in this week. <inertia-link :href="route('lesson.create', {course: $parameters.course, week: week.number })">Add the first</inertia-link>.
                </div>

                <div class="panel-block pl-6 has-background-white-bis is-justify-between" v-for="lesson in week.lessons" :key="lesson.id">
                  <span class="text-overflow-ellipsis is-size-6">{{ lesson.title }}</span>
                  <div class="is-size-6 ml-2">
                    <inertia-link class="button has-text-grey is-small" :href="route('lesson.edit', { course: $parameters.course, week: week.number, lesson: lesson.id })">Edit</inertia-link>
                    <button class="button has-text-grey is-small" @click="confirmLessonDelete(week.number, lesson.id)">Delete</button>
                  </div>
                </div>
            </b-collapse>

            <section v-if="!data.weeks" class="section is-medium has-background-light has-text-centered">
              Start by <inertia-link :href="route('week.create', {course: $parameters.course })">adding a week</inertia-link>. Once you’ve added a week, you can then add lessons to it.
            </section>

          </nav>

          <div class="notification is-size-7" v-else>
            Save this course before adding lessons.
          </div>
        </b-tab-item>
        <b-tab-item label="Students">
          <course-users :course_id="$parameters.course"/>

          <div class="notification has-background-light">
            <h3>Invite people</h3>
            <p class="is-size-7">Share the link below with people to invite them to this course. If they don’t already have an account they'll be prompted to create one.</p>
            <input id="course-invite-link" class="input is-small" type="text" :value="route('course.enrollCurrentUser', {'course' : data.hash })">
          </div>
        </b-tab-item>
      </b-tabs>

      <hr>
      <b-button type="is-primary" :disabled="!form.title" @click.prevent="onSubmit" :loading="form.processing" expanded>{{ data ? 'Update' : 'Create' }}</b-button>
    </div>
  </div>
</app-layout>
</template>

<script>
import TipTap from '@/components/TipTap';
import CourseUsers from '@/components/CourseUsers';

export default {
  props: ['errors', 'data', '$parameters'],
  components: {
    TipTap,
    CourseUsers
  },
  data() {
    return {
      form: this.$inertia.form({
        id: this.data?.id ?? null,
        title: this.data?.title ?? null,
        description: this.data?.description ?? null,
        archived: this.data?.archived ?? false,
        is_open: this.data?.is_open ?? false

      }),
      destroyWeekForm: this.$inertia.form(),
      destroyLessonForm: this.$inertia.form(),
      isOpen: null,
    }
  },

  mounted() {},

  methods: {


    confirmLessonDelete(weekNumber, lessonId) {
      this.$buefy.dialog.confirm({
        type: 'is-danger',
        hasIcon: true,
        title: 'Confirm lesson deletion',
        message: '<strong>Are you sure you want to delete this lesson?</strong>',
        onConfirm: () => this.destroyLessonForm.transform((data) => ({
            _method: 'DELETE',
          }))
          .post(route('lesson.delete', {
            week: weekNumber,
            lesson: lessonId,
            course: this.$parameters.course
          }), {
            preserveScroll: true,
            onSuccess: () => {
              this.successToast('Week deleted');
            },
            onError: errors => {
              this.errorToast('Could not delete week.');
            },
          })
      })
    },

    confirmWeekDelete(weekNumber) {
      this.$buefy.dialog.confirm({
        title: 'Confirm week deletion',
        type: 'is-danger',
        hasIcon: true,
        message: 'Are you sure you want to delete this week?<br> <strong>This will also delete any lessons within this week.</strong>',
        onConfirm: () => this.destroyWeekForm.delete(route('week.delete', {
            week: weekNumber,
            course: this.$parameters.course
          }), {
            preserveScroll: true,
            onSuccess: () => {
              this.successToast('Week deleted');
            },
            onError: errors => {
              this.errorToast('Could not delete week.');
            },
          })
      })
    },

    onSubmit() {
      let postRoute = this.data ? route('course.update', {
        course: this.$parameters.course
      }) : route('course.create');

      this.form.transform((data) => ({
          ...data,
          course_id: this.$parameters.course,
          _method: (this.data ? 'PUT' : 'POST'),
        }))
        .post(postRoute, {
          preserveScroll: true,
          onSuccess: () => {
            this.successToast('Course saved');
          },
          onError: errors => {
            this.errorToast('Check the form for errors');
          },
        })
    }
  }
};
</script>

<style lang="scss">
@import "../../../../sass/variables";

.collapse {
  border-bottom: 1px solid $grey-lightest;
}
</style>
