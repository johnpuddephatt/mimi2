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

              <hr />

              <b-checkbox v-model="form.enable_chatroom"
                >Enable chatroom?</b-checkbox
              >
              <p class="is-size-7 mb-2">
                When <strong>disabled</strong>, chatrooms will not be displayed
                within this class.
              </p>

              <b-checkbox v-model="form.active">Active?</b-checkbox>
              <p class="is-size-7 mb-2">
                When <strong>enabled</strong>, this class will be listed in
                students’ active classes and students will be able to post new
                chatroom replies.<br />
                When <strong>disabled</strong>, students will no longer be able
                to post new chatroom replies. They will also lose access to
                speaking club and companion classes, even if these options are
                enabled below.
              </p>

              <b-checkbox v-model="form.enables_companion_courses"
                >Enables access to companion classes?</b-checkbox
              >
              <p class="is-size-7 mb-2">
                When <strong>enabled</strong>, students enrolled in this class
                will be able to access companion classes while this class is
                active.
              </p>

              <b-checkbox v-model="form.companion">Companion class?</b-checkbox>
              <p class="is-size-7 mb-2">
                When <strong>enabled</strong>, this class will be accessible by
                all students enrolled in an active class that includes access to
                companion courses.
              </p>

              <b-checkbox v-model="form.enables_speaking_club_access"
                >Includes access to speaking club?</b-checkbox
              >
              <p class="is-size-7 mb-2">
                When <strong>enabled</strong>, this students enrolled on this
                class will be able to access speaking club.
                <em>This is only in effect while this class is active.</em>
              </p>
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
        enables_companion_courses:
          this.data?.enables_companion_courses ?? false,
        active: this.data?.active ?? true,
        companion: this.data?.companion ?? false,
        enables_speaking_club_access:
          this.data?.enables_speaking_club_access ?? false
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
