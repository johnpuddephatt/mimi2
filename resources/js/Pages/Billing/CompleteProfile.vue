<template>
<app-layout>
  <div class="column is-7-tablet is-6-desktop is-5-widescreen">
    <form ref="form" class="box register-form">
      <h3 class="title has-text-centered">Presentati <span class="emoji">📸</span></h3>
      <p class="subtitle has-text-centered">Introduce yourself</p>

      <div class="notification mt-3">
        <p class="mt-2"><strong>Your payment was successful</strong><p>
        <p class="mt-2">You can add a photo and introduce yourself below. This is optional but will help your teacher and other students get to know you.</p>
        <inertia-link :href="route('billing.success')" class="mt-2 button is-outlined">Skip this step</inertia-link>
      </div>

      <b-field class="mt-4" label="Introduce yourself">
        <b-input v-model="form.description" name="description" type="textarea" maxlength="120" rows="6" required placeholder="Introduce yourself to the other students.">
        </b-input>
      </b-field>

      <b-field label="Photo">
        <camera-field mode="photo" v-model="form.photo"></camera-field>
      </b-field>

      <b-button type="is-primary" @click.prevent="onSubmit" expanded>Complete your profile</b-button>

    </form>
  </div>
</app-layout>
</template>

<script>
import AppLayout from '@/Layouts/AppLayout'
import CameraField from '@/components/CameraField'

export default {
  props: ['errors', 'user'],
  components: {
    AppLayout,
    CameraField
  },
  data() {
    return {
      form: this.$inertia.form({
        _method: 'put',
        id: this.user.id,
        description: null,
        photo: null,
      })
    }
  },
  mounted() {
  },
  computed: {

  },
  methods: {

    onSubmit() {

      this.form.post(route('billing.complete-profile'), {
          forceFormData: true,
          onError: errors => {
            this.successToast('Check the form for errors');
          },
        })
    }
  }
}
</script>

<style lang="scss">

</style>
