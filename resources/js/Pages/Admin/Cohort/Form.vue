<template>
  <app-layout>
    <div class="columns is-centered">
      <div class="column is-7-tablet is-6-desktop is-5-widescreen">
        <inertia-link
          class="back-link has-text-dark"
          :href="
            route('course.edit', { course: $page.props.parameters.course })
          "
          >&larr; Back to course</inertia-link
        >
        <div class="box">
          <h3 class="title has-text-centered">
            {{ data ? "Modifica" : "Creare" }} classi
            <span class="emoji">✏️</span>
          </h3>
          <p class="subtitle has-text-centered">
            {{ data ? "Edit the" : "Set up a new" }} class below
          </p>

          <b-notification
            v-if="errors.course"
            type="is-danger"
            has-icon
            role="alert"
            :closable="false"
            :message="errors.course[0]"
          >
          </b-notification>

          <b-tabs>
            <b-tab-item label="Overview">
              <b-field
                label="Title"
                :message="errors.title"
                :type="errors.title ? 'is-danger' : null"
              >
                <b-input
                  required
                  name="title"
                  v-model="form.title"
                  placeholder="Enter the name for this class"
                ></b-input>
              </b-field>

              <b-checkbox v-model="form.enable_chatroom"
                >Allow students to post in chatroom?</b-checkbox
              >
              <b-checkbox v-model="form.companion"
                >Companion course?</b-checkbox
              >
              <b-checkbox v-model="form.active">Active?</b-checkbox>
            </b-tab-item>
            <b-tab-item v-if="$page.props.parameters.cohort" label="Students">
              <course-users :cohort_id="$page.props.parameters.cohort" />

              <div class="notification has-background-light">
                <h3>Invite people</h3>
                <p class="is-size-7">
                  Share the link below with people to invite them to this class.
                  If they don’t already have an account they'll be prompted to
                  create one.
                </p>
                <input
                  id="course-invite-link"
                  class="input is-small"
                  type="text"
                  :value="
                    route('cohort.enrollCurrentUser', { cohort: data.hash })
                  "
                />
              </div>
            </b-tab-item>
          </b-tabs>
          <hr />
          <b-button
            type="is-primary"
            :disabled="!form.title"
            @click.prevent="onSubmit"
            :loading="form.processing"
            expanded
            >{{ data ? "Update" : "Create" }}</b-button
          >
        </div>
      </div>
    </div>
  </app-layout>
</template>

<script>
import TipTap from "@/components/TipTap";
import CourseUsers from "@/components/CourseUsers";

export default {
  props: ["errors", "data"],
  components: {
    TipTap,
    CourseUsers
  },
  data() {
    return {
      form: this.$inertia.form({
        id: this.data?.id ?? null,
        title: this.data?.title ?? null,
        enable_chatroom: this.data?.enable_chatroom ?? false,
        active: this.data?.active ?? true,
        companion: this.data?.companion ?? false
      })
    };
  },

  mounted() {},

  methods: {
    onSubmit() {
      let postRoute = this.data
        ? route("cohort.update", {
            course: this.$page.props.parameters.course,
            cohort: this.$page.props.parameters.cohort
          })
        : route("cohort.store", {
            course: this.$page.props.parameters.course
          });

      this.form
        .transform(data => ({
          ...data,
          _method: this.data ? "PUT" : "POST"
        }))
        .post(postRoute, {
          preserveScroll: true,
          onSuccess: () => {
            this.successToast("Class saved");
          },
          onError: errors => {
            this.errorToast("Check the form for errors");
          }
        });
    }
  }
};
</script>

<style></style>
