<template>
  <app-layout>
    <div class="columns is-centered">
      <div class="column is-7-tablet is-6-desktop is-5-widescreen">
        <inertia-link class="back-link has-text-dark" :href="route('course.edit', { course: $page.props.parameters.course })">&larr; Back to course</inertia-link>
        <div class="box">
          <h3 class="title has-text-centered">{{ data ? 'Modifica' : 'Creare' }} settimana <span class="emoji">✏️</span></h3>
          <p class="subtitle has-text-centered">{{ data ? 'Edit the' : 'Set up a new'  }} week below</p>

          <b-notification
            v-if="errors.course"
            type="is-danger"
            has-icon
            role="alert"
            :closable="false"
            :message="errors.course[0]">
          </b-notification>

          <b-field label="Name" :message="errors.name" :type="errors.name ? 'is-danger' : null">
            <b-input required name="name" v-model="form.name" placeholder="Enter the name for this week"></b-input>
          </b-field>

          <b-field label="Number" :message="errors.number" :type="errors.number ? 'is-danger' : null">
            <b-numberinput v-model="form.number" name="number" min="0">
            </b-numberinput>
          </b-field>

          <b-field label="Overview" :message="errors.description" :type="errors.description ? 'is-danger' : null">
            <tip-tap v-model="form.description"/>
          </b-field>

          <b-checkbox v-model="form.live">Make this week live?</b-checkbox>

          <hr>
          <b-button type="is-primary" :disabled="!form.name" @click.prevent="onSubmit" :loading="form.processing" expanded>{{ data ? 'Update' : 'Create' }}</b-button>
        </div>
      </div>
    </div>
  </app-layout>
</template>

<script>
import TipTap from '@/components/TipTap';

export default {

  props: ['errors', 'data'],
  components: {
    TipTap
  },
  data() {
    return {
      form: this.$inertia.form({
        id: this.data?.id ?? null,
        name: this.data?.name ?? null,
        description: this.data?.description ?? null,
        number: this.data?.number ?? 0,
        live: this.data?.live ?? true
      }),
    }
  },

  mounted() {
  },

  methods: {

    onSubmit() {
      let postRoute = this.data ? route('week.update', {
        course: this.$page.props.parameters.course,
        week: this.$page.props.parameters.week
      }) : route('week.store', {
        course: this.$page.props.parameters.course
      });

      this.form.transform((data) => ({
          ...data,
          _method: (this.data ? 'PUT' : 'POST'),
        }))
        .post(postRoute, {
          preserveScroll: true,
          onSuccess: () => {
            this.successToast('Week saved');
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
