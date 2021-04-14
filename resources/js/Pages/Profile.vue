<template>
<app-layout>
  <div class="column is-7-tablet is-6-desktop is-5-widescreen">
    <form ref="form" class="box register-form">
      <h3 class="title has-text-centered">Modifica il tuo profilo<span class="emoji">👤</span></h3>
      <p class="subtitle has-text-centered">Edit your profile</p>
      <b-notification
            v-if="errors.course"
            type="is-danger"
            has-icon
            role="alert"
            :closable="false"
            :message="errors.course[0]">
      </b-notification>

          <b-field label="First name" :message="errors.first_name" :type="errors.first_name ? 'is-danger' : null">
            <b-input required name="first_name" v-model="form.first_name" ></b-input>
          </b-field>

          <b-field label="Last name" :message="errors.last_name" :type="errors.last_name ? 'is-danger' : null">
            <b-input required name="last_name" v-model="form.last_name"></b-input>
          </b-field>

          <b-field label="Photo">
            <camera-field mode="photo" v-model="form.photo"></camera-field>
          </b-field>

          <b-field label="Introduce yourself">
            <b-input v-model="form.description" name="description" type="textarea" maxlength="120" rows="6" required placeholder="Introduce yourself to the other students.">
            </b-input>
          </b-field>

          <b-button type="is-primary" @click.prevent="onSubmit" expanded>Register</b-button>

    </form>
  </div>
</app-layout>
</template>

<script>
import AppLayout from '@/Layouts/AppLayout'
import CameraField from '@/components/CameraField'

export default {
  props: ['errors', 'user', 'admin'],
  components: {
    AppLayout,
    CameraField
  },
  data() {
    return {
      form: this.$inertia.form({
        _method: 'put',
        id: this.user.id,
        first_name: this.user.first_name ?? null,
        last_name: this.user.last_name ?? null,
        description: this.user.description ?? null,
        photo: (this.user.photo && !this.user.photo.startsWith('https://ui-avatars.com')) ? this.user.photo : null,
      })
    }
  },
  mounted() {
  },
  computed: {

  },
  methods: {

    onSubmit() {
      this.form.post(route('profile.update'), {
          forceFormData: true,
          onSuccess: () => {
            this.successToast('Your profile has been updated.');
          },
          onError: errors => {
            this.successToast('Check the form for errors');
          },
        })
    }
  }
}
</script>

<style lang="scss">
nav.steps {
  padding: 1em 0;
}
</style>
