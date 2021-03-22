<template>
  <app-layout>
    <div class="column is-7-tablet is-6-desktop is-5-widescreen">
      <inertia-link  class="back-link has-text-dark" :href="route('course.edit', { course: $parameters.course })">&larr; Back to course</inertia-link>
      <div class="box">
        <h3 class="title has-text-centered">{{ data ? 'Modifica' : 'Creare' }} lezione <span class="emoji">🆕</span></h3>
        <p class="subtitle has-text-centered">{{ data ? 'Edit the' : 'Set up a new'  }} lesson below</p>
        <b-notification
              v-if="errors.course"
              type="is-danger"
              has-icon
              role="alert"
              :closable="false"
              :message="errors.course[0]">
        </b-notification>

        <b-field label="Title" :message="errors.title" :type="errors.title ? 'is-danger' : null">
          <b-input required name="title" v-model="form.title" placeholder="Enter the title for this lesson"></b-input>
        </b-field>

        <b-field grouped label="Day">
          <b-select v-model="form.day" placeholder="Select a day">
            <option value="1">Monday</option>
            <option value="2">Tuesday</option>
            <option value="3">Wednesday</option>
            <option value="4">Thursday</option>
            <option value="5">Friday</option>
            <option value="6">Saturday</option>
            <option value="7">Sunday</option>
          </b-select>
        </b-field>

        <b-field label="Instructions" :message="errors.instructions" :type="errors.instructions ? 'is-danger' : null">
          <b-input v-model="form.instructions" name="instructions" type="textarea" maxlength="120" rows="3" placeholder="Give instructions for this lesson">
          </b-input>
        </b-field>

        <h3 class="label">Sections</h3>
        <nav v-if="$parameters.lesson" class="panel is-shadowless is-bordered">

          <draggable v-model="form.sections" @start="drag=true" @end="onMoveEnd">
            <div v-for="section in form.sections" :key="section.id" class="panel-block is-justify-between">
              <p>
                <b-icon icon="drag" type="is-dark"></b-icon>
                {{ section.title }}
              </p>

              <b-field>
                <p class="control">
                  <inertia-link class="button" :href="route('section.edit', {course: $parameters.course, week: $parameters.week, lesson: $parameters.lesson , section: section.id})">Edit</inertia-link>
                </p>
              </b-field>
            </div>
          </draggable>

          <section v-if="!form.sections.length" class="section is-medium has-background-light has-text-centered">
            No sections added yet. <br><inertia-link :href="route('section.create', {course: $parameters.course, week: $parameters.week, lesson: data.id})">Create the first section</inertia-link>
          </section>

          <div class="panel-block  is-paddingless">
            <inertia-link class="button is-fullwidth" :href="route('section.create', {course: $parameters.course, week: $parameters.week, lesson: data.id})">Create new section</inertia-link>
          </div>
        </nav>
        <div class="notification" v-else>
          You need to save this lesson before adding sections.
        </div>
        <hr>
        <b-button type="is-primary" :disabled="!form.title" @click.prevent="onSubmit" :loading="form.processing" expanded>{{ data ? 'Update' : 'Create' }}</b-button>
      </div>
    </div>
  </app-layout>
</template>

<script>
import CameraField from "@/components/CameraField";
import draggable from 'vuedraggable'

export default {
  props: ['errors', 'data', 'latest_lesson_number', '$parameters'],
  components: {
     CameraField,
     draggable
  },
  data() {
    return {
      day: null,
      week: null,
      form: this.$inertia.form({
        id: this.data?.id ?? null,
        title: this.data?.title ?? null,
        instructions: this.data?.instructions ?? null,
        day: this.data?.day ?? this.latest_lesson_number + 1,
        sections: this.data?.sections ?? []
      }),
    }
  },

  methods: {
    onMoveEnd: function(){
      this.drag = false;

      this.$inertia.post(route('lesson.reorderSections', {course: this.$parameters.course, week: this.$parameters.week, lesson: this.data.id, newOrder: this.form.sections.map(section => section.id)}), null, {
        preserveScroll: true,
      });
    },

    onSubmit() {
      let postRoute = this.data ? route('lesson.update', { course: this.$parameters.course, week: this.$parameters.week, lesson: this.form.id }) : route('lesson.store', { course: this.$parameters.course, week: this.$parameters.week });

      this.form.transform((data) => ({
          ...data,
          course_id: this.$parameters.course,
          _method: (this.data ? 'PUT' : 'POST'),
        }))
        .post(postRoute, {
          preserveScroll: true,
          onSuccess: () => {
            this.successToast('Lesson saved');
          },
          onError: errors => {
            this.errorToast('Check the form for errors');
          },
        })
    }
  }
}
</script>

<style>
</style>
