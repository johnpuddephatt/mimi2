<template>
<app-layout>
  <div class="column is-7-tablet is-6-desktop is-5-widescreen">

    <form ref="form" class="box register-form">
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


      <b-field label="First name" :message="errors.first_name" :type="errors.first_name ? 'is-danger' : null">
        <b-input required name="first_name" v-model="userForm.first_name" ></b-input>
      </b-field>

      <b-field label="Last name" :message="errors.last_name" :type="errors.last_name ? 'is-danger' : null">
        <b-input required name="last_name" v-model="userForm.last_name"></b-input>
      </b-field>

      <b-field label="Email" :message="errors.email" :type="errors.email ? 'is-danger' : null">
        <b-input required type="email" name="email" v-model="userForm.email"></b-input>
      </b-field>

      <b-field label="Password" :message="errors.password || 'Minimum 8 characters'" :type="errors.password ? 'is-danger' : null">
        <b-input v-model="userForm.password" name="password" type="password" password-reveal minlength="8" required>
        </b-input>
      </b-field>

      <hr>

      <b-field label="Card holder name">
        <b-input v-model="billing.name" type="text">
        </b-input>
      </b-field>

      <b-field label="Card holder email">
        <b-input v-model="billing.email" type="email">
        </b-input>
      </b-field>

      <stripe-element-card
        ref="stripeElementCard"
        :pk="stripe_public_key" />

      <button @click.prevent="register" class="button is-fullwidth">Sign up</button>
    </form>


  </div>
</app-layout>
</template>

<script>
import AppLayout from '@/Layouts/AppLayout'
import { StripeElementCard } from '@vue-stripe/vue-stripe';


export default {
  props: ['errors', 'stripe_public_key', 'client_secret', 'user_hash', '$parameters'],
  components: {
    AppLayout,
    StripeElementCard
  },
  data() {
    return {
      show_billing_fields: false,
      userForm: this.$inertia.form({
        first_name: null,
        last_name: null,
        email: null,
        password: null
      }),
      paymentForm: this.$inertia.form({
        intent: null,
        paymentMethodId: null
      }),
      billing: {
        name: null,
        email: null,
      }

    }
  },
  mounted() {
  },
  computed: {

  },
  methods: {

    register() {
      this.userForm.post(route('billing.create-user', { payment_type: this.$parameters.payment_type, stripe_price_code: this.$parameters.stripe_price_code }), {
        preserveScroll: true,
        errorBag: 'user',
        onSuccess: () => {
          this.setupIntent();
        },
        onError: errors => {
          this.errorToast('Check the form for errors');
        },
      })
    },
    async setupIntent() {
      const stripe = this.$refs.stripeElementCard.stripe;

      let payment_method = {
        card: this.$refs.stripeElementCard.element,
        billing_details: {
          name: this.billing.name || `${this.userForm.first_name} ${this.userForm.last_name}`,
          email: this.billing.email || this.userForm.email
        }
      };

      if(this.$parameters.payment_type == 'subscription') {
        const { setupIntent, error } = await stripe.confirmCardSetup(this.client_secret, {
          payment_method: payment_method
        });
        this.paymentForm.intent = setupIntent;
      }
      else if (this.$parameters.payment_type == 'single') {
        const { paymentMethod, error } = await stripe.createPaymentMethod(
          'card', payment_method.card, {
              billing_details: payment_method.billing_details
          });
        this.paymentForm.paymentMethodId = paymentMethod.id;
      }
      // if(error) {
      //   this.errorToast('There was a problem processing your payment');
      // }
      this.paymentForm.post(route('billing.process-payment', { payment_type: this.$parameters.payment_type, stripe_price_code: this.$parameters.stripe_price_code, user_hash: this.user_hash }), {
        preserveScroll: true,
        onSuccess: () => {
          this.successToast('Subscription created');
        },
        onError: errors => {
          this.errorToast('Subscription not created');
        },
      })
    },
  }
}
</script>

<style lang="scss">
nav.steps {
  padding: 1em 0;
}
</style>
