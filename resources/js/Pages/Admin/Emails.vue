<template>
  <app-layout>
    <div class="column is-7-tablet is-6-desktop is-5-widescreen is-paddingless">
      <div class="box">
        <h3 class="title has-text-centered">Emails️</h3>
        <p class="subtitle has-text-centered">Preview system generated emails here</p>
        <nav class="panel is-shadowless is-bordered">

          <div class="panel-block is-justify-between">
            New comment
            <b-field>
              <p class="control">
                <b-button tag="a" :href="route('admin.emails.newcomment')">
                  View
                </b-button>
              </p>
            </b-field>
          </div>

          <div class="panel-block is-justify-between">
            New feedback
            <b-field>
              <p class="control">
                <b-button tag="a" :href="route('admin.emails.newfeedback')">
                  View
                </b-button>
              </p>
            </b-field>
          </div>

          <div class="panel-block is-justify-between">
            New reply
            <b-field>
              <p class="control">
                <b-button tag="a" :href="route('admin.emails.newreply')">
                  View
                </b-button>
              </p>
            </b-field>
          </div>

        </nav>
      </div>
    </div>
  </div>
  </app-layout>
</template>
<script>
import AppLayout from '@/Layouts/AppLayout'

export default {
  props: {
  },
  components: {
    AppLayout
  }
}
</script>
