<template>
<app-layout>
  <div class="columns is-centered">

    <div class="column is-7-tablet is-6-desktop is-5-widescreen">
      <div v-if="isRegistering" class="box register-form">
        <div v-if="!isRegistered">
          <h3 class="title has-text-centered">Un momento <span class="emoji">⌛</span></h3>
          <p class="subtitle has-text-centered">This shouldn’t take long</p>
        </div>
        <div v-else>
          <h3 class="title has-text-centered">Congratulazioni <span class="emoji">👍</span></h3>
          <p class="subtitle has-text-centered">Your account has been created</p>
        </div>
        <div class="circle-loader" :class="isRegistered? 'load-complete' : ''">
          <div v-if="isRegistered" class="checkmark draw"></div>
        </div>
        <div class="has-text-centered" v-if="isRegistered">
          <b-button tag="a" href="/" icon-right="arrow-right">Start learning</b-button>
        </div>
      </div>
      <form ref="form" v-else class="box register-form">
        <h3 class="title has-text-centered">Ti diamo il benvenuto <span class="emoji">👋</span></h3>
        <p class="subtitle has-text-centered">Enter your details below to begin</p>
        <b-notification
              v-if="errors.course"
              type="is-danger"
              has-icon
              role="alert"
              :closable="false"
              :message="errors.course[0]">
        </b-notification>
        <b-steps size="is-small" mobile-mode="compact" v-model="activeStep" :animated="true" :has-navigation="false">

          <b-step-item label="Account" :clickable="true">

            <b-field label="First name" :message="errors.first_name" :type="errors.first_name ? 'is-danger' : null">
              <b-input required name="first_name" v-model="form.first_name" ></b-input>
            </b-field>

            <b-field label="Last name" :message="errors.last_name" :type="errors.last_name ? 'is-danger' : null">
              <b-input required name="last_name" v-model="form.last_name"></b-input>
            </b-field>

            <b-field label="Email" :message="errors.email" :type="errors.email ? 'is-danger' : null">
              <b-input required type="email" name="email" v-model="form.email"></b-input>
            </b-field>

            <b-field label="Password" :message="errors.password || 'Minimum 8 characters'" :type="errors.password ? 'is-danger' : null">
              <b-input v-model="form.password" name="password" type="password" password-reveal minlength="8" required>
              </b-input>
            </b-field>

            <b-field label="Introduce yourself">
              <b-input v-model="form.description" name="description" type="textarea" maxlength="120" rows="4" required placeholder="Introduce yourself to the other students.">
              </b-input>
            </b-field>

            <hr>

            <button class="button is-fullwidth" @click.prevent="activeStep = 1">Next</button>

          </b-step-item>

          <b-step-item label="Notifications" :clickable="true">

            <b-field label="Email notifications">
              <div class="mb-5">
                <p class="mb-2">Send me an email when:</p>
                <b-checkbox v-for="(value, key) in notification_emails" :key="key" v-model="form.notification_emails" :native-value="key">
                  {{ value }}
                </b-checkbox>
              </div>
            </b-field>


            <button class="button is-fullwidth" @click.prevent="activeStep = 2">Next</button>

          </b-step-item>

          <b-step-item label="Photo" :clickable="true">
            <b-field label="Photo">
              <camera-field mode="photo" v-if="activeStep == 2" v-model="form.photo"></camera-field>
            </b-field>
            <hr>
            <b-button :disabled="!form.photo" type="is-primary" @click.prevent="onSubmit" :loading="isRegistering" expanded>Register</b-button>
          </b-step-item>
        </b-steps>
      </form>
    </div>
  </div>
</app-layout>
</template>

<script>
import AppLayout from '%/Layouts/AppLayout'
import CameraField from '%/components/CameraField'

export default {
  props: ['errors', 'course', 'admin', 'notification_emails'],
  components: {
    AppLayout,
    CameraField
  },
  data() {
    return {
      activeStep: 0,
      isRegistering: false,
      isRegistered: false,
      form: this.$inertia.form({
        first_name: null,
        last_name: null,
        email: null,
        password: null,
        description: null,
        photo: null,
        notification_emails: [],
      })
    }
  },
  mounted() {
  },
  computed: {
  },
  methods: {
    onSubmit() {
      this.isRegistering = true;
      this.form.transform((data) => ({
        ...data,
        admin: this.admin ?? null,
        course: this.course ?? null
      }))
      .post(route('register'), {
        preserveScroll: true,
        onError: errors => {
          console.log(errors);
          this.isRegistering = false;
          this.errorToast('Check the form for errors');
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
