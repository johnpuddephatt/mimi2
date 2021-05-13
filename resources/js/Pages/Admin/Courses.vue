<template>
  <app-layout>
    <div class="columns is-centered">
      <div class="column is-7-tablet is-6-desktop is-5-widescreen is-paddingless">
        <div class="box">
          <h3 class="title has-text-centered">Ciao, capo 👑️</h3>
          <p class="subtitle has-text-centered">Manage courses here</p>
          <nav class="panel is-shadowless is-bordered">

            <div v-for="course in courses" class="panel-block is-justify-between">
              <p>
                {{ course.title }}
                <span v-if="course.archived" class="tag is-light">Archived</span>
              </p>

              <b-field>
                <p class="control">
                  <inertia-link class="button" :href="route('course.edit', course.id)">
                    Edit
                  </inertia-link>
                </p>
              </b-field>
            </div>

            <section v-if="!courses" class="section is-medium has-background-light has-text-centered">
              No courses to show you.
            </section>

            <div class="panel-block">
              <inertia-link class="button is-primary is-fullwidth" :href="route('course.new')">
                Add new course
              </inertia-link>
            </div>
          </nav>
        </div>
      </div>
    </div>
  </app-layout>
</template>
<script>
import AppLayout from '%/Layouts/AppLayout'

export default {
  props: {
    courses: Array,
  },
  components: {
    AppLayout
  }
}
</script>
