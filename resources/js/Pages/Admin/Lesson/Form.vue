<template>
  <app-layout>
    <div class="columns is-centered">
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

          <b-tabs>
            <b-tab-item label="Overview">
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
                <tip-tap v-model="form.instructions" />
              </b-field>

              <b-checkbox v-model="form.live">Make this lesson live?</b-checkbox>

            </b-tab-item>
            <b-tab-item label="Sections">
              <h3 class="label is-flex is-justify-between">
                <p>Sections</p>
                <inertia-link v-if="$parameters.lesson"  class="button is-small is-primary ml-a has-text-weight-normal" :href="route('section.create', {course: $parameters.course, week: $parameters.week, lesson: data.id})">Add new section</inertia-link>
              </h3>
              <nav v-if="$parameters.lesson" class="panel is-shadowless is-bordered">

                <draggable v-model="form.sections" @start="drag=true" @end="onMoveEnd">
                  <div v-for="section in form.sections" :key="section.id" class="panel-block is-justify-between">
                    <p class="text-overflow-ellipsis">
                      <b-icon icon="drag" type="is-dark"></b-icon>
                      {{ section.title }}
                      <span v-if="section.is_chatroom" class="tag is-rounded">chatroom</span>
                    </p>
                    <div>
                      <button class="button is-small ml-3" @click="confirmSectionDelete(section.id)">Delete</button>
                      <inertia-link class="button is-small ml-1" :href="route('section.edit', {course: $parameters.course, week: $parameters.week, lesson: $parameters.lesson , section: section.id})">Edit</inertia-link>
                    </div>
                  </div>
                </draggable>

                <section v-if="!form.sections.length" class="section is-medium has-background-light has-text-centered">
                  No sections added yet. <br><inertia-link :href="route('section.create', {course: $parameters.course, week: $parameters.week, lesson: data.id})">Create the first section</inertia-link>
                </section>
              </nav>
              <div class="notification" v-else>
                You need to save this lesson before adding sections.
              </div>
            </b-tab-item>
          </b-tabs>
          <hr>
          <b-button type="is-primary" :disabled="!form.title" @click.prevent="onSubmit" :loading="form.processing" expanded>{{ data ? 'Update' : 'Create' }}</b-button>
        </div>
      </div>
    </div>
  </app-layout>
</template>

<script>
import { Inertia } from '@inertiajs/inertia';
import CameraField from "@/components/CameraField";
import draggable from 'vuedraggable'
import TipTap from '@/components/TipTap';

export default {
  props: ['errors', 'data', 'latest_lesson_number', '$parameters'],
  components: {
     CameraField,
     TipTap,
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
        sections: this.data?.sections ?? [],
        live: this.data?.live ?? true
      }),
      destroySectionForm: this.$inertia.form(),
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
    },

    confirmSectionDelete(sectionId) {
      this.$buefy.dialog.confirm({
        title: 'Confirm section deletion',
        type: 'is-danger',
        hasIcon: true,
        message: 'Are you sure you want to delete this section?',
        onConfirm: () => this.destroySectionForm.delete(route('section.delete', {
            section: sectionId,
            lesson: this.$parameters.lesson,
            week: this.$parameters.week,
            course: this.$parameters.course
          }), {
            preserveScroll: true,
            onSuccess: () => {
              Inertia.reload();
              this.form.sections = this.data.sections;
              this.successToast('Section deleted');
            },
            onError: errors => {
              this.errorToast('Could not delete section.');
            },
          })
      })
    },
  }
}
</script>

<style>
</style>
