<template>
<app-layout>
  <div class="columns is-centered">

    <div class="column is-7-tablet is-6-desktop is-5-widescreen">
      <form ref="form" class="box register-form">
        <h3 class="title has-text-centered">Modifica il tuo profilo<span class="emoji">👤</span></h3>
        <p class="subtitle has-text-centered">Edit your profile</p>
        <b-notification v-if="errors.course" type="is-danger" has-icon role="alert" :closable="false" :message="errors.course[0]">
        </b-notification>

        <b-tabs v-model="activeTab">
           <b-tab-item label="About you">

            <b-field label="First name" :message="errors.first_name" :type="errors.first_name ? 'is-danger' : null">
              <b-input required name="first_name" v-model="form.first_name"></b-input>
            </b-field>

            <b-field label="Last name" :message="errors.last_name" :type="errors.last_name ? 'is-danger' : null">
              <b-input required name="last_name" v-model="form.last_name"></b-input>
            </b-field>

            <b-field label="Photo">
              <camera-field mode="photo" v-model="form.photo"></camera-field>
            </b-field>

            <b-field label="Introduce yourself">
              <b-input v-model="form.description" name="description" type="textarea" maxlength="120" rows="4" required placeholder="Introduce yourself to the other students.">
              </b-input>
            </b-field>
          </b-tab-item>

          <b-tab-item label="Notifications">
            <b-field label="Email notifications">
              <div class="mb-5">
                <p class="mb-2">Send me an email when:</p>
                <b-checkbox v-for="(value, key) in notification_emails" :key="key" v-model="form.notification_emails" :native-value="key">
                  {{ value }}
                </b-checkbox>
              </div>
            </b-field>
          </b-tab-item>

        </b-tabs>

        <hr>

        <b-button type="is-primary" @click.prevent="onSubmit" expanded>Save</b-button>

      </form>
    </div>
  </div>
</app-layout>
</template>

<script>
import AppLayout from '%/Layouts/AppLayout'
import CameraField from '%/components/CameraField'

export default {
  props: ['errors', 'user', 'admin', 'notification_emails'],
  components: {
    AppLayout,
    CameraField
  },
  data() {
    return {
      activeTab: window.location.hash ? parseInt(window.location.hash.replace('#','')) : 0,
      form: this.$inertia.form({
        _method: 'put',
        id: this.user.id,
        first_name: this.user.first_name ?? null,
        last_name: this.user.last_name ?? null,
        description: this.user.description ?? null,
        photo: (this.user.photo && !this.user.photo.startsWith('https://ui-avatars.com')) ? this.user.photo : null,
        notification_emails: this.user.notification_emails ?? [],
      })
    }
  },
  watch: {
    activeTab: function(tab) {
      window.location.hash = tab;
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
    },

    // updateHash(value) {
    //   window.location.hash = value;
    // }
  }
}
</script>

<style lang="scss">

</style>
